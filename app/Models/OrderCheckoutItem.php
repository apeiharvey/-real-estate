<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCheckoutItem extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'tr_order_checkout_item';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function order(){
        return $this->belongsTo(OrderCheckout::class, 'checkout_id');
    }

    public function order_detail(){
        return $this->belongsTo(OrderCheckoutDetail::class, 'checkout_detail_id');
    }

    public function product(){
        return $this->hasOne(Product::class, 'id','product_id');
    }

    public function user_cart(){
        return $this->hasOne(UserCart::class, 'id','cart_id');
    }

}

