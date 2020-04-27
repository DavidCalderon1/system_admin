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

    public function create()
    {
        if (!$this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_CREATE)) {
            abort(404);
        }

        return view('inventory.categories.create');

    }

    /**
     * @param CategoryRequest $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $data['description'] = (!empty($data['description'])) ? $data['description'] : '';
        $response = $this->inventoryCategory->store($data);

        if (empty($response)) {
            return redirect()->back()->withInput()->withErrors(["Ah ocurrido un error almacenadno la categoria"]);
        }

        return redirect(route('inventory.category.index'))->with('message', 'Categoría creada correctamente');
    }
}
