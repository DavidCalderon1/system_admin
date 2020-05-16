@extends('layouts.app')
@section('title', 'Conceptos de gastos')
@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <concepts-component
                    list-route="{{route('concepts.list')}}"
                    create-route="{{route('concepts.create')}}"
                    update-route="{{route('concepts.update')}}"
                    delete-route="{{route('concepts.delete','__ID__')}}"
                    user-can-create="{{$userSessionCanCreate}}"
                    user-can-edit="{{$userSessionCanUpdate}}"
                    user-can-delete="{{$userSessionCanDelete}}"
                ></concepts-component>
            </div>
        </div>
    </div>
@endsection
