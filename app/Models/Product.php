<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Product
 * @package App\Models
 */
class Product extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'category_id',
        'code',
        'reference',
        'base_price',
        'vat',
        'price',
        'image',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'base_price' => 'double',
        'price' => 'double',
    ];

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(InventoryCategory::class);
    }

    /**
     * @return BelongsToMany
     */
    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouse')->withPivot('quantity');
    }
}
