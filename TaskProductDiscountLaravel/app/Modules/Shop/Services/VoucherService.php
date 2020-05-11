<?php

namespace App\Modules\Shop\Services;

use App\Modules\Shop\Models\Voucher;
use App\Modules\Shop\Contracts\VoucherInterface;
use App\Modules\Shop\Contracts\CompositeResultInterface;

class VoucherService implements VoucherInterface
{
    use \App\Modules\Shop\Traits\ServiceTrait;

    /**
     * @inheritDoc
     */
    public function addVoucher(): CompositeResultInterface
    {
        $request = request();
        $validator = \Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'discount_id' => 'exists:discounts,id'
        ]);

        if ($validator->fails()) {
            return $this->getCompositeResult($validator->errors()->all(), 400);
        }

        try {
            $voucher = Voucher::create($request->all());

            if ($voucher) {
                return $this->getCompositeResult(['success' => true]);
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->getCompositeResult(['error' => 'Voucher cannot be added.'], 500);
    }

}