@extends('layouts.app')
@section('title', 'Crear Venta')

@section('content')
    <create-invoices-component
        route-store="{{route('transactions.sales.store')}}"
        route-index="{{route('transactions.sales.index')}}"
        route-filter-clients="{{route('transactions.sales.filterClientsAjax')}}"
        route-filter-products="{{route('transactions.sales.filterProductsAjax')}}"
        route-sale-view="{{route('transactions.sales.view',['sale_id' => '__ID__'])}}"
        route-sale-download="{{route('transactions.sales.download',['sale_id' => '__ID__'])}}"
        payment-methods="{{json_encode($paymentsMethods)}}"
    ></create-invoices-component>
@endsection
