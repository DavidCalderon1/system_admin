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
        $userSessionCanView = $this->hasPermission(PermissionsConstants::THIRD_VIEW);
        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::THIRD_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::THIRD_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::THIRD_DELETE);

        return view('thirds.index', compact(
                'userSessionCanList',
                'userSessionCanView',
                'userSessionCanCreate',
                'userSessionCanUpdate',
                'userSessionCanDelete')
        );
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::THIRD_LIST)) {
            return $this->response(401);
        }

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $draw = $request->input('draw', 0);
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';

        $data = $this->thirdPartiesRepository->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($data)) {
            return $this->response(404);
        }

        return responseDataTable($data, $length, $orderBy, $orderByDir, $draw, $searchValue);
    }
}
