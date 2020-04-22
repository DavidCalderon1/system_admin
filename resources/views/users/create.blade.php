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
                        <form action="{{route('user.store')}}" METHOD="POST">
                            @csrf
                            <input type="text" name="name" required>
                            <input type="email" name="email" required>
                            <input type="password" name="password" required>
                            <input type="password" name="password_confirmation" required >
                            <input type="submit" value="create">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
