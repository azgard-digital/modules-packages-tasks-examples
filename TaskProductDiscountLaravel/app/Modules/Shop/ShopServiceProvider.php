<?php
namespace App\Modules\Shop;

use Illuminate\Support\ServiceProvider;

/**
 * Class ShopServiceProvider
 * @package App\Modules\Shop
 */
class ShopServiceProvider extends ServiceProvider
{
    protected $defer = false;

    /**
     * @inheritDoc
     */
    public function register()
    {
       $this->app->bind('App\Modules\Shop\Contracts\ProductInterface', config('shop.services.product'));
       $this->app->bind('App\Modules\Shop\Contracts\VoucherInterface', config('shop.services.voucher'));
       $this->app->bind('App\Modules\Shop\Contracts\DiscountInterface', config('shop.services.discount'));
       $this->app->bind('App\Modules\Shop\Contracts\ProductVoucherInterface', config('shop.services.product_voucher'));
       
       $this->app['router']->group(['namespace' => 'App\Modules\Shop\Controllers'], function () {
            require __DIR__.'/routes.php';
       });
    }

}