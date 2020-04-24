@extends('layouts.app')
@section('title', 'Editar Rol')

@section('content')
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <form action="{{route('roles.update', $role)}}" METHOD="POST">
            <input type="hidden" value="{{$role->id}}" name="id">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{__('roles.view.name')}}</label>
                        <input type="text" name="name" class="form-control" value="{{$role->name}}" required>
                        @if($errors->has('name'))
                            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{__('roles.view.slug')}}</label>
                        <input type="text" name="slug" class="form-control" value="{{$role->slug}}" required>
                        @if($errors->has('slug'))
                            <small class="form-text text-danger">{{ $errors->first('slug') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">{{__('users.view.assign_permissions')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $key => $permission)
                            <tr>
                                <td><input type="checkbox" id="permissionRadio{{$permission['id']}}"
                                           name="permissions[{{$key}}]"
                                           value="{{$permission['id']}}"
                                        {{$role->hasPermission($permission) == $permission['id'] ? 'checked='.'"'.'checked'.'"' : '' }}
                                    ></td>
                                <td>{{__("permissions.slug.{$permission['slug']}")}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-success" value="{{__('roles.view.button_create')}}">
            <a href="{{route('roles.index')}}" class="btn btn-danger">{{__('roles.view.button_cancel')}}</a>
        </form>
    </div>
@endsection
