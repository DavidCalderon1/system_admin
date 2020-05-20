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
                    create-route="{{route('warehouses.store')}}"
                    update-route="{{route('warehouses.update')}}"
                    delete-route="{{route('warehouses.destroy','__ID__')}}"

                    all-countries-route="{{route('countries.all')}}"
                    states-by-country-code-route="{{route('states.statesByCountryCode','__ID__')}}"
                    cities-by-state-id-route="{{route('cities.citiesByStateId','__ID__')}}"
                    country-code-selected-default="CO"

                    user-can-create="{{$userSessionCanCreate}}"
                    user-can-update="{{$userSessionCanUpdate}}"
                    user-can-delete="{{$userSessionCanDelete}}"
                ></warehouses-component>
            </div>
        </div>
    </div>
@endsection
