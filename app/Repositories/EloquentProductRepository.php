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
     * @param int $perPage
     * @param array $filers
     * @return array
     */
    public function getPagination(int $perPage, array $filers = []): array
    {
        $products = $this->product->with('category');

        if (!empty($filers['code'])) {
            $products->where('code', 'LIKE', "%{$filers['code']}%");
        }

        if (!empty($filers['reference'])) {
            $products->where('reference', 'LIKE', "%{$filers['reference']}%");
        }

        if (!empty($filers['category'])) {
            $products->whereHas('category', function ($query) use ($filers) {
                $query->where('name', 'LIKE', "%{$filers['category']}%");
            });
        }
        return $products->paginate($perPage)->toArray();
    }

    /**
     * @param string $filter
     * @return array}
     */
    public function filterByCodeOrReference(string $filter): array
    {
        $products = $this->product->with('warehouses')->where('code', 'like', "%{$filter}%")
            ->orWhere('reference', 'like', "%{$filter}%")
            ->limit(20)
            ->get();
        return (!empty($products)) ? $products->toArray() : [];
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

        if (!empty($data['warehouses_quantity'])) {
            foreach ($data['warehouses_quantity'] as $datum) {
                $product->warehouses()->attach($datum['warehouse_id'], [
                    'product_id' => $product->id,
                    'quantity' => $datum['quantity']
                ]);
            }
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

        if (!empty($data['warehouses_quantity'])) {
            $product->warehouses()->detach();
            foreach ($data['warehouses_quantity'] as $datum) {
                $product->warehouses()->attach($datum['warehouse_id'], [
                    'product_id' => $product->id,
                    'quantity' => $datum['quantity']
                ]);
            }
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
        Storage::delete([$product->image]);
        return $product->delete();
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

        return false;
    }
}
