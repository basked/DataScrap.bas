<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shops</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<h1>Shops</h1>
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
            <form method="post" action="shop">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                           placeholder="Enter Shop Name">
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea name="address" value="{{ old('address') }}" id="address" class="form-control"
                              placeholder="Enter Shop Address"></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Email:</label>
                    <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}"
                           placeholder="Enter Shop Email">
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
                        <th scope="col">Address</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($shops as $shop)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $shop->name }}</td>
                            <td>{{ $shop->address }}</td>
                            <td>{{ $shop->email }}</td>
                            <td>
                                <form action="{{route('ShopDestroy',[$shop->id])}}" method="POST">
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
