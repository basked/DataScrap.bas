@extends('pars::layouts.master')
@section('content')
    <div class="container">
        <div class="row">
            <h1>ТОВАР</h1>
            <form id=actionProducsPars" action="{{route('ProductsPars')}}" method="GET" style="margin: 6px;">
                @method('GET')
                @csrf
                <button class="btn btn-success" type="submit">Спарсить>>>{{$countCat}}</button>
            </form>
        </div>
    </div>
    <hr class="dl-horizontal">
    @if($message_delete = session()->pull('message_delete'))
        <div class="alert alert-danger">{{ $message_delete }}</div>
    @endif
    @if($message_parse = session()->pull('message_parse'))
        <div class="alert alert-info">{{ $message_parse }}</div>
    @endif
    @if(count($categories))
    <div class="container" style="margin-top: 10px;">
        <div class="row">
            @foreach($categories as $category)
               <div class="col-sm-4">
                <div class="card-group">
                    <div class="card">
                        {{--<img class="card-img-top" src="..." alt="Card image cap">--}}
                        <div class="card-body">
                            <h5 class="card-title">{{$category->name}}</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to
                                additional content. This card has even longer content than the first to show that equal
                                height action.</p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">{{$category->products->count()}}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
                {{----}}
                {{--<th scope="row">{{ $loop->iteration }}</th>--}}
                {{--<td>{{ $product->category['name'] }}</td>--}}
                {{--<td>{{ $product->name }}</td>--}}
                {{--<td>{{ $product->brand }}</td>--}}
                {{--<td>{{ $product->price }}</td>--}}


            </div>
            {{----}}
        </div>
    @endif
    </div>
@endsection

