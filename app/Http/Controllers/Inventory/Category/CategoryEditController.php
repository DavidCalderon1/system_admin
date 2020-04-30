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


    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $currentInventoryCategory = $this->inventoryCategory->get($id);

        return view('inventory.categories.edit', compact('currentInventoryCategory'));
    }

    public function update(CategoryRequest $categoryRequest, int $id)
    {
        $data = $categoryRequest->validated();
        $data['description'] = (!empty($data['description'])) ? $data['description'] : '';

        $response = $this->inventoryCategory->update($id, $data);

        if (empty($response)) {
            return redirect()->back()->withInput()->withErrors(["Ah ocurrido un error actualizando la categoria"]);
        }

        return redirect(route('inventory.category.index'))->with('message', 'Categoría actualizada correctamente');

    }
}
