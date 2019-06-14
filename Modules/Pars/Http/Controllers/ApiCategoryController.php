<?php

namespace Modules\Pars\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Pars\Entities\Category;

class ApiCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    { // обнуляем результат
        $res = [];
        $skip = $request->skip;
        $take = $request->take;
        $requireTotalCount = $request->requireTotalCount;
        $requireGroupCount = $request->requireGroupCount;
        $sort = json_decode($request->sort);
//        //filter=[["id","=",7],"or",["name","contains","7"],"or",["url","contains","7"]]
        $filters = json_decode($request->filter);
        $totalSummary = $request->totalSummary;
        $group = json_decode($request->group);
        if (!$sort && !$group) {

            $data = Category::take($take)->skip($skip)->whereActive(true);
            $res['data'] = $data->get();
            $res['totalCount'] = $data->count();
        }
        if ($sort && !$group) {
            $data = Category::take($take)->skip($skip)->whereActive(true);
            $sort_column = $sort[0]->selector;
            $sort_operator = ($sort[0]->desc == true) ? 'asc' : 'desc';
            $res['data'] = $data->orderBy($sort_column, $sort_operator)->get(['id', 'name', 'url', 'active', 'created_at']);
            $res['totalCount'] = Category::count();
        }
        // если есть параметр групировки но нет сортировки в запросе
        if (!$sort && $group) {
            $data = Category::take($take)->skip($skip)->whereActive(true);
            $group_column = $group[0]->selector;
            $group_operator = ($group[0]->desc == true) ? 'asc' : 'desc';
            //  $data = $data
            $keys = $data->groupBy($group_column)->orderBy($group_column, $group_operator)->get($group_column)->toArray();
            foreach ($keys as $key) {
                $a = (array)$key;
                $categories = Category::where($group_column, '=', $a[$group_column])->orderBy($group_column, $group_operator)->get();
                $data_group[] = ['key' => $a[$group_column], 'items' => $categories, 'count' => count($categories), 'summary' => [5, 30]];
            }
            $res['data'] = $data_group;
            $res['groupCount'] =Category::groupBy($group_column);
            $res['totalCount'] = Category::count();
        }
        // если есть параметр групировки и сортировки в запросе
        if ($sort && $group) {
            $data = Category::take($take)->skip($skip)->whereActive(true);
            $group_column = $group[0]->selector;
            $group_operator = ($group[0]->desc == true) ? 'asc' : 'desc';
            $sort_column = $sort[0]->selector;
            $sort_operator = ($sort[0]->desc == true) ? 'asc' : 'desc';
            $keys = $data->groupBy($group_column)->orderBy($group_column, $group_operator)->get($group_column)->toArray();
            foreach ($keys as $key) {
                $a = (array)$key;
                $categories = Category::where($group_column, '=', $a[$group_column])->orderBy($sort_column, $sort_operator)->get();
                $data_group[] = ['key' => $a[$group_column], 'items' => $categories, 'count' =>count($categories), 'summary' => [1, 3]];
            }
            $res['data'] = $data_group;
            $res['groupCount'] = Category::where($group_column, '=', $a[$group_column])->groupBy($group_column)->count();
            $res['totalCount'] = Category::count();
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
