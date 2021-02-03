<?php

namespace App\Infrastructure\Repository\Doctrine;

use App\Domain\Product;
use App\Infrastructure\Repository\ProductRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class Products implements ProductRepository
{
    /**
     * @var DocumentManager
     */
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function save(Product $product) : void
    {
        $this->dm->persist($product);
        $this->dm->flush();
    }

    public function findAll(int $limit = null, int $offset = null) : array
    {
        return $this->dm->getRepository(Product::class)->findBy([], [], $limit, $offset);
    }
}
