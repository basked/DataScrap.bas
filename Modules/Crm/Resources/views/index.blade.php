@extends('crm::layouts.master')
@section('content')
    <h1>Hello World</h1>
    <p>
        This view is loaded from module: {!! config('crm.name') !!}
    </p>
    <ul class="list-group">
        @foreach ($shops as $shop)
            <li class="list-group-item">{{ $shop->name }}</li>
        @endforeach
    </ul>
@stop
