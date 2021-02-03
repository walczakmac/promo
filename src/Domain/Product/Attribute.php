<?php

namespace App\Domain\Product;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\EmbeddedDocument() */
class Attribute
{
    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $name;

    /**
     * @var string
     * @ODM\Field(type="string")
     */
    private $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    public function name() : string
    {
        return $this->name;
    }

    public function value() : string
    {
        return $this->value;
    }

    /**
     * @return string[]
     */
    public function toArray() : array
    {
        return [
            'name' => $this->name(),
            'value' => $this->value(),
        ];
    }
}
