<?php

namespace App\Modules\Shop\Contracts;

interface ProductVoucherInterface
{
    /**
     * Add voucher
     * @return CompositeResultInterface
     */
    public function addProductVoucher(): CompositeResultInterface;

    /**
     * Delete voucher
     * @param int $voucherId
     * @param int $productId
     * @return CompositeResultInterface
     */
    public function removeProductVoucher(int $voucherId, int $productId): CompositeResultInterface;

    /**
     * Delete voucher
     * @param int $voucherId
     * @return CompositeResultInterface
     */
    public function removeProductVouchers(int $voucherId): CompositeResultInterface;
}
