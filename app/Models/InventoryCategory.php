<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InventoryCategory
 * @package App\Models
 */
class InventoryCategory extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description'
    ];
}
