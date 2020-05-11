<?php

namespace App\Modules\Shop\Contracts;

interface VoucherInterface
{
    /**
     * Add voucher
     * @return CompositeResultInterface
     */
    public function addVoucher(): CompositeResultInterface;
}
