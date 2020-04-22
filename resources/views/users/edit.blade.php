@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">USER CREATE VIEW</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @role('super-admin')
                        Hello super admin
                        @endrole
                        <form method="post" action="{{route('user.update', $user)}}">
                            @csrf

                            <input type="hidden" name="id" value="{{ $user->id }}"/>

                            <input type="text" name="name" value="{{ $user->name }}"/>

                            <input type="email" name="email" value="{{ $user->email }}"/>

                            <input type="password" name="password"/>

                            <input type="password" name="password_confirmation"/>

                            <button type="submit">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
