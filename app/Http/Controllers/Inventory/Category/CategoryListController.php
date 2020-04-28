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
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_LIST)) {
            return $this->response(401);
        }

        $filer = $request->validate([
            'name' => 'string',
        ]);

        $categories = $this->inventoryCategory->getPagination(10, $filer);

        if (empty($categories)) {
            return $this->response(404, __('users.users_not_found'));
        }

        $response = [
            'data' => $categories['data'],
            'pagination' => [
                'current_page' => $categories['current_page'],
                'first_page_url' => $categories['first_page_url'],
                'from' => $categories['from'],
                'last_page' => $categories['last_page'],
                'last_page_url' => $categories['last_page_url'],
                'next_page_url' => $categories['next_page_url'],
                'per_page' => $categories['per_page'],
                'prev_page_url' => $categories['prev_page_url'],
                'to' => $categories['to'],
                'total' => $categories['total']
            ],
        ];

        return response()->json($response, 200);
    }

    /**
     * @return JsonResponse
     */
    public function getAllCategories(): JsonResponse
    {
        return response()->json($this->inventoryCategory->getAll());
    }
}

