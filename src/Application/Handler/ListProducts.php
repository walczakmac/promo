<?php

namespace App\Application\Handler;

use App\Application\Handler;
use App\Domain\Product;
use App\Infrastructure\Repository\ProductRepository;

class ListProducts implements Handler
{
    /**
     * @var ProductRepository
     */
    private $products;

    public function __construct(ProductRepository $products)
    {
        $this->products = $products;
    }

    /**
     * @param array<mixed> $data
     * @return Product[]
     */
    public function handle(array $data) : array
    {
        $page = !empty($data['page']) && is_int($data['page']) && $data['page'] > 0 ? $data['page'] : 1;
        $limit = !empty($data['limit']) && is_int($data['limit']) && $data['limit'] > 0 ? $data['limit'] : 10;

        return $this->products->findAll($limit, ($page - 1) * $limit);
    }
}
