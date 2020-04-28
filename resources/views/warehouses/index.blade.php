@extends('layouts.app')
@section('title', 'Bodegas')
@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <warehouses-component
                    list-route="{{route('warehouses.list')}}"
                    create-route="{{route('warehouses.create')}}"
                    edit-route="{{route('warehouses.edit','__ID__')}}"
                    destroy-route="{{route('warehouses.destroy','__ID__')}}"
                    user-can-create="{{$userSessionCanCreate}}"
                    user-can-update="{{$userSessionCanUpdate}}"
                    user-can-delete="{{$userSessionCanDelete}}"
                ></warehouses-component>
            </div>
        </div>
    </div>
@endsection
