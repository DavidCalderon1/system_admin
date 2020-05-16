@extends('layouts.app')
@section('title', 'Impuestos')
@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                <taxes-component
                    list-route="{{route('taxes.list')}}"
                    create-route="{{route('taxes.create')}}"
                    update-route="{{route('taxes.update')}}"
                    delete-route="{{route('taxes.delete','__ID__')}}"
                    user-can-create="{{$userSessionCanCreate}}"
                    user-can-edit="{{$userSessionCanUpdate}}"
                    user-can-delete="{{$userSessionCanDelete}}"
                ></taxes-component>
            </div>
        </div>
    </div>
@endsection
