<?php

namespace App\Tests\Domain;

use App\Domain\Product;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testObject()
    {
        $price = new Money(100, new Currency('EUR'));
        $attributes = [
            new Product\Attribute('color', 'red'),
            new Product\Attribute('color', 'blue'),
            new Product\Attribute('size', 'm'),
            new Product\Attribute('size', 'l'),
        ];

        $product = new Product('shoes', $price, 'nice red blue shoes');
        foreach($attributes as $attribute) {
            $product->addAttribute($attribute);
        }

        $this->assertSame('shoes', $product->name());
        $this->assertTrue($price->equals($product->price()));
        $this->assertSame('nice red blue shoes', $product->description());
        foreach($attributes as $attribute) {
            $this->assertContains($attribute, $product->attributes());
        }

        $this->assertSame([
            'id' => null,
            'name' => 'shoes',
            'price' => [
                'priceAmount' => '100',
                'priceCurrency' => 'EUR',
            ],
            'description' => 'nice red blue shoes',
            'attributes' => [
                ['name' => 'color', 'value' => 'red'],
                ['name' => 'color', 'value' => 'blue'],
                ['name' => 'size', 'value' => 'm'],
                ['name' => 'size', 'value' => 'l'],
            ],
        ], $product->toArray());
    }
}
