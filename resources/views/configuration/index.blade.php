@extends('layouts.app')
@section('title', 'Configuración')
@section('content')
    <div class="container">
        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @if(auth()->user()->can(\App\Constants\PermissionsConstants::USER_LIST) || auth()->user()->can(\App\Constants\PermissionsConstants::ROLE_LIST))
                <li class="nav-item">
                    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab"
                       aria-controls="profile" aria-selected="false">General</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" id="purchases-tab" data-toggle="tab" href="#transactions" role="tab"
                   aria-controls="home" aria-selected="true">Transacciones</a>
            </li>
        </ul>
        <div class="tab-content mt-3" id="myTabContent">
            @if(auth()->user()->can(\App\Constants\PermissionsConstants::USER_LIST) || auth()->user()->can(\App\Constants\PermissionsConstants::ROLE_LIST))
                <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Autenticacion y permisos</h5>
                                    @if(auth()->user()->can(\App\Constants\PermissionsConstants::ROLE_LIST))
                                        <a href="{{route('roles.index')}}" class="card-link">Roles</a><br>
                                    @endif
                                    @if(auth()->user()->can(\App\Constants\PermissionsConstants::USER_LIST))
                                        <a href="{{route('users.index')}}" class="card-link">Usuarios</a><br>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            @endif

            <div class="tab-pane fade" id="transactions" role="tabpanel" aria-labelledby="transactions-tab">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Comprobantes</h5>
                                <a href="#" class="card-link">Facturas</a><br>
                                <a href="#" class="card-link">Cotizaciones</a><br>
                                <a href="#" class="card-link">Gastos</a><br>
                                <a href="#" class="card-link">Compras</a><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Catalogos</h5>
                                <a href="#" class="card-link">Centros de costo</a><br>
                                <a href="#" class="card-link">Conceptos de gasto</a><br>
                                @if(auth()->user()->can(\App\Constants\PermissionsConstants::CONFIG_TAXES_LIST))
                                    <a href="{{route('taxes.index')}}" class="card-link">Impuestos</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            let url = document.location.toString();

            if (url.match('#')) {
                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
            }

            // Change hash for page-reload
            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            })

            $('.nav-tabs a').on('click', function (e) {
                window.location.hash = e.target.hash;
            })
        })
    </script>
@endsection
