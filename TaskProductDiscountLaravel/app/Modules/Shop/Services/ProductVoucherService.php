<?php

namespace App\Modules\Shop\Services;

use App\Modules\Shop\Models\ProductVoucher;
use App\Modules\Shop\Contracts\ProductVoucherInterface;
use App\Modules\Shop\Contracts\CompositeResultInterface;

class ProductVoucherService implements ProductVoucherInterface
{
    use \App\Modules\Shop\Traits\ServiceTrait;

    /**
     * @inheritDoc
     */
    public function addProductVoucher(): CompositeResultInterface
    {
        $request = request();

        $validator = \Validator::make($request->all(), [
            'product_id' => 'exists:products,id',
            'voucher_id' => 'exists:vouchers,id'
        ]);

        if ($validator->fails()) {
            return $this->getCompositeResult($validator->errors()->all(), 400);
        }

        try {
            ProductVoucher::create($request->all());
            return $this->getCompositeResult(['success' => true]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->getCompositeResult(['error' => 'Relation cannot be added.'], 500);
    }

    /**
     * @inheritDoc
     */
    public function removeProductVoucher(int $voucherId, int $productId): CompositeResultInterface
    {
        try {
            $conditions = [
                'voucher_id' => $voucherId,
                'product_id' => $productId
            ];

            $validator = \Validator::make($conditions, [
                'voucher_id' => 'exists:vouchers,id',
                'product_id' => 'exists:products,id'
            ]);

            if ($validator->fails()) {
                return $this->getCompositeResult($validator->errors()->all(), 400);
            }

            ProductVoucher::where($conditions)->delete();
            return $this->getCompositeResult(['success' => true]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->getCompositeResult(['error' => 'Voucher cannot be detached.'], 500);
    }

    /**
     * @inheritDoc
     */
    public function removeProductVouchers(int $voucherId): CompositeResultInterface
    {
        try {
            $conditions = [
                'voucher_id' => $voucherId,
            ];

            $validator = \Validator::make($conditions, [
                'voucher_id' => 'exists:vouchers,id'
            ]);

            if ($validator->fails()) {
                return $this->getCompositeResult($validator->errors()->all(), 400);
            }

            ProductVoucher::where($conditions)->delete();
            return $this->getCompositeResult(['success' => true]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->getCompositeResult(['error' => 'Voucher cannot be detached.'], 500);
    }
}