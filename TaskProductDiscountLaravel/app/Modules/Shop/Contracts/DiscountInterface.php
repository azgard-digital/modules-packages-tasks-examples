<?php

namespace App\Modules\Shop\Contracts;

interface DiscountInterface
{
    /**
     * Get all discounts
     * @return array
     */
    public function getDiscounts(): array;
}
