@extends('layouts.app')
@section('title', 'Crear Compra')

@section('content')
    <create-purchase-component
        route-store="{{route('transactions.purchases.create')}}"
        route-index="{{route('transactions.sales.index')}}"
        route-filter-providers="{{route('transactions.purchases.filterProvidersAjax')}}"
        route-filter-products="{{route('transactions.purchases.filterProductsAjax')}}"
        route-sale-view="{{route('transactions.sales.view',['sale_id' => '__ID__'])}}"
        route-sale-download="{{route('transactions.sales.download',['sale_id' => '__ID__'])}}"
        payment-methods="{{json_encode($paymentsMethods)}}"
    ></create-purchase-component>
@endsection
