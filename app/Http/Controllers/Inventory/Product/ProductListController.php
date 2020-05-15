<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\ProductWarehouseRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProductListController
 * @package App\Http\Controllers\Inventory\Product
 */
class ProductListController extends Controller
{

    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**+
     * @var ProductWarehouseRepositoryInterface
     */
    protected $productWarehouseRepository;

    /**
     * ProductListController constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param ProductWarehouseRepositoryInterface $productWarehouseRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository, ProductWarehouseRepositoryInterface $productWarehouseRepository)
    {
        $this->middleware('auth');
        $this->productRepository = $productRepository;
        $this->productWarehouseRepository = $productWarehouseRepository;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::PRODUCT_CREATE);
        $userSessionCanView = $this->hasPermission(PermissionsConstants::PRODUCT_VIEW);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::PRODUCT_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::PRODUCT_DELETE);

        return view('inventory.products.index', compact(
            'userSessionCanCreate',
            'userSessionCanView',
            'userSessionCanUpdate',
            'userSessionCanDelete'));

    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_LIST)) {
            abort(404);
        }

        $length = $request->input('length', '');
        $orderBy = $request->input('column', ''); //Index
        $orderByDir = $request->input('dir', 'asc');
        $searchValue = (!empty($request->input('search', ''))) ? $request->input('search') : '';
        $draw = $request->input('draw', 0);

        $products = $this->productRepository->getPagination($length, $orderBy, $orderByDir, $searchValue);

        if (empty($products)) {
            return $this->response(404);
        }

        foreach ($products['data'] as $key => $product) {
            if (empty($product['image'])) {
                continue;
            }
            $products['data'][$key]['image'] = asset(Storage::url($product['image']));
            $products['data'][$key]['base_price'] = '$' . numberFormat($product['base_price']);
            $products['data'][$key]['price'] = '$' . numberFormat($product['price']);
        }

        return responseDataTable($products, $length, $orderBy, $orderByDir, $draw, $searchValue);
    }

    /**
     * @param int $id
     * @return array
     */
    public function view(int $id)
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_VIEW)) {
            abort(404);
        }

        $product = $this->productRepository->get($id);

        $product['price'] = numberFormat($product['price']);
        $product['base_price'] = numberFormat($product['base_price']);
        if (!empty($product['image'])) {
            $product['image'] = asset(Storage::url($product['image']));
        }

        return $this->productWarehouseRepository->mergeWarehousesProduct($product);
    }
}
