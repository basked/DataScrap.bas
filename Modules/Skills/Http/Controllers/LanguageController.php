<?php

namespace Modules\Skills\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Skills\Entities\Language;
use Modules\Skills\Transformers\LanguageResource;
use Modules\Skills\Transformers\LanguagesCollection;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return LanguagesCollection
     */
    public function index()
    {
        // data не должна быть на верхнем уровне поэтомиу оборачиваем через wrapper
        return new LanguagesCollection(Language::all());
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('skills::create');
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
     * @return LanguageResource
     */
    public function show(Language $language)
    {
         // data не должна быть на верхнем уровне поэтомиу оборачиваем через wrapper
        LanguageResource::withoutWrapping();
        return new LanguageResource($language);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('skills::edit');
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
