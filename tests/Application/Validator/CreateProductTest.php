<?php

namespace App\Tests\Application\Validator;

use App\Application\Validator;
use App\Infrastructure\PromoProductApi\InMemoryClient;
use PHPUnit\Framework\TestCase;

class CreateProductTest extends TestCase
{
    /**
     * @dataProvider provideForTestValidate
     */
    public function testValidate($data, $promoProduct, $errors, $isValid)
    {
        $client = new InMemoryClient($promoProduct);
        $validator = new Validator\CreateProduct($client);
        $validator->validate($data);
        $this->assertSame($isValid, $validator->isValid());
        $this->assertSame($errors, $validator->getErrors());
    }

    public function provideForTestValidate() : array
    {
        return [
            [
                'data' => ['id' => 'xxx', 'name' => 'prod 1', 'price' => '100 EUR'],
                'promoProduct' => ['colors' => ['red'], 'sizes' => ['xl']],
                'errors' => [],
                'isValid' => true,
            ],
            [
                'data' => [],
                'promoProduct' => [],
                'errors' => [
                    'id' => 'Product ID must be provided.',
                    'name' => 'Product name must be provided.',
                    'price' => 'Price must be provided.'
                ],
                'isValid' => false,
            ],
            [
                'data' => ['id' => 'qqq', 'name' => 'prod 1', 'price' => '100 EUR'],
                'promoProduct' => [],
                'errors' => [
                    'id' => 'Promo product with ID qqq not found.'
                ],
                'isValid' => false,
            ],
            [
                'data' => [
                    'id' => 'xxx',
                    'name' => str_pad('prod 1', 260, 'prod 1'),
                    'price' => '100 EUR'
                ],
                'promoProduct' => ['colors' => ['red'], 'sizes' => ['xl']],
                'errors' => [
                    'name' => 'Product name can be only up to 255 characters long.'
                ],
                'isValid' => false,
            ],
            [
                'data' => ['id' => 'xxx', 'name' => 'prod 1', 'price' => '100EUR'],
                'promoProduct' => ['colors' => ['red'], 'sizes' => ['xl']],
                'errors' => [
                    'price' => 'Price must match following regex: /^(?<amount>[0-9]+)\s(?<currency>[A-Z]{3})$/'
                ],
                'isValid' => false,
            ],
        ];
    }
}
