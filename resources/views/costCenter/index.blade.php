@extends('layouts.app')
@section('title', 'Centros de costo')
@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <cost-center-component
                    list-route="{{route('costCenter.list')}}"
                    create-route="{{route('costCenter.create')}}"
                    update-route="{{route('costCenter.update')}}"
                    delete-route="{{route('costCenter.delete','__ID__')}}"
                    user-can-create="{{$userSessionCanCreate}}"
                    user-can-edit="{{$userSessionCanUpdate}}"
                    user-can-delete="{{$userSessionCanDelete}}"
                ></cost-center-component>
            </div>
        </div>
    </div>
@endsection
