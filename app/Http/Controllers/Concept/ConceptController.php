<?php

namespace App\Http\Controllers\Concept;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ConceptRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ConceptController
 * @package App\Http\Controllers\CostCenter
 */
class ConceptController extends Controller
{
    /**
     * @var ConceptRepositoryInterface
     */
    protected $conceptRepository;

    /**
     * ConceptController constructor.
     * @param ConceptRepositoryInterface $conceptRepository
     */
    public function __construct(ConceptRepositoryInterface $conceptRepository)
    {
        $this->middleware('auth');
        $this->conceptRepository = $conceptRepository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::CONCEPT_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::CONCEPT_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::CONCEPT_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::CONCEPT_DELETE);

        return view('concept.index', compact(
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
        if (!$this->hasPermission(PermissionsConstants::CONCEPT_LIST)) {
            abort(404);
        }

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';
        $draw = $request->input('draw', 0);

        $costCenters = $this->conceptRepository->getPagination($length, $orderBy, $orderByDir, $searchValue);

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
        if (!$this->hasPermission(PermissionsConstants::CONCEPT_CREATE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $data = $request->validate([
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);

        $created = $this->conceptRepository->create($data);

        if (!$created) {
            return $this->response(500, 'Ha ocurrido un error creando el concepto');
        }

        return $this->response(201, 'Concepto creado correctamente');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::CONCEPT_UPDATE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $data = $request->validate([
            'id' => ['required', 'numeric', 'min:1'],
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);

        $updated = $this->conceptRepository->update($data['id'], $data);

        if (!$updated) {
            return $this->response(500, 'Ha ocurrido un error actualizando el concepto');
        }

        return $this->response(200, 'Concepto actualizado correctamente');
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::CONCEPT_DELETE)) {
            return $this->response(500, 'No tienes permisos para esta acción');
        }

        $deleted = $this->conceptRepository->delete($id);

        if (!$deleted) {
            return $this->response(500, 'Ha ocurrido un error eliminando el concepto');
        }

        return $this->response(200, 'Concepto eliminado correctamente');
    }
}
