<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class OrderBastDetail extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'tr_order_bast_detail';
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

    public function product(){
        return $this->hasOne(Product::class, 'id','product_id');
    }
}

