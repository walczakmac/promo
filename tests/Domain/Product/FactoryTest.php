<?php

namespace App\Tests\Domain\Product;

use App\Domain\Product\Attribute;
use App\Domain\Product\Factory;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testCreateNoAttributes()
    {
        $factory = new Factory();
        $product = $factory->createFrom([
            'name' => 'shoes',
            'price' => '100 EUR',
            'description' => 'nice red shoes XL'
        ]);

        $this->assertSame('shoes', $product->name());
        $this->assertTrue($product->price()->equals(new Money(100, new Currency('EUR'))));
        $this->assertSame('nice red shoes XL', $product->description());
        $this->assertCount(0, $product->attributes());
    }

    public function testCreateWithAttributes()
    {
        $factory = new Factory();
        $product = $factory->createFrom([
            'name' => 'shoes',
            'price' => '100 EUR',
            'description' => 'nice red shoes XL',
            'promoProduct' => [
                'colors' => ['red', 'blue'],
                'sizes' => ['xl']
            ]
        ]);

        $this->assertSame('shoes', $product->name());
        $this->assertTrue($product->price()->equals(new Money(100, new Currency('EUR'))));
        $this->assertSame('nice red shoes XL', $product->description());
        $this->assertCount(3, $product->attributes());
        $this->assertContainsOnlyInstancesOf(Attribute::class, $product->attributes());
    }
}
