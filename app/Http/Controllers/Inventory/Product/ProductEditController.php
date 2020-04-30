<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProductEditController
 * @package App\Http\Controllers\Inventory\Product
 */
class ProductEditController extends Controller
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
     * ProductEditController constructor.
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
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_UPDATE)) {
            abort(404);
        }

        $product = $this->productRepository->get($id);

        if (!empty($product['image'])) {
            $product['image'] = asset(Storage::url($product['image']));
        }

        $product['warehouses_quantity'] = [];
        if (!empty($product['warehouses'])) {
            foreach ($product['warehouses'] as $warehouse) {
                if (empty($warehouse['pivot'])) {
                    continue;
                }
                $product['warehouses_quantity'][] = [
                    'warehouse_id' => $warehouse['pivot']['warehouse_id'],
                    'quantity' => $warehouse['pivot']['quantity'],
                ];
            }
        }

        return view('inventory.products.edit', compact('product'));
    }

    /**
     * @param ProductRequest $productRequest
     * @return JsonResponse
     */
    public function update(ProductRequest $productRequest): JsonResponse
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

        $id = $productRequest->get('id', 0);
        $warehousesQuantity = $warehousesQuantity->validated();
        $productRequest = $productRequest->validated();

        try {
            $productRequest['description'] = (!empty($productRequest['description'])) ? $productRequest['description'] : '';
            $productRequest['warehouses_quantity'] = $warehousesQuantity['warehouses_quantity'];

            $saved = $this->productRepository->update($id, $productRequest);

            if (!$saved) {
                throw new \Exception("A ocurrido un error actualizando el producto.", 500);
            }

            $this->session->flash('message', "producto actualizado correctamente.");

            return $this->response(201);
        } catch (\Exception $exception) {
            return $this->response($exception->getCode(), $exception->getMessage());
        }
    }
}
