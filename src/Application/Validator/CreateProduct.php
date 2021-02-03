<?php

namespace App\Application\Validator;

use App\Application\Validator;
use App\Domain\Product;
use App\Infrastructure\PromoProductApi;

class CreateProduct implements Validator
{
    /**
     * @var array<string>
     */
    private $errors;

    /**
     * @var PromoProductApi
     */
    private $client;

    public function __construct(PromoProductApi $client)
    {
        $this->errors = [];
        $this->client = $client;
    }

    public function validate(array $data): void
    {
        $this->errors = [];

        if (empty($data['id'])) {
            $this->errors['id'] = 'Product ID must be provided.';
        }
        if (!empty($data['id'])) {
            $promoProduct = $this->client->getProductById($data['id']);
            if (empty($promoProduct)) {
                $this->errors['id'] = sprintf('Promo product with ID %s not found.', $data['id']);
            }
        }
        if (empty($data['name'])) {
            $this->errors['name'] = 'Product name must be provided.';
        }
        if (!empty($data['name']) && strlen($data['name']) > 255) {
            $this->errors['name'] = 'Product name can be only up to 255 characters long.';
        }
        if (empty($data['price'])) {
            $this->errors['price'] = 'Price must be provided.';
        }
        if (!empty($data['price']) && 1 !== preg_match(Product::PRICE_REGEX, $data['price'])) {
            $this->errors['price'] = sprintf('Price must match following regex: %s', Product::PRICE_REGEX);
        }
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
