<?php

namespace App\Modules\Shop\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Shop\Contracts\VoucherInterface;

class VoucherController extends Controller
{
    private $voucherService;

    public function __construct(VoucherInterface $voucherService)
    {
        $this->voucherService = $voucherService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->voucherService->addVoucher();
        return response()->json($result->data, $result->status);
    }
}
