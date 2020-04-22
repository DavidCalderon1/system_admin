<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
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
     * define el slug del permiso User List
     */
    public const USER_LIST = 'user-list';

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
        if (!$this->hasPermission(self::USER_LIST)) {
            abort(404);
        }

        return view('users.index');
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        if (!$this->hasPermission(self::USER_LIST)) {
            return $this->response(401);
        }

        $response = $this->userRepository->getPagination(15);

        if (empty($response)) {
            return $this->response(404, 'Users not found');
        }

        return $this->response(200, 'Users found', $response);
    }
}
