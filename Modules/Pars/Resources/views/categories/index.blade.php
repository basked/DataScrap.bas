@extends('pars::layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <h1>КАТЕГОРИИ</h1>
            <div class="row" style="margin-left: 12px">
                <form id=actionParsCategories" action="{{route('CategoriesPars')}}" method="GET" style="margin: 6px;">
                    @method('GET')
                    @csrf
                    <button class="btn btn-success" type="submit">Спарсить>>></button>
                </form>
                <form id=actionParsCategories" action="{{route('CategoriesUpdate')}}" method="GET" style="margin: 6px;">
                    @method('GET')
                    @csrf
                    <button class="btn btn-success" type="submit">Обновить кол-во</button>
                </form>
                <form action="{{route('ProdСategoriesPars')}}" method="GET" style="margin: 6px;">
                    @method('GET')
                    @csrf
                    <button class="btn btn-success" type="submit">Обновить товары</button>
                </form>
                <form action="{{route('ProdСategoriesNullPars')}}" method="GET" style="margin: 6px;">
                    @method('GET')
                    @csrf
                    <button class="btn btn-success" type="submit">Повторно обновить товары</button>
                </form>
                <form action="{{route('ProdImportToSam')}}" method="GET" style="margin: 6px;">
                    @method('GET')
                    @csrf
                    <button class="btn btn-success" type="submit">Импорт</button>
                </form>

            </div>

        </div>
    </div>
    <hr class="dl-horizontal">
    @if($message_delete = session()->pull('message_delete'))
        <div class="alert alert-danger">{{ $message_delete }}</div>
    @endif
    @if($message_parse = session()->pull('message_parse'))
        <div class="alert alert-info">{{ $message_parse }}</div>
    @endif
    <div class="container" style="margin-top: 10px;">
        <div class="row">
            @if(count($inactCategories))
                <div class="col-sm-6">
                    <div style="margin-left: 10px" class="row"><h2>Неактивные</h2>
                        <button style="margin-left: 10px" class="btn btn-success" type="submit"
                                onclick="PostActiveCategory()">Активировать выделенные
                        </button>
                    </div>
                    <table class="table table-dark">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Категория</th>
                            <th scope="col">Активость</th>
                            <th scope="col">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($inactCategories as $category)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td><a target="_blank" href="https://5element.by{{$category->url }}"
                                       style="color:#F5F5F5">{{ $category->name }}({{ $category->products_cnt }})</a>
                                </td>

                                {{--<td>{{ $category->url }}</td>--}}
                                <td><input @if ($category->active) checked @endif class="check-data"
                                           id="{{$category->id}}"
                                           type="checkbox" onclick="getCheckCategories({{$category->id}})"></td>
                                <td>
                                    <div class="container">
                                        <div class="row">
                                            <form action="{{route('CategoryActive',[$category->id])}}" method="GET">
                                                @method('GET')
                                                @csrf
                                                <button class="btn btn-danger" type="submit">+</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            @if(count($actCategories))
                <div class="col-sm-6">
                    <h2>Активные Категорий:{{count($actCategories)}} </h2>
                    <table class="table table-active">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Категория</th>
                            {{--<th scope="col">Активность</th>--}}
                            <th scope="col">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($actCategories as $category)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td><a target="_blank" href="https://5element.by{{$category->url }}"
                                       style="color:#000000">{{ $category->name }}({{ count($category->products) }}
                                        из {{ $category->products_cnt }})</a></td>
                                {{--<td>{{ $category->url }}</td>--}}
                                {{--<td><input @if ($category->active) checked @endif class="check-data"--}}
                                {{--id="{{$category->id}}"--}}
                                {{--type="checkbox" onclick="getCheckCategories({{$category->id}})" checked></td>--}}
                                <td>
                                    <div class="container">
                                        <div class="row">
                                            <form action="{{route('ProdСategoryPars',[$category->site_id])}}"
                                                  method="GET"
                                                  style="margin-right: 5px;">
                                                @method('GET')
                                                @csrf
                                                <button class="btn btn-danger" type="submit">></button>
                                            </form>
                                            <form action="{{route('CategoryInactive',[$category->id])}}" method="GET">
                                                @method('GET')
                                                @csrf
                                                <button class="btn btn-danger" type="submit">-</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
@endsection
