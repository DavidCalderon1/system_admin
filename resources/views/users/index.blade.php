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
                @sessionHasPermission(\App\Constants\PermissionsConstants::USER_LIST)
                <users-component user-list-route="{{route('user.list')}}"
                                 user-edit-route="{{route('user.edit',['userId' => '__userId__'])}}"
                                 user-create-route="{{route('user.create')}}"
                                 user-destroy-route="{{route('user.destroy',['user' => '__userId__'])}}"
                                 user-can-create="{{$userSessionCanCreate}}"
                                 user-can-update="{{$userSessionCanUpdate}}"
                                 user-can-delete="{{$userSessionCanDelete}}"
                                 user-admin-id="{{\App\Constants\PermissionsConstants::ROLE_ADMIN_ID}}"
                ></users-component>
                @endsessionHasPermission
            </div>
        </div>
    </div>
@endsection
