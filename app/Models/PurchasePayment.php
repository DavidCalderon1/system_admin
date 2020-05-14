<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PurchasePayment
 * @package App\Models
 */
class PurchasePayment extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'purchase_id',
        'way_to_pay',
        'amount',
        'method',
        'days_to_pay',
        'credit_expiration_date',
        'date',
    ];
}
