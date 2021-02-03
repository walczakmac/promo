<?php

namespace App\Infrastructure\Repository;

use App\Domain\Product;

interface ProductRepository
{
    public function save(Product $product) : void;

    /**
     * @param int|null $limit
     * @param int|null $offset
     * @return Product[]
     */
    public function findAll(int $limit = null, int $offset = null) : array;
}
