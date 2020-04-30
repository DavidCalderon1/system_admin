@extends('layouts.app')
@section('title', 'Editar Producto')

@section('content')
    <form-create-product-component
        route-index="{{route('inventory.products.index')}}"
        route-store="{{route('inventory.products.update','__ID__')}}"
        route-all-categories="{{route('inventory.category.all')}}"
        route-all-warehouses="{{route('warehouses.all')}}"
        product-to-edit="{{json_encode($product)}}"
    ></form-create-product-component>
@endsection
