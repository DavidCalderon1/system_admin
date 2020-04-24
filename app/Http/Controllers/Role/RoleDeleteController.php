<?php

namespace App\Http\Controllers\Role;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class RoleDeleteController
 * @package App\Http\Controllers\User
 */
class RoleDeleteController extends Controller
{
    /**
     * @var RoleRepositoryInterface
     */
    protected $roleRepository;

    /**
     * RoleDeleteController constructor.
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->middleware('auth');

        $this->roleRepository = $roleRepository;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::ROLE_DELETE)) {
            return $this->response(401);
        }

        try {
            if ($id == PermissionsConstants::ROLE_ADMIN_ID) {
                throw new \Exception(__('roles.no_can_delete_super_admin'));
            }

            $response = $this->roleRepository->destroy($id);

            if (empty($response)) {
                throw new \Exception(__('roles.register_not_found'));
            }

            return $this->response(200, __('roles.success_delete'));
        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }
    }
}
