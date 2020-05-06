<!DOCTYPE html>
<html>
<head>
    <title>Generate Pdf</title>
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
<h1>{{$sale['prefix']}}-{{$sale['consecutive']}}</h1>

<div class="header">
    <label><b>Cliente: </b>{{$sale['client_name']}} {{$sale['client_last_name']}}  </label><br>
    <label><b>{{$sale['client_identity_type']}}:</b> {{$sale['client_identity_number']}} </label><br>
    <label><b>Contacto:</b> {{$sale['client_contact']}} </label><br>
    <label><b>Fecha:</b> {{$sale['date']}} </label><br>
    <label><b>Descripción:</b> {{$sale['description']}} </label><br>
</div>

<hr>

<table>
    <thead>
    <tr>
        <th>Producto</th>
        <th>Descripción</th>
        <th style="text-align: center">Cantidad</th>
        <th style="text-align: center">Valor unit.</th>
        <th style="text-align: center">% Desc.</th>
        <th style="text-align: center">% Iva</th>
        <th style="text-align: center"> Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sale['sale_products'] as  $product)
        <tr>
            <td style="width: 20%">{{$product['name']}}</td>
            <td style="width: 30%">{{$product['description']}}</td>
            <td style="text-align: center">{{$product['quantity']}}</td>
            <td style="text-align: center">${{$product['price']}}</td>
            <td style="text-align: center">{{$product['discount_percentage']}}</td>
            <td style="text-align: center">{{$product['vat']}}</td>
            <td style="text-align: center">${{$product['total']}}</td>
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
<br>
<br>
<table style="width: 50%">
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
</body>
</html>
