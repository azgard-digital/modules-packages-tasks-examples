<?php

namespace App\Modules\Shop\Contracts;

interface ProductInterface
{
    /**
     * Get products
     * @return array
     */
    public function getProducts(): array;

    /**
     * Add product
     * @return CompositeResultInterface
     */
    public function addProduct(): CompositeResultInterface;

    /**
     * Buy product
     * @param int $id
     * @return CompositeResultInterface
     */
    public function buyProduct(int $id): CompositeResultInterface;
}
