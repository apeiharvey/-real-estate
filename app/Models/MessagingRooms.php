<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
// MONGO ::
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;


class MessagingRooms extends Model
{
    // use HasFactory;
    // use SoftDeletes;
    use HybridRelations;

    protected $connection = 'mongodb';
    protected $collection = 'rooms';
    protected $guarded = [];
    // protected $dates = ['deleted_at'];

    public function customer(){
        return $this->belongsTo(Customer::class,'user_id','user_id'); 
    }

    public function vendor (){
        return $this->belongsTo(Vendor::class,'vendor_id','id');
    }

    public function order_detail (){
        return $this->belongsTo(OrderCheckoutDetail::class,'ref_id');
    }
}

