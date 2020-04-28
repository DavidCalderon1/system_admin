<?php

namespace App\Repositories\Interfaces;

/**
 * Interface ProductRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface ProductRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

}
