@extends('layouts.app')
@section('title', 'Crear Venta')

@section('content')
    <create-invoices-component
        route-store="{{route('transactions.sales.store')}}"
        all-clients="{{json_encode($clients)}}"
        all-products="{{json_encode($products)}}"
        route-sale-view="{{route('transactions.sales.view',['sale_id' => '__ID__'])}}"
        route-sale-download="{{route('transactions.sales.download',['sale_id' => '__ID__'])}}"
    ></create-invoices-component>
@endsection
