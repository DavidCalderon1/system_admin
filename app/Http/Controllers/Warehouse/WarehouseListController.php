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

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';
        $draw = $request->input('draw', 0);

        $warehouses = $this->warehousesRepository->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($warehouses)) {
            return $this->response(404, 'Bodegas no encontradas');
        }

        return responseDataTable($warehouses, $length, $orderBy, $orderByDir, $draw, $searchValue);
    }

    /**
     * @return JsonResponse
     */
    public function getAllWarehouses(): JsonResponse
    {
        return response()->json($this->warehousesRepository->getAll());
    }
}
