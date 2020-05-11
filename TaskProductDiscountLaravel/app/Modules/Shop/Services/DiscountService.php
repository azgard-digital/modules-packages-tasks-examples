<?php

namespace App\Modules\Shop\Services;

use App\Modules\Shop\Models\Discount;
use App\Modules\Shop\Contracts\DiscountInterface;

class DiscountService implements DiscountInterface
{
    /**
     * @inheritDoc
     */
    public function getDiscounts(): array
    {
        $products = Discount::all();
        return $products->toArray();
    }
}