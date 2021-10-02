<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $fillable=['name','images_thumbnail','images_detail',
    'description','status','bathroom','bedroom','area','floor','created_by','updated_by'];
}
