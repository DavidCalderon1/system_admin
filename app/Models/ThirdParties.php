<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class ThirdParties
 * @package App\Models
 */
class ThirdParties extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = [
        'identity_type',
        'identity_number',
        'type_person',
        'type',
        'name',
        'last_name',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'phone_number',
        'phone_extension',
        'email',
        'description',
    ];

    /**
     * @return BelongsTo
     */
    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * @return BelongsTo
     */
    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
