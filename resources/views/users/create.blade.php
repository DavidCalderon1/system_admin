@extends('layouts.app')
@section('title', 'Crear usuario')

@section('content')
    <div class="container">
        <form action="{{route('user.store')}}" METHOD="POST">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{old('name')}}" required>
                        @if($errors->has('name'))
                            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}" required>
                        @if($errors->has('email'))
                            <small class="form-text text-danger">{{ $errors->first('email') }}</small>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">password</label>
                        <input type="password" name="password" class="form-control" value="{{old('password')}}"
                               required>
                        @if($errors->has('password'))
                            <small class="form-text text-danger">{{ $errors->first('password') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">confirmation</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               value="{{old('password_confirmation')}}" required>
                        @if($errors->has('password_confirmation'))
                            <small class="form-text text-danger">{{ $errors->first('password_confirmation') }}</small>
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
                            <th scope="col">Asignar Roles</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $key => $role)
                            <tr>
                                <td><input type="checkbox" id="customRadio{{$role->id}}" name="roles[{{$key}}]"
                                           value="{{$role->id}}"
                                        {{old('roles.'.$key) == $role->id ? 'checked='.'"'.'checked'.'"' : '' }}
                                    ></td>
                                <td>{{$role->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-success" value="Crear">
            <a href="{{route('users.index')}}" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
@endsection
