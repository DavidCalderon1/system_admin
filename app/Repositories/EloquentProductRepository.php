<?php

namespace App\Repositories;


use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Class EloquentProductRepository
 * @package App\Repositories
 */
class EloquentProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Product
     */
    protected $product;

    /**
     * EloquentProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * @param int $length
     * @param string $orderBy
     * @param string $orderByDir
     * @param string $searchValue
     * @return array
     */
    public function getPagination(int $length, string $orderBy, string $orderByDir, string $searchValue): array
    {
        $products = $this->product->with('category')
            ->where('code', 'LIKE', "%{$searchValue}%")
            ->orWhere('reference', "LIKE", "%{$searchValue}%")
            ->orWhere('vat', "LIKE", "%{$searchValue}%")
            ->orWhereHas('category', function ($query) use ($searchValue,$orderBy, $orderByDir) {
                $query->where('name', 'LIKE', "%{$searchValue}%");
            })->orderBy($orderBy, $orderByDir);

        return $products->paginate($length)->toArray();
    }

    /**
     * @return array
     */
    public function getAll(): array
    {
        $products = $this->product->with('warehouses')->get();

        if (empty($products)) {
            return [];
        }

        $products = $products->toArray();

        foreach ($products as $key => $product) {

            $products[$key]['id'] = (string)$product['id'];
            $products[$key]['text'] = $product['code'] . ' - ' . $product['reference'];

            if (!empty($product['warehouses'])) {
                $products[$key]['warehouses'] = array_map(function ($warehouse) {
                    return [
                        'id' => (string)$warehouse['id'],
                        'text' => $warehouse['name'] . ' -> ' . $warehouse['pivot']['quantity'] . 'und',
                    ];
                }, $product['warehouses']);
            }
        }

        return $products;
    }

    /**
     * @param string $filter
     * @return array
     */
    public function filter(string $filter): array
    {
        return $this->product->with('warehouses')
            ->where('code', 'LIKE', "%{$filter}%")
            ->orWhere('reference', 'LIKE', "%{$filter}%")
            ->limit(20)
            ->get()
            ->toArray();
    }

    /**
     * @param int $productId
     * @return array
     */
    public function get(int $productId): array
    {
        return $this->product->with('category', 'warehouses')
            ->where('id', $productId)
            ->first()
            ->toArray();
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data)
    {
        $product = $this->product->create([
            'category_id' => $data['category_id'],
            'code' => $data['code'],
            'reference' => strtoupper($data['reference']),
            'base_price' => $data['base_price'],
            'vat' => $data['vat'],
            'price' => $data['price'],
            'description' => strtoupper($data['description']),
        ]);

        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $product->image = $data['image']->storePubliclyAs(
                'public/products', $product->code . '.' . $data['image']->getClientOriginalExtension()
            );
            $product->save();
        }

        return $product->refresh();
    }

    /**
     * @param int $id
     * @param $data
     * @return mixed
     */
    public function update(int $id, $data)
    {
        $product = $this->product->where('id', $id)->first();

        $product->update([
            'category_id' => $data['category_id'],
            'code' => $data['code'],
            'reference' => strtoupper($data['reference']),
            'base_price' => $data['base_price'],
            'vat' => $data['vat'],
            'price' => $data['price'],
            'description' => strtoupper($data['description']),
        ]);

        if ($data['image'] === null) {
            Storage::delete([$product->image]);
            $product->image = '';
        }

        if (!empty($data['image']) && $data['image'] instanceof UploadedFile) {
            $product->image = $data['image']->storePubliclyAs(
                'public/products', $product->code . '.' . $data['image']->getClientOriginalExtension()
            );
        }

        return $product->save();
    }

    /**
     * @param int $categoryId
     * @return bool
     */
    public function existWithCategoryId(int $categoryId): bool
    {
        return (bool)$this->product->where('category_id', $categoryId)->get()->count();
    }

    /**
     * @param int $id
     * @return bool
     */
    public function destroy(int $id): bool
    {
        $product = $this->product->where('id', $id)->first();
        $deleted = $product->delete();
        Storage::delete([$product->image]);
        return $deleted;
    }

    /**
     * @param int $productId
     * @param int $warehouseId
     * @param int $quantityToDiscount
     * @return bool
     */
    public function updatePivotSubtractQuantity(int $productId, int $warehouseId, int $quantityToDiscount): bool
    {
        $product = $this->product->with('warehouses')->where('id', $productId)->first();

        foreach ($product->warehouses as $warehouse) {
            if ($warehouseId !== $warehouse->id) {
                continue;
            }

            $warehouse->pivot->quantity = $warehouse->pivot->quantity - $quantityToDiscount;

            return $warehouse->pivot->save();
        }

        $product->warehouses()->attach($warehouseId, [
            'product_id' => $product->id,
            'quantity' => $quantityToDiscount * -1
        ]);

        return true;
    }

    /**
     * @param int $productId
     * @param int $warehouseId
     * @param int $quantityToSum
     * @return bool
     */
    public function updatePivotSumQuantity(int $productId, int $warehouseId, int $quantityToSum): bool
    {
        $product = $this->product->with('warehouses')->where('id', $productId)->first();

        foreach ($product->warehouses as $warehouse) {
            if ($warehouseId !== $warehouse->id) {
                continue;
            }

            $warehouse->pivot->quantity = $warehouse->pivot->quantity + $quantityToSum;
            return $warehouse->pivot->save();
        }

        $product->warehouses()->attach($warehouseId, [
            'product_id' => $product->id,
            'quantity' => $quantityToSum
        ]);

        return true;
    }
}
