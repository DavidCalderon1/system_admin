<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\Facades\Validator;
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

    /**
     * @var SessionStore
     */
    protected $session;

    /**
     * ProductCreateController constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param SessionStore $session
     */
    public function __construct(ProductRepositoryInterface $productRepository, SessionStore $session)
    {
        $this->middleware('auth');
        $this->productRepository = $productRepository;
        $this->session = $session;
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

    /**
     * @param ProductRequest $productRequest
     * @return JsonResponse
     */
    public function store(ProductRequest $productRequest): JsonResponse
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_CREATE)) {
            return $this->response(401);
        }

        $warehousesQuantity = Validator::make($productRequest->all(), [
            'warehouses_quantity.*.warehouse_id' => 'numeric|min:1|required',
            'warehouses_quantity.*.quantity' => 'numeric|min:0|required'
        ], [
            'warehouses_quantity.*.quantity.required' => 'Este campo es requerido.',
            'warehouses_quantity.*.quantity.numeric' => 'Este campo debe ser numerico (valor mínimo 0).',
            'warehouses_quantity.*.quantity.min' => 'El valor minimo de este campo es 0.',
        ]);

        $warehousesQuantity = $warehousesQuantity->validated();
        $productRequest = $productRequest->validated();

        try {
            $productRequest['description'] = (!empty($productRequest['description'])) ? $productRequest['description'] : '';
            $productRequest['warehouses_quantity'] = $warehousesQuantity['warehouses_quantity'];

            $saved = $this->productRepository->store($productRequest);

            if (!$saved) {
                throw new \Exception('Ah ocurrido un error almacenando el producto.', 500);
            }

            $this->session->flash('message', 'Producto creado correctamente.');

            return $this->response(201);
        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }
    }
}
