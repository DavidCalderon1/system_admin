<?php

namespace App\Repositories;

use App\Models\State;
use App\Repositories\Interfaces\StateRepositoryInterface;

/**
 * Class EloquentStateRepository
 * @package App\Repositories
 */
class EloquentStateRepository implements StateRepositoryInterface
{
    /**
     * @var State
     */
    protected $state;

    /**
     * EloquentStateRepository constructor.
     * @param State $state
     */
    public function __construct(State $state)
    {
        $this->state = $state;
    }

    public function getStatesByCountryId(int $countryId): array
    {
        return $this->state->where('country_id', $countryId)->get()->toArray();
    }
}
