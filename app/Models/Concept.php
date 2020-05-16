<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Concept
 * @package App\Models
 */
class Concept extends Model
{
    /**
     * @var string[]
     */
    protected $fillable=[
        'name',
        'code',
    ];
}
