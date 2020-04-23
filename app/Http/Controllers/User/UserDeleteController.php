<?php

namespace App\Http\Controllers\User;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

/**
 * Class UserDeleteController
 * @package App\Http\Controllers\User
 */
class UserDeleteController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * UserListController constructor.
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth');

        $this->userRepository = $userRepository;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function __invoke(int $id): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::USER_DELETE)) {
            return $this->response(401);
        }

        try {
            if ($id == PermissionsConstants::ROLE_ADMIN_ID) {
                throw new \Exception(__('users.no_can_delete_super_admin'));
            }

            $response = $this->userRepository->destroy($id);

            if (empty($response)) {
                throw new \Exception(__('users.register_found'));
            }

            return $this->response(200, __('users.success_delete'));
        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }
    }
}
