@extends('layouts.app')
@section('title', 'Categorías de productos')
@section('content')

    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <inventory-categories-component
            list-route="{{route('inventory.category.list')}}"
            create-route="{{route('inventory.category.create')}}"
            edit-route="{{route('inventory.category.edit','__ID__')}}"
            destroy-route="{{route('inventory.category.destroy','__ID__')}}"
            user-can-create="{{ $userSessionCanCreate }}"
            user-can-update="{{ $userSessionCanUpdate }}"
            user-can-delete="{{ $userSessionCanDelete }}"
        ></inventory-categories-component>
    </div>

@endsection
