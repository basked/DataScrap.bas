    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{csrf_token()}}">
        @routes

        {{--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">--}}
        {{--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>--}}
        {{--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>--}}


        <title>Магазины</title>

        {{-- Laravel Mix - CSS File --}}
        <link rel="stylesheet" href="{{ mix('css/pars.css') }}">


    </head>
    <body>


    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="http://datascrap.bas/pars/">DataScrap</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="http://datascrap.bas/pars/">Главная<span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="http://datascrap.bas/pars/shops">Магазины</a>
                <a class="nav-item nav-link" href="http://datascrap.bas/pars/categories">Категории</a>
                <a class="nav-item nav-link" href="http://datascrap.bas/pars/products">Товары</a>

            </div>
        </div>
    </nav>

    @yield('content')
    <script src="{{ asset('modules/pars/js/pars_app.js') }}" defer></script>
    {{-- Laravel Mix - JS File --}}
    <script src="{{ mix('js/pars.js') }}"></script>
    </body>

    </html>
