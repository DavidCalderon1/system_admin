<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class State
 * @package App\Models
 */
class State extends Model
{
    /**
     * @return BelongsTo
     */
    public function cities():BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
