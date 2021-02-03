<?php

namespace App\Application\Handler;

use App\Application\Handler;
use App\Application\Validator;
use App\Domain\Product;
use App\Infrastructure\PromoProductApi;
use App\Infrastructure\Repository\ProductRepository;

class CreateProduct implements Handler
{
    /**
     * @var ProductRepository
     */
    private $products;

    /**
     * @var PromoProductApi
     */
    private $promoProductApi;

    /**
     * @var Validator\CreateProduct
     */
    private $validator;

    /**
     * @var Product\Factory
     */
    private $factory;

    public function __construct(
        ProductRepository $products,
        PromoProductApi $promoProductApi,
        Validator\CreateProduct $validator,
        Product\Factory $factory
    ) {
        $this->products = $products;
        $this->promoProductApi = $promoProductApi;
        $this->validator = $validator;
        $this->factory = $factory;
    }

    public function handle(array $data): void
    {
        $this->validator->validate($data);
        if (false === $this->validator->isValid()) {
            throw new Validator\Exception\CreateProductValidationFailed($this->validator->getErrors());
        }

        $data['promoProduct'] = $this->promoProductApi->getProductById($data['id']);
        $product = $this->factory->createFrom($data);
        $this->products->save($product);
    }
}
