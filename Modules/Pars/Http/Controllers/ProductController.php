<?php

namespace Modules\Pars\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Pars\Entities\Category;
use Modules\Pars\Entities\Product;

class ProductController extends Controller
{


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
       // $categories=Category::where('active',1)->get();

        $categories= Category::with('products')->where('active','1')->orderBy('name')->get();

//        dd($categories products);
     //   $products =$categories[6]->products()->get();


        return view('pars::products.index', [
            'categories' => $categories
        ]);
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

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function productsPars()
    {
        Product::productsPars();
        return redirect('pars/products');
    }

    public function categoryPars($category_id)
    {
        Product::categoryPars($category_id);
       return redirect('pars/categories');
    }

    public function categoriesPars()
    {
        Product::categoriesPars() ;
        return redirect('pars/categories');
    }

}
