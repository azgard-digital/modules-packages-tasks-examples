<?php

namespace App\Modules\Shop\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Shop\Contracts\ProductInterface;

class ProductController extends Controller
{
    private $productService;

    /**
     * ProductController constructor.
     * @param ProductInterface $productService
     */
    public function __construct(ProductInterface $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Products list
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productService->getProducts();
        return response()->json($products);
    }


    /**
     * Add new product
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->productService->addProduct();
        return response()->json($result->data, $result->status);
    }

    /*
     *
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function buyProduct($id)
    {
        $result = $this->productService->buyProduct($id);
        return response()->json($result->data, $result->status);
    }
}
