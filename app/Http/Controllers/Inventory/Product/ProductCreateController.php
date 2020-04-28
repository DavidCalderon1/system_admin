<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Class ProductsCreateController
 * @package App\Http\Controllers\Inventory\Products
 */
class ProductCreateController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @return View
     */
    public function create(): View
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_LIST)) {
            abort(404);
        }

        return view('inventory.products.create');
    }

    public function store(ProductRequest $productRequest)
    {

    }
}
