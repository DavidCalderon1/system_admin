@extends('layouts.app')
@section('title', 'Editar Compra')

@section('content')
    <edit-purchase-component
        route-update="{{route('transactions.purchases.update','__ID__')}}"
        route-index="{{route('transactions.purchases.index')}}"
        route-filter-providers="{{route('transactions.purchases.filterProvidersAjax')}}"
        route-filter-products="{{route('transactions.purchases.filterProductsAjax')}}"
        route-sale-view="{{route('transactions.purchases.view',['purchase_id' => '__ID__'])}}"
        route-sale-download="{{route('transactions.purchases.download',['purchase_id' => '__ID__'])}}"
        payment-methods="{{json_encode($paymentsMethods)}}"
        purchase-data="{{json_encode($purchase)}}"
    ></edit-purchase-component>
@endsection
