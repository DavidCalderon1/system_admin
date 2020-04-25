<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CityRepositoryInterface;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\StateRepositoryInterface;

/**
 * Class LocationController
 * @package App\Http\Controllers\Location
 */
class LocationController extends Controller
{
    /**
     * @var CountryRepositoryInterface
     */
    protected $countryRepository;

    /**
     * @var StateRepositoryInterface
     */
    protected $stateRepository;

    /**
     * @var CityRepositoryInterface
     */
    protected $cityRepository;

    /**
     * LocationController constructor.
     * @param CountryRepositoryInterface $countryRepository
     * @param StateRepositoryInterface $stateRepository
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(
        CountryRepositoryInterface $countryRepository,
        StateRepositoryInterface $stateRepository,
        CityRepositoryInterface $cityRepository
    )
    {
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCountries()
    {
        return response()->json($this->countryRepository->getAll());
    }

    /**
     * @param string $countryCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatesByCountryCode(string $countryCode)
    {
        $country = $this->countryRepository->getCountryByCode($countryCode);

        return response()->json($this->stateRepository->getStatesByCountryId($country['id']));
    }

    /**
     * @param int $stateId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCitiesByStateId(int $stateId)
    {
        return response()->json( $this->cityRepository->getCitiesByStateId($stateId));
    }
}
