<?php

namespace App\Http\Controllers\CostCenter;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CostCenterRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class CostCenterController
 * @package App\Http\Controllers\CostCenter
 */
class CostCenterController extends Controller
{
    /**
     * @var CostCenterRepositoryInterface
     */
    protected $costCenterRepository;

    /**
     * CostCenterController constructor.
     * @param CostCenterRepositoryInterface $costCenterRepository
     */
    public function __construct(CostCenterRepositoryInterface $costCenterRepository)
    {
        $this->middleware('auth');
        $this->costCenterRepository = $costCenterRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::COST_CENTER_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::COST_CENTER_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::COST_CENTER_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::COST_CENTER_DELETE);

        return view('costCenter.index', compact(
            'userSessionCanCreate',
            'userSessionCanUpdate',
            'userSessionCanDelete'
        ));
    }

    /**
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::COST_CENTER_LIST)) {
            abort(404);
        }

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';
        $draw = $request->input('draw', 0);

        $costCenters = $this->costCenterRepository->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($costCenters)) {
            return $this->response(404);
        }

        return responseDataTable($costCenters, $length, $orderBy, $orderByDir, $draw, $searchValue);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::COST_CENTER_CREATE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $data = $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);

        $created = $this->costCenterRepository->create($data);

        if (!$created) {
            return $this->response(500, 'Ha ocurrido un error creando el centro de costos');
        }

        return $this->response(201, 'Centro de costos creado correctamente');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::COST_CENTER_UPDATE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $data = $request->validate([
            'id' => ['required', 'numeric', 'min:1'],
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);

        $updated = $this->costCenterRepository->update($data['id'], $data);

        if (!$updated) {
            return $this->response(500, 'Ha ocurrido un error actualizando el centro de costos');
        }

        return $this->response(200, 'Centro de costos actualizado correctamente');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::COST_CENTER_DELETE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $deleted = $this->costCenterRepository->delete($id);

        if (!$deleted) {
            return $this->response(500, 'Ha ocurrido un error eliminando el impuesto');
        }

        return $this->response(200, 'Impuesto eliminado correctamente');
    }
}
