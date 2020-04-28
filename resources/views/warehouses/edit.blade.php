@extends('layouts.app')
@section('title', 'Editar Bodega')

@section('content')
    <form-create-warehouse-component
        route-index="{{route('warehouses.index')}}"
        route-store="{{route('warehouses.update','__ID__')}}"
        route-all-countries="{{route('countries.all')}}"
        route-states-by-country-code="{{route('states.statesByCountryCode',['country_code' => '__ID__'])}}"
        route-cities-by-state-id="{{route('cities.citiesByStateId',['state_id' => '__ID__'])}}"
        country-code-selected-default="CO"
        warehouse-to-edit="{{json_encode($warehouse)}}"
    ></form-create-warehouse-component>
@endsection
