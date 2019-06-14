@extends('pars::layouts.master')

@section('content')
    <div class="card-group">
        <div class="card">
            <img class="card-img-top" src="..." alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Категории</h5>
                <p class="card-text">
                   Кол-во активных категорий:
                   Кол-во неактивных категорий:
                </p>
            </div>
            <div class="card-footer">
                <small class="text-muted">Последнее обновление...</small>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="..." alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title">Товары</h5>
                <p class="card-text">
                   Кол-во товаров:
                </p>
            </div>
            <div class="card-footer">
                <small class="text-muted">Последнее обновление...</small>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="..." alt="Card image cap">
            <div class="card-body alert-danger">
                <h5 class="card-title">Акции</h5>
                <p class="card-text">
                    Кол-во акций:
                </p>
            </div>
            <div class="card-footer">
                <small class="text-muted">Последнее обновление...</small>
            </div>
        </div>
    </div>
@stop
