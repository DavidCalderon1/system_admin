<?php

namespace App\Repositories\Interfaces;

/**
 * Interface CityRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface CityRepositoryInterface
{
    /**
     * @param int $stateId
     * @return array
     */
    public function getCitiesByStateId(int $stateId): array;
}
