<?php

namespace App\Tests\Infrastructure\Repository\Doctrine;

use App\Domain\Product;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;
use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Repository;

class ProductsTest extends TestCase
{
    public function testSave()
    {
        $product = new Product('shoes', new Money(100, new Currency('EUR')));
        $dm = $this->getMockBuilder(DocumentManager::class)->disableOriginalConstructor()->onlyMethods(['persist', 'flush'])->getMock();
        $dm->expects($this->once())->method('persist')->with($product);
        $dm->expects($this->once())->method('flush');
        $products = new Repository\Doctrine\Products($dm);
        $products->save($product);
    }

    public function testFindAll()
    {
        $product = new Product('shoes', new Money(100, new Currency('EUR')));
        $dm = $this->getMockBuilder(DocumentManager::class)->disableOriginalConstructor()->onlyMethods(['getRepository'])->getMock();
        $repository = $this->getMockBuilder(DocumentRepository::class)->disableOriginalConstructor()->onlyMethods(['findBy'])->getMock();

        $dm->expects($this->once())->method('getRepository')->with(Product::class)->willReturn($repository);
        $repository->expects($this->once())->method('findBy')->willReturn([$product]);
        
        $products = new Repository\Doctrine\Products($dm);
        $this->assertEquals([$product], $products->findAll());
    }
}
