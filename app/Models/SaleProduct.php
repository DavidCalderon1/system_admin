<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleProduct extends Model
{
    protected $fillable = [
        'sale_id',
        'product_id',
        'warehouse_id',
        'name',
        'price',
        'quantity',
        'discount_percentage',
        'vat',
        'description',
    ];
}
