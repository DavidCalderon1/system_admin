<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;

class ProductDeleteController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * ProductCreateController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->middleware('auth');
        $this->productRepository = $productRepository;
    }

    public function __invoke(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_DELETE)) {
            return $this->response(401);
        }

        try {

            $response = $this->productRepository->destroy($id);

            if (empty($response)) {
                throw new \Exception('Producto no encontrado.');
            }

            return $this->response(200, 'Producto eliminado correctamente.');
        } catch (\Exception $exception) {
            return $this->response(500, $exception->getMessage());
        }
    }
}
