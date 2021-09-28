<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Negotiation extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'tr_user_cart_negotiation';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function cart(){
        return $this->belongsTo(UserCart::class, 'cart_id');
    }

    public function customer(){
        return $this->hasOne(Customer::class, 'user_id','customer_id');
    }

    public function vendor(){
        return $this->hasOne(Vendor::class, 'id','vendor_id');
    }
}
