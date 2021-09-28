<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class UserCart extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'tr_user_cart';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function creator(){
        return $this->belongsTo(Customer::class, 'created_by');
    }

    public function updater(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function customer (){
        return $this->hasOne(Customer::class,'user_id','user_id');
    }

    public function negotiation (){
        return $this->hasMany(Negotiation::class,'cart_id','id');
    }

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

//    public function school(){
//        return $this->hasOne(ProductStock::class,'product_id','id');
//    }

//    public function zone(){
//        return $this->hasMany(ProductPrice::class,'product_id','id');
//    }

}

