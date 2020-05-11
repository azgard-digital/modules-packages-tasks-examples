<?php
namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    public $timestamps = false;
    protected $visible = ['id', 'name', 'price'];
    protected $fillable = ['id', 'name', 'price'];
    protected $dates = ['deleted_at'];
    
    public function voucher()
    {
        return $this->belongsToMany(Voucher::class, 'products_vouchers');
    }
}
