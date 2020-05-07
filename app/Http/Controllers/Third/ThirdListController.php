<?php

namespace App\Http\Controllers\Third;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ThirdPartiesRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ThirdListController
 * @package App\Http\Controllers\Third
 */
class ThirdListController extends Controller
{
    /**
     * @var ThirdPartiesRepositoryInterface
     */
    protected $thirdPartiesRepository;

    /**
     * ThirdListController constructor.
     * @param ThirdPartiesRepositoryInterface $thirdPartiesRepository
     */
    public function __construct(ThirdPartiesRepositoryInterface $thirdPartiesRepository)
    {
        $this->middleware('auth');
        $this->thirdPartiesRepository = $thirdPartiesRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_LIST)) {
            return abort(404);
        }

        $userSessionCanList = $this->hasPermission(PermissionsConstants::THIRD_LIST);
        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::THIRD_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::THIRD_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::THIRD_DELETE);

        return view('thirds.index', compact(
                'userSessionCanList',
                'userSessionCanCreate',
                'userSessionCanUpdate',
                'userSessionCanDelete')
        );
    }

    public function list(Request $request): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_LIST)) {
            return $this->response(401);
        }

        $filer = $request->validate([
            'name' => 'string',
            'last_name' => 'string',
            'email' => 'string',
            'phone_number' => 'string',
            'identity_number' => 'string',
            'identity_type' => 'string',
        ]);

        $thirds = $this->thirdPartiesRepository->getPagination(10, $filer);

        if (empty($thirds)) {
            return $this->response(404, __('users.users_not_found'));
        }

        $response = [
            'data' => $thirds['data'],
            'pagination' => [
                'current_page' => $thirds['current_page'],
                'first_page_url' => $thirds['first_page_url'],
                'from' => $thirds['from'],
                'last_page' => $thirds['last_page'],
                'last_page_url' => $thirds['last_page_url'],
                'next_page_url' => $thirds['next_page_url'],
                'per_page' => $thirds['per_page'],
                'prev_page_url' => $thirds['prev_page_url'],
                'to' => $thirds['to'],
                'total' => $thirds['total']
            ],
        ];

        return response()->json($response, 200);
    }
}
