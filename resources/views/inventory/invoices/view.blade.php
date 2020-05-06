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
                <a href="#" class="btn btn-danger btn-sm pull-right ml-2">Eliminar</a>
                <a href="#" class="btn btn-dark btn-sm pull-right ml-2">Anular</a>
                <a href="#" class="btn btn-primary btn-sm pull-right ml-2">Editar</a>
                <a href="{{route('inventory.invoices.download', $sale['id'])}}" class="btn btn-success btn-sm pull-right ml-2">Descargar</a>
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
                <div class="card" >
                    <div class="card-header">
                        <label>CODIGO DE VENDEDOR: </label> {{$sale['seller_code']}}<br>
                        <label>FECHA: </label> {{$sale['date']}}<br>
                        <label>DESCRIPCIÓN: </label> {{$sale['description']}}<br>
                    </div>
                </div>
            </div>
        </div>
        <hr>

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
                    <td>{{$saleProduct['price']}}</td>
                    <td>{{$saleProduct['discount_percentage']}}</td>
                    <td>{{$saleProduct['vat']}}</td>
                    <td>{{$saleProduct['total']}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="4"></td>
                <td colspan="2">Total bruto</td>
                <td colspan="1">${{$sale['totals']['total_gross']}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">Descuentos</td>
                <td colspan="1">${{$sale['totals']['total_discount']}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">Subtotal</td>
                <td colspan="1">${{$sale['totals']['sub_total']}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2">IVA</td>
                <td colspan="1">${{$sale['totals']['total_vat']}}</td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td colspan="2"><b>Total neto $</b></td>
                <td colspan="1">${{$sale['totals']['total']}}</td>
            </tr>
            </tbody>
        </table>

        <table class="table table-bordered" style="width: 50%">
            <thead>
            <tr>
                <th>Forma de pago</th>
                <th>Metodo de pago</th>
                <th>Valor</th>
                <th>Fecha</th>
            </tr>
            </thead>
            <tbody style="text-align: center">
            @foreach($sale['sale_payments'] as $payment)
                <tr>
                    <td>{{$payment['way_to_pay']}}</td>
                    <td>{{$payment['method']}}</td>
                    <td>$ {{$payment['amount']}}</td>
                    <td>{{$payment['date']}}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2"><b>Total Pagos</b></td>
                <td colspan="2">$ {{$sale['totals']['total_payment']}}</td>
            </tr>
            </tbody>
            <tbody>
        </table>
    </div>
@endsection
