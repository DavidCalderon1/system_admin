@extends('layouts.app')
@section('title', 'Crear Tercero')

@section('content')
    <form-create-third-component
        route-index-third="{{route('thirds.index')}}"
        route-store-third="{{route('thirds.store')}}"
        route-all-countries="{{route('countries.all')}}"
        route-states-by-country-code="{{route('states.statesByCountryCode',['country_code' => '__ID__'])}}"
        route-cities-by-state-id="{{route('cities.citiesByStateId',['state_id' => '__ID__'])}}"
        country-code-selected-default="CO"
    ></form-create-third-component>
@endsection
