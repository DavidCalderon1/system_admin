<?php

namespace App\Repositories;


use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

/**
 * Class EloquentProductRepository
 * @package App\Repositories
 */
class EloquentProductRepository implements ProductRepositoryInterface
{
    protected $product;

    /**
     * EloquentProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function store(array $data)
    {
        dd($data);
    }
}
