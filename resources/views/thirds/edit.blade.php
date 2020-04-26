@extends('layouts.app')
@section('title', 'Crear Tercero')

@section('content')
    <form-edit-third-component
        route-index-third="{{route('thirds.index')}}"
        route-update-third="{{route('thirds.update',['third' => '__ID__'])}}"
        route-all-countries="{{route('countries.all')}}"
        route-states-by-country-code="{{route('states.statesByCountryCode',['country_code' => '__ID__'])}}"
        route-cities-by-state-id="{{route('cities.citiesByStateId',['state_id' => '__ID__'])}}"
        third-to-edit-data="{{json_encode($third)}}"
    ></form-edit-third-component>
@endsection
