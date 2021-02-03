<?php

namespace App\Infrastructure;

interface PromoProductApi
{
    /**
     * @param string $id
     * @return array<mixed>
     */
    public function getProductById(string $id) : array;
}
