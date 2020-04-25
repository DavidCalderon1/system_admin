<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\Interfaces\CountryRepositoryInterface;

/**
 * Class EloquentCountryRepository
 * @package App\Repositories
 */
class EloquentCountryRepository implements CountryRepositoryInterface
{
    /**
     * @var Country
     */
    protected $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        return $this->country->all()->toArray();
    }

    public function getCountryByCode(string $countryCode): array
    {
        return $this->country->where('code', $countryCode)->first()->toArray();
    }
}
