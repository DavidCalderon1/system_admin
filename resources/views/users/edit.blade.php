@extends('layouts.app')
@section('title', 'Editar usuario')

@section('content')
    <div class="container">

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="post" action="{{route('user.update', $user)}}">
            @csrf
            <input type="hidden" value="{{$user->id}}" name="id">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Nombre</label>
                        <input type="text" name="name" class="form-control" value="{{$user->name}}" required>
                        @if($errors->has('name'))
                            <small class="form-text text-danger">{{ $errors->first('name') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" class="form-control" value="{{$user->email}}" required>
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
                        <input type="password" name="password" class="form-control" value="">
                        @if($errors->has('password'))
                            <small class="form-text text-danger">{{ $errors->first('password') }}</small>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">confirmation</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               value="">
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
                                        {{$user->hasRole($role->slug) ? 'checked='.'"'.'checked'.'"' : '' }}
                                    ></td>
                                <td>{{$role->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <input type="submit" class="btn btn-success" value="Guardar">
            <a href="{{route('users.index')}}" class="btn btn-danger">Cancelar</a>
        </form>
    </div>
@endsection
