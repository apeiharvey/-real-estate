<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class OrderCheckout extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'tr_order_checkout';
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

    public function order_detail (){
        return $this->hasMany(OrderCheckoutDetail::class,'checkout_id','id');
    }

    public function order_item (){
        return $this->hasMany(OrderCheckoutItem::class,'checkout_id','id');
    }
    
    public function region (){
        return $this->hasOne(Region::class,'region_id','delivery_region_id');
    }

    public function district (){
        return $this->hasOne(District::class,'id','delivery_district_id');
    }
}

