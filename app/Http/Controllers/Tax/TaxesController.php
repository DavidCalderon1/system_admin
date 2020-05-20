<?php

namespace App\Http\Controllers\Tax;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\TaxesRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class TaxesController
 * @package App\Http\Controllers\Config\Transactions
 */
class TaxesController extends Controller
{
    /**
     * @var TaxesRepositoryInterface
     */
    protected $taxesRepository;

    /**
     * TaxesController constructor.
     * @param TaxesRepositoryInterface $taxesRepository
     */
    public function __construct(TaxesRepositoryInterface $taxesRepository)
    {
        $this->middleware('auth');
        $this->taxesRepository = $taxesRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|View
     */
    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::CONFIG_TAXES_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::CONFIG_TAXES_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::CONFIG_TAXES_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::CONFIG_TAXES_DELETE);

        return view('taxes.index', compact(
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
        if (!$this->hasPermission(PermissionsConstants::CONFIG_TAXES_LIST)) {
            abort(404);
        }

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';
        $draw = $request->input('draw', 0);

        $taxes = $this->taxesRepository->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($taxes)) {
            return $this->response(404);
        }

        return responseDataTable($taxes, $length, $orderBy, $orderByDir, $draw, $searchValue);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::CONFIG_TAXES_CREATE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $data = $request->validate([
            'name' => ['required', 'string'],
            'type' => ['required', 'string'],
            'percentage' => ['required', 'numeric', 'min:0'],
        ]);

        $created = $this->taxesRepository->create($data);

        if (!$created) {
            return $this->response(500, 'Ha ocurrido un error creando el impuesto');
        }

        return $this->response(201, 'Impuesto creado correctamente');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::CONFIG_TAXES_UPDATE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $data = $request->validate([
            'id' => ['required', 'numeric', 'min:1'],
            'name' => ['required', 'string'],
            'type' => ['required', 'string'],
            'percentage' => ['required', 'numeric', 'min:0'],
        ]);

        $updated = $this->taxesRepository->update($data['id'], $data);

        if (!$updated) {
            return $this->response(500, 'Ha ocurrido un error actualizando el impuesto');
        }

        return $this->response(200, 'Impuesto actualizando correctamente');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::CONFIG_TAXES_DELETE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $deleted = $this->taxesRepository->delete($id);

        if (!$deleted) {
            return $this->response(500, 'Ha ocurrido un error eliminando el impuesto');
        }

        return $this->response(200, 'Impuesto eliminado correctamente');
    }
}
