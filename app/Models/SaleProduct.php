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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'double',
        'discount_percentage' => 'double',
        'vat' => 'double',
    ];

    public $timestamps=false;

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
