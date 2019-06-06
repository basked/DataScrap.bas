@extends('pars::layouts.master')
@section('content')
<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-sm-10 offset-sm-1">
            @if($message = session()->pull('message'))
                <div class="alert alert-success">{{ $message }}</div>
            @endif
                @if($message_delete = session()->pull('message_delete'))
                    <div class="alert alert-danger">{{ $message_delete }}</div>
                @endif
            <form method="post" action="shop">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                           placeholder="Enter Shop Name">
                </div>
                <div class="form-group">
                    <label for="url">URL:</label>
                    <input id="url" type="text" name="url" value="{{ old('url') }}" class="form-control"
                           placeholder="Enter URL"/>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="submit">Добавить</button>
                </div>
            </form>
            @if(count($errors))
                <ul class="alert alert-danger well">
                    @foreach($errors->all() as $error)
                        <li class="list-unstyled">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>


    @if(count($shops))
        <hr class="dl-horizontal">

        <div class="row">
            <div class="col-sm-10 offset-sm-1">
                <table class="table table-dark">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">URL</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($shops as $shop)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $shop->name }}</td>
                            <td>{{ $shop->url }}</td>
                            <td>
                                <row>
                                <form action="{{route('ShopDestroy',[$shop->id])}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                                <form action="{{route('CategoryIndex',[$shop->id])}}" method="GET">
                                    @method('GET')
                                    @csrf
                                    <button class="btn btn-danger" type="submit">Go to</button>
                                </form>
                                </row>
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
