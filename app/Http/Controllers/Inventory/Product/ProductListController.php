<?php

namespace App\Http\Controllers\Inventory\Product;

use App\Constants\PermissionsConstants;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Session\Store as SessionStore;
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

    /**
     * ProductCreateController constructor.
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->middleware('auth');
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_LIST)) {
            abort(404);
        }

        $userSessionCanCreate = $this->hasPermission(PermissionsConstants::PRODUCT_CREATE);
        $userSessionCanUpdate = $this->hasPermission(PermissionsConstants::PRODUCT_UPDATE);
        $userSessionCanDelete = $this->hasPermission(PermissionsConstants::PRODUCT_DELETE);

        return view('inventory.products.index', compact(
            'userSessionCanCreate',
            'userSessionCanUpdate',
            'userSessionCanDelete'));

    }

    public function list(Request $request)
    {
        if (!$this->hasPermission(PermissionsConstants::PRODUCT_LIST)) {
            abort(404);
        }

        $filers = $request->validate([
            'code' => 'string|nullable',
            'reference' => 'string|nullable',
            'category' => 'string|nullable'
        ]);

        $products = $this->productRepository->getPagination(10, $filers);

        if (empty($products)) {
            return $this->response(404, 'productos no encontrados');
        }

        foreach ($products['data'] as $key => $datum) {
            if (empty($datum['image'])) {
                continue;
            }

            $products['data'][$key]['image'] = asset(Storage::url($datum['image']));
        }

        $response = [
            'data' => $products['data'],
            'pagination' => [
                'current_page' => $products['current_page'],
                'first_page_url' => $products['first_page_url'],
                'from' => $products['from'],
                'last_page' => $products['last_page'],
                'last_page_url' => $products['last_page_url'],
                'next_page_url' => $products['next_page_url'],
                'per_page' => $products['per_page'],
                'prev_page_url' => $products['prev_page_url'],
                'to' => $products['to'],
                'total' => $products['total']
            ],
        ];

        return response()->json($response, 200);

    }
}
