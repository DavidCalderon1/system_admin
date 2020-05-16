<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CostCenter
 * @package App\Models
 */
class CostCenter extends Model
{
    /**
     * @var string[]
     */
    protected $fillable=[
        'name',
        'code',
    ];
}
