<?php

namespace App\Tests\Application\Handler;

use App\Application\Handler;
use App\Application\Validator;
use App\Domain\Product;
use App\Infrastructure\PromoProductApi;
use App\Infrastructure\Repository\InMemory\Products;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class CreateProductTest extends TestCase
{
    public function testSuccess()
    {
        $products = new Products([]);
        $promoProductApi = new PromoProductApi\InMemoryClient([
            'colors' => [
                'green',
                'yellow',
            ],
            'sizes' => [
                's',
                'xl,'
            ],
        ]);
        $validator = new Validator\CreateProduct($promoProductApi);
        $factory = new Product\Factory();

        $handler = new Handler\CreateProduct($products, $promoProductApi, $validator, $factory);
        $handler->handle([
            'id' => 'xxx',
            'name' => 'prod 1',
            'price' => '100 EUR',
            'description' => 'desc'
        ]);

        $this->assertCount(1, $products->findAll());
        $this->assertContainsOnlyInstancesOf(Product::class, $products->findAll());

        $createdProduct = $products->findAll()[0];
        $this->assertSame('prod 1', $createdProduct->name());
        $this->assertTrue($createdProduct->price()->equals(new Money(100, new Currency('EUR'))));
        $this->assertSame('desc', $createdProduct->description());
        $this->assertCount(4, $createdProduct->attributes());
    }

    public function testInvalid()
    {
        $exception = new Validator\Exception\CreateProductValidationFailed([
            'name' => 'Product name must be provided.'
        ]);
        $this->expectExceptionObject($exception);

        $products = new Products([]);
        $promoProductApi = new PromoProductApi\InMemoryClient([]);
        $validator = new Validator\CreateProduct($promoProductApi);
        $factory = new Product\Factory();

        $handler = new Handler\CreateProduct($products, $promoProductApi, $validator, $factory);
        $handler->handle([
            'id' => 'xxx',
            'price' => '100 EUR',
            'description' => 'desc'
        ]);

    }
}
