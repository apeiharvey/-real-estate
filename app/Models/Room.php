<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable=['name','house_id','images','created_by','updated_by','status','type'];

}
