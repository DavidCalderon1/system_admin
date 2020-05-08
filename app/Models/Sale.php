<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'client_id',
        'prefix',
        'consecutive',
        'client_name',
        'client_last_name',
        'client_identity_number',
        'client_identity_type',
        'client_contact',
        'seller_code',
        'date',
        'description',
        'status',
        'file',
    ];

    /**
     * @return HasMany
     */
    public function saleProducts():HasMany
    {
        return $this->hasMany(SaleProduct::class,'sale_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function salePayments():HasMany
    {
        return $this->hasMany(SalePayment::class,'sale_id', 'id');
    }
}
