<?php

namespace Modules\Pars\Http\Controllers;

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
    public function index(Request $request)
    {
        $res = [];
        $skip = $request->skip;
        $take = $request->take;
        $requireTotalCount = $request->requireTotalCount;
        $requireGroupCount = $request->requireGroupCount;
        $sort = json_decode($request->sort);
        $filter = $request->filter;
        $totalSummary = $request->totalSummary;
        $group = json_decode($request->group);
        $groupSummary = $request->groupSummary;


        // для круппировки
//        if (!is_null($group)) {
//            if ($group[0]->selector) {
//                $res = [];
//                $desc = ($group[0]->desc == true) ? 'desc' : 'asc';
//                //Выбранный для группировки столбец
//                $column = $group[0]->selector;
//                $keys = DB::table('pars_shops')->orderBy($column, $desc)->groupBy($column)->take($take)->skip($skip)->get($column)->toArray();
//                foreach ($keys as $key) {
//                    $a = (array)$key;
//                    $shops = Shop::where($column, '=', $a[$column])->orderBy($column, $desc)->get();
//                    $data[] = ['key' => $a[$column], 'items' => $shops, 'count' => 2, 'summary' => [1, 3]];
//                }
//                $res['data'] = $data;
//                $res['totalCount'] = $shops->count();
//                $res['groupCount'] = $shops->count();
//                $res['summary'] = [20];
//            }
//        } else {
        // только при обычном отображении таблицы
        $data = Shop::take($take)->skip($skip);
        $res['data'] = $data->get(['id', 'name', 'url', 'active', 'created_at']);
        $res['totalCount'] = $data->count();

        // если есть параметр сортировки в запросе
        if ($sort) {
            $res = [];
            $sort_column = $sort[0]->selector;
            $sort_operator = ($sort[0]->desc == true) ? 'asc' : 'desc';
            //    $data = $data->orderBy($sort_column, $sort_operator);
            $res['data'] = $data->orderBy($sort_column, $sort_operator)->get(['id', 'name', 'url', 'active', 'created_at']);
            $res['totalCount'] = $data->count();

        }
        // если есть параметр групировки в запросе
        if ($group) {
            $res = [];
            $group_column = $group[0]->selector;
            $group_operator = ($group[0]->desc == true) ? 'asc' : 'desc';
            //  $data = $data;
            $keys = $data->groupBy($group_column)->orderBy($group_column, $group_operator)->get($group_column)->toArray();
            foreach ($keys as $key) {
                $a = (array)$key;
                $shops = Shop::where($group_column, '=', $a[$group_column])->orderBy($group_column, $group_operator)->get();
                $data_group[] = ['key' => $a[$group_column], 'items' => $shops, 'count' => 15, 'summary' => [1, 3]];
            }
            $res['data'] = $data_group;
            $res['groupCount'] = $data->groupBy($group_column)->orderBy($group_column, $group_operator)->count();
            $res['totalCount'] = 15;


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
