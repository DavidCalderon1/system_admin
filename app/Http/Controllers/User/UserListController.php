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

        return view('users.index');
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

        $response = $this->userRepository->getPagination(5, $filer);

        if (empty($response)) {
            return $this->response(404, 'Users not found');
        }

        return $this->response(200, 'Users found', $response);
    }
}
