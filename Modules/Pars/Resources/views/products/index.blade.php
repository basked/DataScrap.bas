@extends('pars::layouts.master')
@section('content')
    <div class="demo-container">
        <div id="app">
            <span v-if="false">Загрузка...</span>
            <dev-grid-products></dev-grid-products>
        </div>
    </div>
@endsection



{{--@extends('pars::layouts.master')--}}
{{--@section('content')--}}
    {{--<div class="container">--}}
        {{--<div class="row">--}}
            {{--<h1>ТОВАР</h1>--}}
            {{--<form id=actionProducsPars" action="{{route('ProductsPars')}}" method="GET" style="margin: 6px;">--}}
                {{--@method('GET')--}}
                {{--@csrf--}}
                {{--<button class="btn btn-success" type="submit">Спарсить>>></button>--}}
            {{--</form>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<hr class="dl-horizontal">--}}
    {{--@if($message_delete = session()->pull('message_delete'))--}}
        {{--<div class="alert alert-danger">{{ $message_delete }}</div>--}}
    {{--@endif--}}
    {{--@if($message_parse = session()->pull('message_parse'))--}}
        {{--<div class="alert alert-info">{{ $message_parse }}</div>--}}
    {{--@endif--}}
    {{--<div class="container" style="margin-top: 10px;">--}}
        {{--<div class="row">--}}

            {{--@foreach($categories as  $category)--}}
            {{--@if(count($category->products))--}}
                {{--<h1>{{$category->name}}</h1>--}}
                {{--<div class="col-sm-12">--}}
                    {{--<table class="table table-dark">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th scope="col">#</th>--}}
                            {{--<th scope="col">Категория</th>--}}
                            {{--<th scope="col">Наименование</th>--}}
                            {{--<th scope="col">Бренд</th>--}}
                            {{--<th scope="col">Стоимость</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach($category->products as $product)--}}
                            {{--<tr>--}}
                                {{--<th scope="row">{{ $loop->iteration }}</th>--}}
                                {{--<td>{{ $product->category['name'] }}</td>--}}
                                {{--<td>{{ $product->name }}</td>--}}
                                {{--<td>{{ $product->brand }}</td>--}}
                                {{--<td>{{ $product->price }}</td>--}}
                            {{--</tr>--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                {{--</div>--}}
            {{--@endif--}}
            {{--@endforeach--}}
        {{--</div>--}}
    {{--</div>--}}
{{--@endsection--}}
{{--<script>--}}
    {{--import DevGridProducts from "../../assets/js/components/DevGridProducts";--}}
    {{--export default {--}}
        {{--components: {DevGridProducts}--}}
    {{--}--}}
{{--</script>--}}