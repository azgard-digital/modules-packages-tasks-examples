<?php

namespace App\Modules\Shop\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Shop\Contracts\DiscountInterface;

class DiscountController extends Controller
{
    private $discountService;

    public function __construct(DiscountInterface $discountService)
    {
        $this->discountService = $discountService;
    }

    /**
     * Discounts list
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = $this->discountService->getDiscounts();
        return response()->json($discounts);
    }

}
