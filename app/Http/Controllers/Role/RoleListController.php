<?php

namespace App\Http\Controllers\Role;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\EloquentRoleRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class RoleListController
 * @package App\Http\Controllers\Role
 */
class RoleListController extends Controller
{
    /**
     * @var EloquentRoleRepository
     */
    protected $roleRepository;

    /**
     * RoleListController constructor.
     * @param EloquentRoleRepository $roleRepository
     */
    public function __construct(EloquentRoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    /**
     * @return View
     */
    public function index(): View
    {
        if (!$this->hasPermission(PermissionsConstants::ROLE_LIST)) {
            abort(404);
        }

        $userSessionCanList = $this->hasPermission(PermissionsConstants::ROLE_LIST);
        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::ROLE_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::ROLE_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::ROLE_DELETE);

        return view('roles.index', compact(
            'userSessionCanList',
            'userSessionCanCreate',
            'userSessionCanUpdate',
            'userSessionCanDelete'
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::ROLE_LIST)) {
            return $this->response(401);
        }

        $filer = $request->validate([
            'name' => 'string',
            'slug' => 'string',
        ]);

        $roles = $this->roleRepository->getPagination(5, $filer);

        if (empty($roles)) {
            return $this->response(404, __('users.users_not_found'));
        }

        $response = [
            'roles' => $roles['data'],
            'pagination' => [
                'current_page' => $roles['current_page'],
                'first_page_url' => $roles['first_page_url'],
                'from' => $roles['from'],
                'last_page' => $roles['last_page'],
                'last_page_url' => $roles['last_page_url'],
                'next_page_url' => $roles['next_page_url'],
                'per_page' => $roles['per_page'],
                'prev_page_url' => $roles['prev_page_url'],
                'to' => $roles['to'],
                'total' => $roles['total']
            ],
        ];

        return $this->response(200, __('users.users_found'), $response);
    }
}
