@extends('layouts.app')
@section('title', 'Editar usuario')

@section('content')
    <div class="container">

        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form method="post" action="{{route('user.update', $user)}}">
            @csrf

            <input type="hidden" name="id" value="{{ $user->id }}"/>
            <label for="">Nombre</label>
            <input type="text" name="name" value="{{ $user->name }}"/>
            <label for="">Email</label>
            <input type="email" name="email" value="{{ $user->email }}"/>
            <label for="">password</label>
            <input type="password" name="password"/>
            <label for="">confirmation</label>
            <input type="password" name="password_confirmation"/>

            <button type="submit">Send</button>
        </form>
    </div>
@endsection
