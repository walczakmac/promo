<?php

namespace App\Domain;

use App\Domain\Product\Attribute;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Money\Currency;
use Money\Money;

/** @ODM\Document(collection="products") */
class Product
{
    const PRICE_REGEX = '/^(?<amount>[0-9]+)\s(?<currency>[A-Z]{3})$/';

    /**
     * @var string
     * @ODM\Id
     */
    private $id;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $name;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $priceAmount;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $priceCurrency;

    /**
     * @var string|null
     * @ODM\Field(type="string", nullable=true)
     */
    private $description;

    /**
     * @var Collection<string|int, Attribute>
     * @ODM\EmbedMany()
     */
    private $attributes;

    public function __construct(string $name, Money $price, string $description = null)
    {
        $this->name = $name;
        $this->priceAmount = $price->getAmount();
        $this->priceCurrency = $price->getCurrency()->getCode();
        $this->description = $description;
        $this->attributes = new ArrayCollection();
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function price(): Money
    {
        return new Money($this->priceAmount, new Currency($this->priceCurrency));
    }

    public function description(): ?string
    {
        return $this->description;
    }

    public function addAttribute(Attribute $attribute) : void
    {
        $this->attributes->add($attribute);
    }

    /**
     * @return Collection<string|int, Attribute>
     */
    public function attributes(): Collection
    {
        return $this->attributes;
    }

    /**
     * @return array<mixed>
     */
    public function toArray() : array
    {
        return [
            'id' => $this->id(),
            'name' => $this->name(),
            'price' => [
                'priceAmount' => $this->priceAmount,
                'priceCurrency' => $this->priceCurrency,
            ],
            'description' => $this->description(),
            'attributes' => array_map(function(Attribute $attribute) {
                return $attribute->toArray();
            }, $this->attributes()->toArray()),
        ];
    }
}
