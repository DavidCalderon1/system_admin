<?php

namespace App\Repositories\Interfaces;

/**
 * Interface StateRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface StateRepositoryInterface
{
    /**
     * @param int $countryId
     * @return array
     */
    public function getStatesByCountryId(int $countryId): array;

}
