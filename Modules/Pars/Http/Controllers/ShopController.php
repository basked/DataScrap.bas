<?php

namespace Modules\Pars\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Pars\Entities\Shop;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $shops = Shop::orderBy('created_at', 'asc')->get();
        return view('pars::shops.index', [
            'shops' => $shops
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $shops = Shop::all();
        return view('pars::shops.create', compact('shops'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:crm_shops,name',
            'url' => 'required',

        ]);

        $shop = new Shop();
        $shop->name = $request->name;
        $shop->url = $request->url;
        $shop->save();

        return redirect('pars/shop')->with('message', 'Shop created successfully');

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
        Shop::destroy($id);
        return redirect('pars/shop')->with('message_delete', 'Shop deleted');
    }

    public function test()
    {
        $shops = Shop::find(1)->categories;
        foreach ($shops as $shop) {
            echo $shop->name;
        }

    }

}
