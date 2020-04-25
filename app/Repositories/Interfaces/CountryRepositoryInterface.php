<?php

namespace App\Repositories\Interfaces;

/**
 * Interface CountryRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface CountryRepositoryInterface
{
    /**
     * @return array
     */
    public function getAll(): array;

    /**
     * @param string $countryCode
     * @return array
     */
    public function getCountryByCode(string $countryCode): array;
}
