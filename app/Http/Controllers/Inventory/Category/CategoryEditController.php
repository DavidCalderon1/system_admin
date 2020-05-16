<?php

namespace App\Http\Controllers\Inventory\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Repositories\Interfaces\InventoryCategoryRepositoryInterface;

/**
 * Class CategoryEditController
 * @package App\Http\Controllers\Inventory\Category
 */
class CategoryEditController extends Controller
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

    public function update(CategoryRequest $categoryRequest)
    {
        $data = $categoryRequest->validated();
        $id = $categoryRequest->get('id', 0);

        $updated = $this->inventoryCategory->update($id, $data);

        if (empty($updated)) {
            return $this->response(500, 'Ha ocurrido un error actualizando la categoría');
        }

        return $this->response(200, 'Categoría actualizada correctamente');

    }
}
