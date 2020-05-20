<?php

namespace App\Http\Controllers\Inventory\Category;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Repositories\Interfaces\InventoryCategoryRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class CategoryCreateController
 * @package App\Http\Controllers\Inventory\Category
 */
class CategoryCreateController extends Controller
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
     * @param CategoryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        $created = $this->inventoryCategory->store($data);

        if (!$created) {
            return $this->response(500, 'Ha ocurrido un error creando la categoría.');
        }

        return $this->response(201, 'Categoría creada correctamente');
    }
}
