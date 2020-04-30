@extends('layouts.app')
@section('title', 'Crear Producto')

@section('content')
    <form-create-product-component
        route-index="{{route('inventory.products.index')}}"
        route-store="{{route('inventory.products.store')}}"
        route-all-categories="{{route('inventory.category.all')}}"
        route-all-warehouses="{{route('warehouses.all')}}"
    ></form-create-product-component>
@endsection
