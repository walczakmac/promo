<?php

namespace App\Infrastructure\Repository\InMemory;

use App\Domain\Product;
use App\Infrastructure\Repository\ProductRepository;

class Products implements ProductRepository
{
    /**
     * @var Product[]
     */
    private $products;

    /**
     * @param Product[] $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function save(Product $product): void
    {
        $this->products[] = $product;
    }

    public function findAll(int $limit = null, int $offset = null): array
    {
        return $this->products;
    }
}
