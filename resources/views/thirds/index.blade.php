@extends('layouts.app')
@section('title', 'Terceros')
@section('content')
    <div class="container" id="index">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <grid-component
                    list-route="{{route('thirds.list')}}"
                    create-route="{{route('thirds.create')}}"
                    edit-route="{{route('thirds.edit','__ID__')}}"
                    destroy-route="{{route('thirds.destroy','__ID__')}}"
                    user-can-create="{{$userSessionCanCreate}}"
                    user-can-update="{{$userSessionCanUpdate}}"
                    user-can-delete="{{$userSessionCanDelete}}"
                ></grid-component>
            </div>
        </div>
    </div>
@endsection