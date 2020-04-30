@extends('layouts.app')
@section('title', 'Productos')
@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <products-component
                    list-route="{{route('inventory.products.list')}}"
                    create-route="{{route('inventory.products.create')}}"
                    edit-route="{{route('inventory.products.edit','__ID__')}}"
                    destroy-route="{{route('warehouses.destroy','__ID__')}}"
                    user-can-create="{{$userSessionCanCreate}}"
                    user-can-update="{{$userSessionCanUpdate}}"
                    user-can-delete="{{$userSessionCanDelete}}"
                ></products-component>
            </div>
        </div>
    </div>
@endsection
