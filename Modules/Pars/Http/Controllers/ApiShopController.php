<?php

namespace Modules\Pars\Http\Controllers;

use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Pars\Entities\Shop;
use function MongoDB\BSON\toJSON;

class ApiShopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */

    private function Escape_win($path)
    {
        $path = strtoupper($path);
        return strtr($path, array("\U0430" => "а", "\U0431" => "б", "\U0432" => "в",
            "\U0433" => "г", "\U0434" => "д", "\U0435" => "е", "\U0451" => "ё", "\U0436" => "ж", "\U0437" => "з", "\U0438" => "и",
            "\U0439" => "й", "\U043A" => "к", "\U043B" => "л", "\U043C" => "м", "\U043D" => "н", "\U043E" => "о", "\U043F" => "п",
            "\U0440" => "р", "\U0441" => "с", "\U0442" => "т", "\U0443" => "у", "\U0444" => "ф", "\U0445" => "х", "\U0446" => "ц",
            "\U0447" => "ч", "\U0448" => "ш", "\U0449" => "щ", "\U044A" => "ъ", "\U044B" => "ы", "\U044C" => "ь", "\U044D" => "э",
            "\U044E" => "ю", "\U044F" => "я", "\U0410" => "А", "\U0411" => "Б", "\U0412" => "В", "\U0413" => "Г", "\U0414" => "Д",
            "\U0415" => "Е", "\U0401" => "Ё", "\U0416" => "Ж", "\U0417" => "З", "\U0418" => "И", "\U0419" => "Й", "\U041A" => "К",
            "\U041B" => "Л", "\U041C" => "М", "\U041D" => "Н", "\U041E" => "О", "\U041F" => "П", "\U0420" => "Р", "\U0421" => "С",
            "\U0422" => "Т", "\U0423" => "У", "\U0424" => "Ф", "\U0425" => "Х", "\U0426" => "Ц", "\U0427" => "Ч", "\U0428" => "Ш",
            "\U0429" => "Щ", "\U042A" => "Ъ", "\U042B" => "Ы", "\U042C" => "Ь", "\U042D" => "Э", "\U042E" => "Ю", "\U042F" => "Я"));
    }


    private function JsonToSQL($s)
    {
        $string = strtolower($this->Escape_win($s));

        // dd( $string);
        $patterns = array();

        $patterns[0] = '/,"contains","(\w*)"]/u';
        $patterns[1] = '/,"or",/';
        $patterns[2] = '/,"and",/';
        $patterns[3] = '/,"=",null]/';
        $patterns[4] = '/,"<","(.*s*.*)"]/u';
        $patterns[5] = '/,">","(.*s*.*)"]/u';
        $patterns[6] = '/,"=","(.*s*.*)"]/u';
        $patterns[7] = '/,"=",(\d*)/';
        $patterns[8] = '/\[/';
        $patterns[9] = '/\]/';
        $patterns[10] = '/\"/';


        $replacements = array();


        $replacements[10] = ' like \'%$1%\')';
        $replacements[9] = ' or ';
        $replacements[8] = ' and ';
        $replacements[7] = ' is null)';
        $replacements[6] = ' < \'$1\')';
        $replacements[5] = ' > \'$1\')';
        $replacements[4] = ' = \'$1\')';
        $replacements[3] = ' = $1';
        $replacements[2] = '(';
        $replacements[1] = ')';
        $replacements[0] = '';


        return preg_replace($patterns, $replacements, $string);
        // return preg_replace($patterns, $replacements, $string);
    }


    public function index(Request $request)
    {
        
        $model=Shop::class;
        $fields=['id', 'name', 'url', 'active', 'created_at'];

        $res = [];
        $skip = $request->skip;
        $take = $request->take;
        $requireTotalCount = $request->requireTotalCount;
        $requireGroupCount = $request->requireGroupCount;
        $sort = json_decode($request->sort);

        $filters = json_decode($request->filter);
        $totalSummary = $request->totalSummary;
        $group = json_decode($request->group);
        $groupSummary = $request->groupSummary;
        $data = $model::take($take)->skip($skip);
        //1) только при обычном отображении таблицы
        if (!$sort && !$group && !$filters) {
            $res['data'] = $data->get($fields);
            $res['totalCount'] = $model::all()->count();
        }

        //2) только поиск
        if (!$sort && !$group && $filters) {
            $data = $data->whereRaw($this->JsonToSQL(json_encode($filters)));
            if (json_encode($data) != []) {
                $res['data'] = $data->get($fields);
                $res['totalCount'] = $data->count();
            }
        }

        //3) если есть параметр групировки но нет сортировки и фильтра в запросе
        if (!$sort && $group && !$filters) {
            $data_group = [];
            $group_column = $group[0]->selector;
            $group_operator = ($group[0]->desc == true) ? 'asc' : 'desc';
            $keys = $data->groupBy($group_column)->orderBy($group_column, $group_operator)->get($group_column)->toArray();
            foreach ($keys as $key) {
                $a = (array)$key;
                $shops = $model::where($group_column, '=', $a[$group_column])->orderBy($group_column, $group_operator)->get();
                $data_group[] = ['key' => $a[$group_column], 'items' => $shops, 'count' => count($shops), 'summary' => [1, 3]];
            }
            $res['data'] = $data_group;
            $res['groupCount'] = $model::all()->groupBy($group_column)->count();
            $res['totalCount'] = $model::all()->count();
        }

        //4) если есть параметр групировки и сортировки нет фильтрации в запросе
        if (!$sort && $group && $filters) {
            $data_group = [];
            $group_column = $group[0]->selector;
            $group_operator = ($group[0]->desc == true) ? 'asc' : 'desc';
            $data = $data->whereRaw($this->JsonToSQL(json_encode($filters)));
            $keys = $data->groupBy($group_column)->orderBy($group_column, $group_operator)->get($group_column)->toArray();
            foreach ($keys as $key) {
                $a = (array)$key;
                $shops = $model::where($group_column, '=', $a[$group_column])->whereRaw($this->JsonToSQL(json_encode($filters)))->get();
                $data_group[] = ['key' => $a[$group_column], 'items' => $shops, 'count' => count($shops), 'summary' => [1, 3]];
            }
            $res['data'] = $data_group;
            $res['groupCount'] = $model::all()->groupBy($group_column)->count();
            $res['totalCount'] = $model::all()->count();
        }

        //5) если есть параметр сортировки и нет группировки и фильтра в запросе
        if ($sort && !$group && !$filters) {
            $sort_column = $sort[0]->selector;
            $sort_operator = ($sort[0]->desc == true) ? 'asc' : 'desc';
            $res['data'] = $data->orderBy($sort_column, $sort_operator)->get($fields);
            $res['totalCount'] = $data->count();
        }


        //6) если есть параметр сортировки и фильтрации нет группировки в запросе
        if ($sort && !$group && $filters) {
            $sort_column = $sort[0]->selector;
            $sort_operator = ($sort[0]->desc == true) ? 'asc' : 'desc';
            $data = $data->whereRaw($this->JsonToSQL(json_encode($filters)));
            $res['data'] = $data->orderBy($sort_column, $sort_operator)->get($fields);
            $res['totalCount'] =$model::all()->count();
        }


        //7) если есть параметр групировки и сортировки в запросе
        if ($sort && $group && !$filters) {
            $data_group = [];
            $group_column = $group[0]->selector;
            $group_operator = ($group[0]->desc == true) ? 'asc' : 'desc';
            $sort_column = $sort[0]->selector;
            $sort_operator = ($sort[0]->desc == true) ? 'asc' : 'desc';
            $keys = $data->groupBy($group_column)->orderBy($group_column, $group_operator)->get($group_column)->toArray();
            foreach ($keys as $key) {
                $a = (array)$key;
                $shops = $model::where($group_column, '=', $a[$group_column])->orderBy($sort_column, $sort_operator)->get();
                $data_group[] = ['key' => $a[$group_column], 'items' => $shops, 'count' => count($shops), 'summary' => [1, 3]];
            }
            $res['data'] = $data_group;
            $res['groupCount'] = $model::all()->groupBy($group_column)->count();
            $res['totalCount'] =$model::all()->count();
        }


        //8) если есть параметр групировки и сортировки и фильтрации в запросе
        if ($sort && $group && $filters) {
            $data_group = [];
            $group_column = $group[0]->selector;
            $group_operator = ($group[0]->desc == true) ? 'asc' : 'desc';
            $sort_column = $sort[0]->selector;
            $sort_operator = ($sort[0]->desc == true) ? 'asc' : 'desc';
            $data = $data->whereRaw($this->JsonToSQL(json_encode($filters)));
            $keys = $data->groupBy($group_column)->orderBy($group_column, $group_operator)->get($group_column)->toArray();
            foreach ($keys as $key) {
                $a = (array)$key;
                $shops = $model::where($group_column, '=', $a[$group_column])->whereRaw($this->JsonToSQL(json_encode($filters)))->orderBy($sort_column, $sort_operator)->get();
                $data_group[] = ['key' => $a[$group_column], 'items' => $shops, 'count' => count($shops), 'summary' => [1, 3]];
            }
            $res['data'] = $data_group;
            $res['groupCount'] =$model::all()->groupBy($group_column)->count();
            $res['totalCount'] = $model::all()->count();
        }
        return json_encode($res);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('pars::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $shop = new Shop();
        $shop->name = $request->name;
        $shop->url = $request->url;
        $shop->active = $request->active;
        $shop->save();
        return $shop;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('pars::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('pars::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $shop = Shop::findOrFail($id);
        $shop->update();
        if ($request->name) {
            $shop->name = $request->name;
        };
        if ($request->url) {
            $shop->url = $request->url;
        };
        if ($request->active) {
            $shop->active = $request->active;
        };
        $shop->save();
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $shop = Shop::findOrFail($id);
        $shop->delete();
    }
}
