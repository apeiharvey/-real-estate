<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'tr_order_checkout_rating';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function order_detail (){
        return $this->belongsTo(OrderCheckoutDetail::class,'checkout_detail_id');
    }
}
