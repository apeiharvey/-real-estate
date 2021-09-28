<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorRating extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'tr_vendor_rating';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function order_detail (){
        return $this->belongsTo(OrderCheckoutDetail::class,'checkout_detail_id');
    }
}
