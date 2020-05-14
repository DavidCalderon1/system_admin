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
                <a class="nav-link" id="expenses-tab" data-toggle="tab" href="#expenses" role="tab"
                   aria-controls="profile" aria-selected="false">Gastos</a>
            </li>
        </ul>
        <div class="tab-content mt-3" id="myTabContent">
            <div class="tab-pane fade show active" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <purchases-component
                            list-route="{{route('transactions.purchases.list')}}"
                            view-route="{{route('transactions.purchases.view', '__ID__')}}"
                            create-route="{{route('transactions.purchases.create')}}"
                            update-route="{{route('transactions.purchases.update','__ID__')}}"
                            cancel-route="{{route('transactions.purchases.cancel','__ID__')}}"
                            user-can-create="{{$userSessionCanCreate}}"
                            user-can-view="{{$userSessionCanView}}"
                            user-can-edit="{{$userSessionCanEdit}}"
                            user-can-cancel="{{$userSessionCanCancel}}"
                        ></purchases-component>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="expenses" role="tabpanel" aria-labelledby="expenses-tab">...</div>
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
