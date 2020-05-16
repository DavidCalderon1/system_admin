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
            create-route="{{route('inventory.category.store')}}"
            update-route="{{route('inventory.category.update')}}"
            delete-route="{{route('inventory.category.destroy','__ID__')}}"
            user-can-create="{{ $userSessionCanCreate }}"
            user-can-edit="{{ $userSessionCanUpdate }}"
            user-can-delete="{{ $userSessionCanDelete }}"
        ></inventory-categories-component>
    </div>

@endsection
