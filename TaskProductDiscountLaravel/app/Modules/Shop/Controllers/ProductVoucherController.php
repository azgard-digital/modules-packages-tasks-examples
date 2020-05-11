<?php

namespace App\Modules\Shop\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Shop\Contracts\ProductVoucherInterface;

class ProductVoucherController extends Controller
{
    private $productVoucherService;

    /**
     * ProductVoucherController constructor.
     * @param ProductVoucherInterface $productVoucherService
     */
    public function __construct(ProductVoucherInterface $productVoucherService)
    {
        $this->productVoucherService = $productVoucherService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->productVoucherService->addProductVoucher();
        return response()->json($result->data, $result->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $voucherId
     * @return \Illuminate\Http\Response
     */
    public function voucherDestroy($voucherId)
    {
        $result = $this->productVoucherService->removeProductVouchers($voucherId);
        return response()->json($result->data, $result->status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $voucherId
     * @param int $productId
     * @return \Illuminate\Http\Response
     */
    public function voucherProductDestroy($voucherId, $productId)
    {
        $result = $this->productVoucherService->removeProductVoucher($voucherId, $productId);
        return response()->json($result->data, $result->status);
    }
}
