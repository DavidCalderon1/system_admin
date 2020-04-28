@extends('layouts.app')
@section('title', 'Crear Producto')

@section('content')
    <form-create-product-component
        {{--        route-index-third="{{route('thirds.index')}}"--}}
        route-store-third="{{route('inventory.products.store')}}"
        route-all-categories="{{route('inventory.category.all')}}"
    ></form-create-product-component>
@endsection
