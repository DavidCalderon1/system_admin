<?php

namespace App\Models;

use App\UsesCases\Interfaces\PurchasesUseCaseInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Purchase
 * @package App\Models
 */
class Purchase extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'provider_id',
        'prefix',
        'consecutive',
        'provider_invoice_number',
        'provider_name',
        'provider_identity_number',
        'provider_identity_type',
        'provider_address',
        'provider_phone_number',
        'provider_location',
        'description',
        'status',
        'include_taxes',
        'file',
        'date',
    ];

    /**
     * @var string[]
     */
    protected $appends = ['invoice_number'];

    /**
     * @return string
     */
    public function getInvoiceNumberAttribute(): string
    {
        return $this->prefix . '-' . $this->consecutive;
    }

    /**
     * @return HasMany
     */
    public function purchaseProducts(): HasMany
    {
        return $this->hasMany(PurchaseProduct::class, 'purchase_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function purchasePayments(): HasMany
    {
        return $this->hasMany(PurchasePayment::class, 'purchase_id', 'id');
    }
}
