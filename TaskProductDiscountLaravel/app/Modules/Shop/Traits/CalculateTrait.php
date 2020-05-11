<?php
namespace App\Modules\Shop\Traits;

use App\Modules\Shop\Contracts\CompositeResultInterface;

trait CalculateTrait
{
    /**
     * Maximum of available discount
     * @var int
     */
    protected $maxDiscount = 60;

    /**
     * Calculate price with discount
     * @param int $discount
     * @param int $originalPrice
     * @return int
     */
    protected function calculatePrice(int $discount, int $originalPrice): int
    {
        if ($discount > $this->maxDiscount) {
            $discount = $this->maxDiscount;
        }
        
        return $originalPrice - ($originalPrice * ($discount/100));
    }    
}