<?php

namespace App\Http\Controllers\User;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class UserListController
 * @package App\Http\Controllers\User
 */
class UserListController extends Controller
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
     * @return View
     */
    public function index(): View
    {
        if (!$this->hasPermission(PermissionsConstants::USER_LIST)) {
            abort(404);
        }

        $userSessionCanList = $this->hasPermission(PermissionsConstants::USER_LIST);
        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::USER_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::USER_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::USER_DELETE);

        return view('users.index', compact(
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
        if (!$this->hasPermission(PermissionsConstants::USER_LIST)) {
            return $this->response(401);
        }

        $filer = $request->validate([
            'name' => 'string',
            'email' => 'string',
        ]);

        $users = $this->userRepository->getPagination(5, $filer);

        if (empty($users)) {
            return $this->response(404, __('users.users_not_found'));
        }

        $response = [
            'users' => $users['data'],
            'pagination' => [
                'current_page' => $users['current_page'],
                'first_page_url' => $users['first_page_url'],
                'from' => $users['from'],
                'last_page' => $users['last_page'],
                'last_page_url' => $users['last_page_url'],
                'next_page_url' => $users['next_page_url'],
                'per_page' => $users['per_page'],
                'prev_page_url' => $users['prev_page_url'],
                'to' => $users['to'],
                'total' => $users['total']
            ],
        ];

        return $this->response(200, __('users.users_found'), $response);
    }
}
