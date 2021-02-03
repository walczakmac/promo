<?php

namespace App\Domain\Product;

use App\Domain\Product;
use Money\Currency;
use Money\Money;

class Factory
{
    /**
     * @param array<mixed> $data
     * @return Product
     */
    public function createFrom(array $data) : Product
    {
        preg_match(Product::PRICE_REGEX, $data['price'], $matches);
        $price = new Money((int) $matches['amount'], new Currency($matches['currency']));
        $description = $data['description'] ?? null;
        $product = new Product($data['name'], $price, $description);

        if (!empty($data['promoProduct']['colors']) && is_array($data['promoProduct']['colors'])) {
            foreach($data['promoProduct']['colors'] as $color) {
                $product->addAttribute(new Attribute('color', $color));
            }
        }

        if (!empty($data['promoProduct']['sizes']) && is_array($data['promoProduct']['sizes'])) {
            foreach($data['promoProduct']['sizes'] as $size) {
                $product->addAttribute(new Attribute('size', $size));
            }
        }

        return $product;
    }
}
