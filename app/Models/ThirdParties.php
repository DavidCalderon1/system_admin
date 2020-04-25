<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ThirdParties
 * @package App\Models
 */
class ThirdParties extends Model
{
    /**
     * @return HasOne
     */
    public function country(): HasOne
    {
        return $this->hasOne(Country::class);
    }

    /**
     * @return HasOne
     */
    public function state(): HasOne
    {
        return $this->hasOne(State::class);
    }

    /**
     * @return HasOne
     */
    public function city(): HasOne
    {
        return $this->hasOne(City::class);
    }
}
