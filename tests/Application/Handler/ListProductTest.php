<?php

namespace App\Tests\Application\Handler;

use App\Application\Handler\ListProducts;
use App\Domain\Product;
use App\Infrastructure\Repository;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;

class ListProductTest extends TestCase
{
    public function testSuccess()
    {
        $product = new Product('shoes', new Money(100, new Currency('EUR')), 'pretty shoes');
        $repository = new Repository\InMemory\Products([$product]);
        $handler = new ListProducts($repository);
        $products = $handler->handle([]);
        $this->assertSame([$product], $products);
    }

    /**
     * @dataProvider provideForTestPagination
     */
    public function testPagination(array $data, int $expectedLimit, int $expectedOffset)
    {
        $repository = $this->getMockBuilder(Repository\ProductRepository::class)
            ->onlyMethods(['findAll', 'save'])
            ->getMock();
        $repository->expects($this->once())->method('findAll')->with($expectedLimit, $expectedOffset);
        $handler = new ListProducts($repository);
        $handler->handle($data);
    }

    public function provideForTestPagination()
    {
        return [
            [[], 10, 0],
            [['page' => 5, 'limit' => 100], 100, 400],
            [['page' => -1, 'limit' => 10], 10, 0],
            [['page' => -5, 'limit' => -10], 10, 0],
            [['page' => 'abc', 'limit' => 'abc'], 10, 0],
        ];
    }
}
