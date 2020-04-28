<?php

namespace App\Http\Controllers\Warehouse;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WarehousesRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class WarehouseDeleteController
 * @package App\Http\Controllers\Warehouse
 */
class WarehouseDeleteController extends Controller
{
    /**
     * @var WarehousesRepositoryInterface
     */
    protected $warehousesRepository;

    /**
     * WarehouseDeleteController constructor.
     * @param WarehousesRepositoryInterface $warehousesRepository
     */
    public function __construct(WarehousesRepositoryInterface $warehousesRepository)
    {
        $this->middleware('auth');

        $this->warehousesRepository = $warehousesRepository;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::WAREHOUSE_DELETE)) {
            return $this->response(401);
        }

        try {

            $response = $this->warehousesRepository->delete($id);

            if (empty($response)) {
                throw new \Exception("Bodega no encontrada.");
            }

            return $this->response(200, "Bodega eliminada correctamente.");
        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }
    }
}
