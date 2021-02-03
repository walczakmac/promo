<?php

namespace App\Tests\Domain\Product;

use App\Domain\Product\Attribute;
use PHPUnit\Framework\TestCase;

class AttributeTest extends TestCase
{
    public function testObject()
    {
        $attribute = new Attribute('color', 'red');
        $this->assertSame('color', $attribute->name());
        $this->assertSame('red', $attribute->value());

        $this->assertSame(['name' => 'color', 'value' => 'red'], $attribute->toArray());
    }
}
