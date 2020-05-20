<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SalePayment
 * @package App\Models
 */
class SalePayment extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'sale_id',
        'way_to_pay',
        'amount',
        'method',
        'days_to_pay',
        'credit_expiration_date',
        'date',
    ];

    public $timestamps=false;
}
