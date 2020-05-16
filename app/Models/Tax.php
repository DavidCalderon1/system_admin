<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Tax
 * @package App\Models
 */
class Tax extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'type',
        'percentage'
    ];
}
