<?php

namespace App\Http\Controllers\Inventory\Category;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\InventoryCategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryDeleteController extends Controller
{
    /**
     * @var InventoryCategoryRepositoryInterface
     */
    protected $inventoryCategory;

    /**
     * CategoryDeleteController constructor.
     * @param InventoryCategoryRepositoryInterface $inventoryCategory
     */
    public function __construct(InventoryCategoryRepositoryInterface $inventoryCategory)
    {
        $this->middleware('auth');
        $this->inventoryCategory = $inventoryCategory;
    }

    public function __invoke(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_DELETE)) {
            return $this->response(401);
        }

        try {
            $response = $this->inventoryCategory->delete($id);

            if (empty($response)) {
                throw new \Exception('Categoría no encontrada.');
            }

            return $this->response(200, 'Categoría eliminada correctamente.');
        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }

    }
}
