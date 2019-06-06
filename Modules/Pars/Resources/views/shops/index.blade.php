@extends('pars::layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <h1>МАГАЗИНЫ</h1>
        </div>
    </div>
    <div class="container" style="margin-top: 10px;">

        @if(count($shops))
            <hr class="dl-horizontal">

            <div class="row">
                <div class="col-sm-10 offset-sm-1">
                    @if($message_delete = session()->pull('message_delete'))
                        <div class="alert alert-danger">{{ $message_delete }}</div>
                    @endif
                    <table class="table table-dark">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Магазин</th>
                            <th scope="col">Сайт</th>
                            <th scope="col">Действие</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shops as $shop)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>{{ $shop->name }}</td>
                                <td>{{ $shop->url }}</td>
                                <td>
                                    <div class="container">
                                        <div class="row">
                                            <form action="{{route('ShopDestroy',[$shop->id])}}" method="POST"
                                                  style="margin-right: 5px;">
                                                @method('DELETE')
                                                @csrf
                                                <button class="btn btn-danger" type="submit">Удалить</button>
                                            </form>
                                            <form action="{{route('CategoryIndex',[$shop->id])}}" method="GET">
                                                @method('GET')
                                                @csrf
                                                <button class="btn btn-danger" type="submit">Категории</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
