<?php

namespace App\Http\Controllers\Warehouse;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\WarehousesRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class WarehouseListController
 * @package App\Http\Controllers\Warehouse
 */
class WarehouseListController extends Controller
{
    /**
     * @var WarehousesRepositoryInterface
     */
    protected $warehousesRepository;

    /**
     * WarehouseListController constructor.
     * @param WarehousesRepositoryInterface $warehousesRepository
     */
    public function __construct(WarehousesRepositoryInterface $warehousesRepository)
    {
        $this->middleware('auth');
        $this->warehousesRepository = $warehousesRepository;
    }

    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::WAREHOUSE_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::WAREHOUSE_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::WAREHOUSE_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::WAREHOUSE_DELETE);

        return view('warehouses.index', compact(
            'userSessionCanCreate',
            'userSessionCanUpdate',
            'userSessionCanDelete'));

    }

    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::WAREHOUSE_LIST)) {
            abort(404);
        }

        $filer = $request->validate([
            'name' => 'string|nullable',
            'country' => 'string|nullable',
            'address' => 'string|nullable',
            'phone_number' => 'string|nullable',
        ]);

        $warehouses = $this->warehousesRepository->getPagination(10, $filer);

        if (empty($warehouses)) {
            return $this->response(404, 'Bodegas no encontradas');
        }

        $response = [
            'data' => $warehouses['data'],
            'pagination' => [
                'current_page' => $warehouses['current_page'],
                'first_page_url' => $warehouses['first_page_url'],
                'from' => $warehouses['from'],
                'last_page' => $warehouses['last_page'],
                'last_page_url' => $warehouses['last_page_url'],
                'next_page_url' => $warehouses['next_page_url'],
                'per_page' => $warehouses['per_page'],
                'prev_page_url' => $warehouses['prev_page_url'],
                'to' => $warehouses['to'],
                'total' => $warehouses['total']
            ],
        ];

        return response()->json($response, 200);
    }

    /**
     * @return JsonResponse
     */
    public function getAllWarehouses(): JsonResponse
    {
        return response()->json($this->warehousesRepository->getAll());
    }
}
