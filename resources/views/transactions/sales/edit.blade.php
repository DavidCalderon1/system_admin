@extends('layouts.app')
@section('title', 'Editar Venta')

@section('content')
    <edit-sale-component
        route-update="{{route('transactions.sales.update','__ID__')}}"
        route-index="{{route('transactions.sales.index')}}"
        route-filter-clients="{{route('transactions.sales.filterClientsAjax')}}"
        route-filter-products="{{route('transactions.sales.filterProductsAjax')}}"
        route-sale-view="{{route('transactions.sales.view',['sale_id' => '__ID__'])}}"
        route-sale-download="{{route('transactions.sales.download',['sale_id' => '__ID__'])}}"
        payment-methods="{{json_encode($paymentsMethods)}}"
        sale-data="{{json_encode($sale)}}"
    ></edit-sale-component>
@endsection
