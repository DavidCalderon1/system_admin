@extends('layouts.app')
@section('title', 'Roles')
@section('content')
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session()->get('message') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12">
            <roles-component
                role-list-route="{{route('roles.list')}}"
                role-create-route="{{route('roles.create')}}"
                role-edit-route="{{route('roles.edit',['roleId' => '__ID__'])}}"
                role-destroy-route="{{route('roles.destroy', ['role' => '__ID__'])}}"
                user-can-create="{{$userSessionCanCreate}}"
                user-can-update="{{$userSessionCanUpdate}}"
                user-can-delete="{{$userSessionCanDelete}}"
                role-admin-id="{{\App\Constants\PermissionsConstants::ROLE_ADMIN_ID}}"
            ></roles-component>
        </div>
    </div>
@endsection
