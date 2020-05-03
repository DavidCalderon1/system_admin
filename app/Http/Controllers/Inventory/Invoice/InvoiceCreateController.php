<?php

namespace App\Http\Controllers\Inventory\Invoice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InvoiceCreateController extends Controller
{
    public function __construct()
    {
    }

    public function create()
    {
        return view('inventory.invoices.create');
    }

    public function store(Request $request, bool $download)
    {
        dd($download, $request->all());
    }
}
