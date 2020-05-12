<!DOCTYPE html>
<html>
<head>
    <title>Factura de compra</title>
    <style>
        body {
            font-size: 12px;
        }

        table, td, th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            height: 50px;
        }

        .header {
            font-size: 14px;
        }
    </style>
</head>
<body>
<h1>{{$purchase['prefix']}}-{{$purchase['consecutive']}}</h1>

<div class="header">
    <label><b>Proveedor: </b>{{$purchase['provider_name']}}</label><br>
    <label><b>{{$purchase['provider_identity_type']}}:</b> {{$purchase['provider_identity_number']}} </label><br>
    <label><b>Dirección:</b> {{$purchase['provider_address']}} </label><br>
    <label><b>Ciudad:</b> {{$purchase['provider_location']}} </label><br>
    <label><b>Fecha:</b> {{$purchase['date']}} </label><br>
    <label><b>Descripción:</b> {{$purchase['description']}} </label><br>
    <label><b>Estado de la factura:</b> {{$purchase['status']}} </label><br>
</div>

<hr>
<h3>Detalles</h3>

<table>
    <thead>
    <tr>
        <th>Producto</th>
        <th>Descripción</th>
        <th style="text-align: center">Cantidad</th>
        <th style="text-align: center">% Iva</th>
        <th style="text-align: center">Ret. en la fuente</th>
        <th style="text-align: center"> Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($purchase['purchase_products'] as  $product)
        <tr>
            <td style="width: 20%">{{$product['name']}}</td>
            <td style="width: 30%">{{$product['description']}}</td>
            <td style="text-align: center">{{$product['quantity']}}</td>
            <td style="text-align: center">{{$product['vat']}}</td>
            <td style="text-align: center">${{$product['withholding_tax_percentage']}}</td>
            <td style="text-align: center">${{$product['total_formatted']}}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3"></td>
        <td colspan="2">Total bruto</td>
        <td colspan="1">${{$purchase['totals']['total_gross_formatted']}}</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="2">Subtotal</td>
        <td colspan="1">${{$purchase['totals']['sub_total_formatted']}}</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="2">Retención en la fuente</td>
        <td colspan="1">${{$purchase['totals']['total_taxes_formatted']}}</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="2">IVA</td>
        <td colspan="1">${{$purchase['totals']['total_vat_formatted']}}</td>
    </tr>
    <tr>
        <td colspan="3"></td>
        <td colspan="2"><b>Total neto $</b></td>
        <td colspan="1">${{$purchase['totals']['total_formatted']}}</td>
    </tr>
    </tbody>
</table>
<br>
<br>
<h3>Informacion de pagos</h3>
<table style="width: 100%">
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
    <tbody style="text-align: center">
    @foreach($purchase['purchase_payments'] as $payment)
        <tr>
            <td>{{$payment['way_to_pay_trans']}}</td>
            <td>{{$payment['method']}}</td>
            <td>$ {{$payment['amount_formatted']}}</td>
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
        <td colspan="1">$ {{$purchase['totals']['total_payment']}}</td>
        <td colspan="3"></td>
    </tr>
    </tbody>
    <tbody>
</table>
</body>
</html>
