@extends('layouts.app')
@section('title', 'Ventas')
@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <sales-component
                    list-route="{{route('transactions.sales.list')}}"
                    create-route="{{route('transactions.sales.create')}}"
                    edit-route="{{route('transactions.sales.edit','__ID__')}}"
                    view-route="{{route('transactions.sales.view','__ID__')}}"
                    cancel-route="{{route('transactions.sales.cancel','__ID__')}}"
                    user-can-create="{{$userSessionCanCreate}}"
                    user-can-view="{{$userSessionCanView}}"
                    user-can-edit="{{$userSessionCanEdit}}"
                    user-can-cancel="{{$userSessionCanCancel}}"
                ></sales-component>
            </div>
        </div>
    </div>
@endsection
