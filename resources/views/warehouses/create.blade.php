@extends('layouts.app')
@section('title', 'Crear Bodega')

@section('content')
    <form-create-warehouse-component
        route-index="{{route('warehouses.index')}}"
        route-store="{{route('warehouses.store')}}"
        route-all-countries="{{route('countries.all')}}"
        route-states-by-country-code="{{route('states.statesByCountryCode',['country_code' => '__ID__'])}}"
        route-cities-by-state-id="{{route('cities.citiesByStateId',['state_id' => '__ID__'])}}"
        country-code-selected-default="CO"
    ></form-create-warehouse-component>
@endsection
