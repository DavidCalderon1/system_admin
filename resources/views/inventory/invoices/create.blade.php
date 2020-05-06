@extends('layouts.app')
@section('title', 'Crear Venta')

@section('content')

    <create-invoices-component
        route-store="{{route('inventory.invoices.store')}}"
        route-filter-clients-by-identity-number="{{route('thirds.filterAllByType','client')}}"
        route-filter-products="{{route('inventory.products.filter')}}"
        route-sale-view="{{route('inventory.invoices.view',['sale_id' => '__ID__'])}}"
        route-sale-download="{{route('inventory.invoices.download',['sale_id' => '__ID__'])}}"
    ></create-invoices-component>

@endsection
