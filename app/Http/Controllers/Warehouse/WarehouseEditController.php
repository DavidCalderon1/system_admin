<?php

namespace App\Http\Controllers\Warehouse;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\WarehoseRequest;
use App\Repositories\Interfaces\CountryRepositoryInterface;
use App\Repositories\Interfaces\WarehousesRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Session\Store as SessionStore;

/**
 * Class WarehouseEditController
 * @package App\Http\Controllers\Warehouse
 */
class WarehouseEditController extends Controller
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
     * @return JsonResponse
     */
    public function update(WarehoseRequest $request): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::WAREHOUSE_UPDATE)) {
            return $this->response(401);
        }

        try {
            $id = $request->get('id');

            $request = $request->validated();

            $country = $this->countryRepository->getCountryByCode($request['country_code']);

            $request['country_id'] = $country['id'];

            $saved = $this->warehousesRepository->update($id, $request);

            if (!$saved) {
                throw new \Exception("A ocurrido un error actualizando la bodega.", 500);
            }

            return $this->response(200, "Bodega actualizada correctamente.");
        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }
    }
}


