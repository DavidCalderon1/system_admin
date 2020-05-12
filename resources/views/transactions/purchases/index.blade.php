@extends('layouts.app')
@section('title', 'Compras/ gastos')
@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="purchases-tab" data-toggle="tab" href="#purchases" role="tab"
                   aria-controls="home" aria-selected="true">Compras</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                   aria-controls="profile" aria-selected="false">Gastos</a>
            </li>
        </ul>
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <sales-component
                            list-route="{{route('transactions.sales.list')}}"
                            create-route="{{route('transactions.sales.create')}}"
                            view-route="{{route('transactions.sales.view','__ID__')}}"
                            cancel-route="{{route('transactions.sales.cancel','__ID__')}}"
                            user-can-create="{{$userSessionCanCreate}}"
                            user-can-view="{{$userSessionCanView}}"
                            user-can-cancel="{{$userSessionCanCancel}}"
                        ></sales-component>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
        </div>
    </div>
@endsection
