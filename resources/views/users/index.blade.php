@extends('layouts.app')
@section('title', 'Users')

@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-12">
                @permission('user-list')
                <users-component user-list-route="{{route('user.list')}}"
                                 user-edit-route="{{route('user.edit',['userId' => '__userId__'])}}"
                                 user-create-route="{{route('user.create')}}"
                                 user-destroy-route="{{route('user.destroy',['user' => '__userId__'])}}"
                                 user-can-list="{{auth()->user()->can('user-list') || auth()->user()->hasRole('super-admin') }}"
                                 user-can-create="{{auth()->user()->can('user-create') || auth()->user()->hasRole('super-admin') }}"
                                 user-can-update="{{auth()->user()->can('user-update') || auth()->user()->hasRole('super-admin') }}"
                                 user-can-delete="{{auth()->user()->can('user-delete') || auth()->user()->hasRole('super-admin') }}"
                ></users-component>
                @endpermission
            </div>
        </div>
    </div>
@endsection
