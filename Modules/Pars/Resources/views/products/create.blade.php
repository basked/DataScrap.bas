<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Model Observer</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<h1>Products</h1>
<hr>
<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col-sm-10 offset-sm-1">
            @if($message = session()->pull('message'))
                <div class="alert alert-success">{{ $message }}</div>
            @endif
            @if($message_delete = session()->pull('message_delete'))
                <div class="alert alert-danger">{{ $message_delete }}</div>
            @endif
            <form method="post" action="product">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Product Name">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description"   id="description" class="form-control"
                              placeholder="Enter Product Description">{{ old('description') }}</textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price"  value="{{ old('price') }}" placeholder="Enter Product Price">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}"
                           placeholder="Enter Product Quantity">
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

    @if(count($products))
        <hr class="dl-horizontal">
        <div class="row">
            <div class="col-sm-10 offset-sm-1">
                <table class="table table-dark">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($products as $product)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>{{ $product->price }}</td>
                            <td>
                                <form action="{{route('ProductDestroy',[$product->id])}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-danger" type="submit">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
</body>
</html>
