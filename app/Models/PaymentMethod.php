<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentMethod
 * @package App\Models
 */
class PaymentMethod extends Model
{
    /**
     * @var string[]
     */
    protected $fillable=['name'];
}
