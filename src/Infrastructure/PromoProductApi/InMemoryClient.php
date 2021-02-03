<?php

namespace App\Infrastructure\PromoProductApi;

use App\Infrastructure\PromoProductApi;

class InMemoryClient implements PromoProductApi
{
    /**
     * @var array<mixed>
     */
    private $data;

    /**
     * @param array<mixed> $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getProductById(string $id) : array
    {
        return $this->data;
    }
}
