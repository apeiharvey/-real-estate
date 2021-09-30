<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLink extends Model
{
    protected $table="product_link";
    protected $fillable=['type','link','product_id'];
}
