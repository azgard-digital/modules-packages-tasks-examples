<?php

namespace App\Modules\Shop\Services;

use App\Modules\Shop\Models\Product;
use App\Modules\Shop\Contracts\ProductInterface;
use App\Modules\Shop\Contracts\CompositeResultInterface;
use Carbon\Carbon;

class ProductService implements ProductInterface
{
    use \App\Modules\Shop\Traits\ServiceTrait;
    use \App\Modules\Shop\Traits\CalculateTrait;

    /**
     * @inheritDoc
     */
    public function getProducts(): array
    {
        $products = [];

        try {
            $expiresAt = Carbon::now()->timestamp;

            $products = Product::with(['voucher' => function ($query) use ($expiresAt) {
                return $query->where('start_date', '<=', $expiresAt)
                    ->where('end_date', '>', $expiresAt)
                    ->join('discounts as d', 'd.id', '=', 'vouchers.discount_id')
                    ->select('d.discount');
            }])->get(['id', 'name', 'price']);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->formatProductsArray($products);
    }

    /**
     * @param array $products
     * @return array
     */
    protected function formatProductsArray(array $products): array
    {
        $data = [];

        foreach ($products as $product) {
            $attributes = $product->getAttributes();

            if (count($product->voucher)) {
                $discount = $product->voucher->sum('discount');
                $attributes['discount'] = ($discount > $this->maxDiscount) ? $this->maxDiscount : $discount;
                $attributes['discount_price'] = $this->calculatePrice($attributes['discount'], $attributes['price']);
            }

            $data[] = $attributes;
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function addProduct(): CompositeResultInterface
    {
        $request = request();
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->getCompositeResult($validator->errors()->all(), 400);
        }

        try {
            $product = Product::create($request->all());

            if ($product) {
                return $this->getCompositeResult(['success' => true]);
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->getCompositeResult(['error' => 'Product cannot be added.'], 500);
    }

    /**
     * @inheritDoc
     */
    public function buyProduct(int $id): CompositeResultInterface
    {
        try {
            $product = Product::find($id);

            if (!$product) {
                return $this->getCompositeResult(['error' => 'bad request.'], 401);
            }

            if ($product->delete()) {
                $expiresAt = Carbon::now()->toDateTimeString();

                \DB::table('vouchers as v')
                    ->join('products_vouchers as pv', 'v.id', '=', 'pv.voucher_id')
                    ->where('pv.product_id', $id)
                    ->update(['deleted_at' => $expiresAt]);

                return $this->getCompositeResult(['success' => true]);
            }

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }

        return $this->getCompositeResult(['error' => 'Product has not been bought.'], 500);
    }
}