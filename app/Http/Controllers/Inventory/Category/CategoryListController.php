<?php

namespace App\Http\Controllers\Inventory\Category;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\InventoryCategoryRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryListController extends Controller
{
    /**
     * @var InventoryCategoryRepositoryInterface
     */
    protected $inventoryCategory;

    /**
     * CategoryListController constructor.
     * @param InventoryCategoryRepositoryInterface $inventoryCategory
     */
    public function __construct(InventoryCategoryRepositoryInterface $inventoryCategory)
    {
        $this->middleware('auth');
        $this->inventoryCategory = $inventoryCategory;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_DELETE);

        return view('inventory.categories.index', compact(
            'userSessionCanCreate',
            'userSessionCanUpdate',
            'userSessionCanDelete'));
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_LIST)) {
            return $this->response(401);
        }

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';
        $draw = $request->input('draw', 0);

        $categories = $this->inventoryCategory->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($categories)) {
            return $this->response(404, __('users.users_not_found'));
        }

        return responseDataTable($categories, $length, $orderBy, $orderByDir, $draw, $searchValue);
    }

    /**
     * @return JsonResponse
     */
    public function getAllCategories(): JsonResponse
    {
        return response()->json($this->inventoryCategory->getAll());
    }
}

