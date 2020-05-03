@extends('layouts.app')
@section('title', 'Crear Venta')

@section('content')

    <create-invoices-component
        route-store="{{route('inventory.invoices.store')}}"

        route-filter-clients-by-identity-number="{{route('thirds.filterAllByType','client')}}"
        route-filter-products="{{route('inventory.products.filter')}}"

        route-index="{{route('inventory.products.index')}}"

        route-all-categories="{{route('inventory.category.all')}}"
        route-all-warehouses="{{route('warehouses.all')}}"
    ></create-invoices-component>

@endsection
