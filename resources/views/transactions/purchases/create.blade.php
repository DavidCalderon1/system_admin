@extends('layouts.app')
@section('title', 'Crear Compra')

@section('content')
    <create-purchase-component
        route-store="{{route('transactions.purchases.store')}}"
        route-index="{{route('transactions.sales.index')}}"
        route-filter-providers="{{route('transactions.purchases.filterProvidersAjax')}}"
        route-filter-products="{{route('transactions.purchases.filterProductsAjax')}}"
        route-sale-view="{{route('transactions.purchases.view',['purchase_id' => '__ID__'])}}"
        route-sale-download="{{route('transactions.purchases.download',['purchase_id' => '__ID__'])}}"
        payment-methods="{{json_encode($paymentsMethods)}}"
    ></create-purchase-component>
@endsection
