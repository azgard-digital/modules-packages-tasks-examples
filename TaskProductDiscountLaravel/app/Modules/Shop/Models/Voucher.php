<?php
namespace App\Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;
    
    public $timestamps = false;
    protected $visible = ['id', 'start_date', 'end_date'];
    protected $fillable = ['id', 'start_date', 'end_date', 'discount_id'];
    protected $dates = ['deleted_at'];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function($issue){
            $issue->start_date = strtotime($issue->start_date);
            $issue->end_date = strtotime($issue->end_date);
        });
    }
}
