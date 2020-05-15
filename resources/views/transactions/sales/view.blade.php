@extends('layouts.app')
@section('title', 'Factura: '.$sale['prefix'].'-'.$sale['consecutive'])
<style>
    #view-sale {
        font-size: 12px;
    }

    #view-sale table {
        font-size: 12px;
    }
</style>
@section('content')

    <div class="container" id="view-sale">
        <div class="row  text-right">
            <div class="col-lg-12">
                @if($sale['status'] !=='Anulada')
                    <button class="btn btn-danger btn-sm pull-right ml-2" id="cancel">Anular</button>
                    <a href="{{route('transactions.sales.edit', $sale['id'])}}"
                       class="btn btn-info btn-sm pull-right ml-2">Editar</a>
                @endif
                <a href="{{route('transactions.sales.print', $sale['id'])}}" target="_blank"
                   class="btn btn-primary btn-sm pull-right ml-2">Imprimir</a>

                <a href="{{route('transactions.sales.download', $sale['id'])}}"
                   class="btn btn-success btn-sm pull-right ml-2">Descargar</a>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group form-group-sm">
                    <div class="card">
                        <div class="card-header">
                            <label>CLIENTE: </label> {{$sale['client_name']}} {{$sale['client_last_name']}}<br>
                            <label>{{$sale['client_identity_type']}}: </label> {{$sale['client_identity_number']}}<br>
                            <label>CONTACTO: </label> {{$sale['client_contact']}}<br>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <label>CODIGO DE VENDEDOR: </label> {{$sale['seller_code']}}<br>
                        <label>FECHA: </label> {{$sale['date']}}<br>
                        @if(!empty($sale['description']))
                            <label>DESCRIPCIÓN: </label> {{$sale['description']}}<br>
                        @endif
                        <label>ESTADO: </label> <span id="status">{{$sale['status']}}</span><br>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <h4>Productos</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">Producto</th>
                    <th scope="col" style="width: 30%">Descripción</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Valor unit.</th>
                    <th scope="col">% Desc.</th>
                    <th scope="col">% Iva.</th>
                    <th scope="col">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sale['sale_products'] as $saleProduct)
                    <tr>
                        <td>{{$saleProduct['name']}}</td>
                        <td>{{$saleProduct['description']}}</td>
                        <td>{{$saleProduct['quantity']}}</td>
                        <td>${{$saleProduct['price_formatted']}}</td>
                        <td>{{$saleProduct['discount_percentage']}}</td>
                        <td>{{$saleProduct['vat']}}</td>
                        <td>${{$saleProduct['total_formatted']}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2">Total bruto</td>
                    <td colspan="1">${{$sale['totals']['total_gross_formatted']}}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2">Descuentos</td>
                    <td colspan="1">${{$sale['totals']['total_discount_formatted']}}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2">Subtotal</td>
                    <td colspan="1">${{$sale['totals']['sub_total_formatted']}}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2">IVA</td>
                    <td colspan="1">${{$sale['totals']['total_vat_formatted']}}</td>
                </tr>
                <tr>
                    <td colspan="4"></td>
                    <td colspan="2"><b>Total neto $</b></td>
                    <td colspan="1">${{$sale['totals']['total_formatted']}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <h4>Pagos</h4>
        <div class="table-responsive">
            <table class="table table-bordered" style="width: 100%">
                <thead>
                <tr>
                    <th>Forma de pago</th>
                    <th>Metodo de pago</th>
                    <th>Valor</th>
                    <th>Fecha</th>
                    <th>Plazo</th>
                    <th>Fecha limite de pago</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sale['sale_payments'] as $payment)
                    <tr>
                        <td>{{$payment['way_to_pay_trans']}}</td>
                        <td>{{$payment['method']}}</td>
                        <td>${{$payment['amount_formatted']}}</td>
                        <td>{{$payment['date']}}</td>
                        @if($payment['way_to_pay'] == 'credit')
                            <td>{{$payment['days_to_pay']}} dias</td>
                            <td>{{$payment['credit_expiration_date']}}</td>
                        @else
                            <td>-</td>
                            <td>-</td>
                        @endif
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2"><b>Total Pagos</b></td>
                    <td colspan="4">${{$sale['totals']['total_payment']}}</td>
                </tr>
                </tbody>
                <tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            alertify.defaults.notifier.position = 'top-right'
            alertify.defaults.notifier.delay = 5
            alertify.defaults.notifier.closeButton = false
            alertify.defaults.glossary.title = ''
            alertify.defaults.glossary.ok = 'Aceptar'
            alertify.defaults.glossary.cancel = 'Cancelar'

            $("#cancel").click(function () {
                alertify.confirm('Esta seguro de anular la factura?', 'Esta acción es irreversible.',
                    function () {
                        $.ajax({
                            url: "{{route('transactions.sales.cancel', $sale['id'])}}",
                            data: {"_token": "{{ csrf_token() }}"},
                            type: 'DELETE',
                        }).done(function (response) {
                            alertify.success(response.message)
                            $('#status').text('Anulada');
                            $("#cancel").remove();
                        }).fail(function (response) {
                            console.log(response)
                            if (response.status === 401) {
                                alertify.error('Acceso denegado, verifique que tenga permisos para esta acción.');
                            } else if (typeof response.responseJSON.message !== undefined) {
                                alertify.error(response.responseJSON.message);
                            } else {
                                alertify.error('Ha ocurrido un error.');
                            }
                        });
                    }, function () {
                    }
                );
            })
        });
    </script>
@endsection
