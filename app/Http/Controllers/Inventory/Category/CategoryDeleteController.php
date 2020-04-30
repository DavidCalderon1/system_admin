<?php

namespace App\Http\Controllers\Inventory\Category;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\InventoryCategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

class CategoryDeleteController extends Controller
{
    /**
     * @var InventoryCategoryRepositoryInterface
     */
    protected $inventoryCategory;

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * CategoryDeleteController constructor.
     * @param InventoryCategoryRepositoryInterface $inventoryCategory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(InventoryCategoryRepositoryInterface $inventoryCategory, ProductRepositoryInterface $productRepository)
    {
        $this->middleware('auth');
        $this->inventoryCategory = $inventoryCategory;
        $this->productRepository = $productRepository;
    }

    public function __invoke(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::INVENTORY_CATEGORY_DELETE)) {
            return $this->response(401);
        }

        try {
            if($this->productRepository->existWithCategoryId($id)){
                throw new \Exception('Hay productos asociados a esta categoria', 500);
            }

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
