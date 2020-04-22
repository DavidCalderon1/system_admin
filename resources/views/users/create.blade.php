@extends('layouts.app')
@section('title', 'Crear usuario')

@section('content')
    <div class="container">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{route('user.store')}}" METHOD="POST">
            @csrf
            <label for="">Nombre</label>
            <input type="text" name="name" required>
            <label for="">Email</label>

            <input type="email" name="email" required>
            <label for="">password</label>

            <input type="password" name="password" required>
            <label for="">confirmation</label>

            <input type="password" name="password_confirmation" required>
            <input type="submit" value="Crear">
        </form>
    </div>
@endsection
