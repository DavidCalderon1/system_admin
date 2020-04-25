<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\Interfaces\CityRepositoryInterface;

/**
 * Class EloquentCityRepository
 * @package App\Repositories
 */
class EloquentCityRepository implements CityRepositoryInterface
{
    /**
     * @var City
     */
    protected $city;

    /**
     * EloquentCityRepository constructor.
     * @param City $city
     */
    public function __construct(City $city)
    {
        $this->city = $city;
    }

    /**
     * @param int $stateId
     * @return array
     */
    public function getCitiesByStateId(int $stateId): array
    {
        return $this->city->where('state_id', $stateId)->get()->toArray();
    }
}
