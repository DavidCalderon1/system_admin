@extends('layouts.app')
@section('title', 'Users')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <users-component users-list-route="{{route('user.list')}}"></users-component>
            </div>
        </div>
    </div>
@endsection
