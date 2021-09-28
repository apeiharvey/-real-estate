<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class OrderComplaint extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'tr_order_complain';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function customer(){
        return $this->belongsTo(Customer::class, 'created_by', 'user_id'); // made by customer 
    }

    public function updater(){
        return $this->belongsTo(Customer::class, 'updated_by', 'user_id');
    }

    public function vendor (){
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function order_detail (){
        return $this->belongsTo(OrderCheckoutDetail::class,'checkout_detail_id');
    }

    public function complaint_detail (){
        return $this->hasMany(OrderComplaintDetail::class,'complain_id','id');
    }
}

