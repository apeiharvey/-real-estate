<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'ms_product';
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

    public function images (){
        return $this->hasMany(ProductImage::class,'product_id','id');
    }

    public function stock(){
        return $this->hasMany(ProductStock::class,'product_id','id');
    }

    public function price(){
        return $this->hasMany(ProductPrice::class,'product_id','id');
    }

    public function category(){
        return $this->hasOne(Category::class,'category_key','category_key');
    }

}

