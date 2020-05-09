<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PurchaseProduct
 * @package App\Models
 */
class PurchaseProduct extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'purchase_id',
        'product_id',
        'warehouse_id',
        'name',
        'description',
        'quantity',
        'vat',
        'withholding_tax_percentage',
        'total',
    ];
}
