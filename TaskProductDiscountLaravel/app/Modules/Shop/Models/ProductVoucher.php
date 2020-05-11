<?php
namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVoucher extends Model
{
    protected $table = 'products_vouchers';
    public $timestamps = false;
    protected $fillable = ['product_id', 'voucher_id'];
}
