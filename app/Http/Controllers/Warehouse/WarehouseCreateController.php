<?php

namespace App\Http\Controllers\Warehouse;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehoseRequest;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\WarehousesRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Session\Store as SessionStore;

/**
 * Class WarehouseCreateController
 * @package App\Http\Controllers\Warehouse
 */
class WarehouseCreateController extends Controller
{
    /**
     * @var WarehousesRepositoryInterface
     */
    protected $warehousesRepository;

    /**
     * @var CountryRepositoryInterface
     */
    protected $countryRepository;

    /**
     * @var SessionStore
     */
    protected $session;

    /**
     * WarehouseCreateController constructor.
     * @param WarehousesRepositoryInterface $warehousesRepository
     * @param CountryRepositoryInterface $countryRepository
     * @param SessionStore $session
     */
    public function __construct(
        WarehousesRepositoryInterface $warehousesRepository,
        CountryRepositoryInterface $countryRepository,
        SessionStore $session
    )
    {
        $this->middleware('auth');
        $this->warehousesRepository = $warehousesRepository;
        $this->countryRepository = $countryRepository;
        $this->session = $session;
    }

    /**
     * @param WarehoseRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WarehoseRequest $request)
    {
        if (!$this->hasPermission(PermissionsConstants::WAREHOUSE_CREATE)) {
            abort(404);
        }

        try {
            $request = $request->validated();

            $country = $this->countryRepository->getCountryByCode($request['country_code']);

            $request['country_id'] = $country['id'];

            $saved = $this->warehousesRepository->store($request);

            if (!$saved) {
                throw new \Exception('Ha ocurrido un error almacenando la bodega.', 500);
            }

            return $this->response(201, 'Bodega creada correctamente.');

        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }
    }
}
