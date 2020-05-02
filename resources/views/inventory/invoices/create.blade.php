@extends('layouts.app')
@section('title', 'Crear Venta')

@section('content')

    <create-invoices-component

        route-filter-clients-by-identity-number="{{route('thirds.filterAllByType','client')}}"
        route-filter-products="{{route('inventory.products.filter')}}"

        route-index="{{route('inventory.products.index')}}"
        route-store="{{route('inventory.products.store')}}"
        route-all-categories="{{route('inventory.category.all')}}"
        route-all-warehouses="{{route('warehouses.all')}}"
    ></create-invoices-component>

@endsection
