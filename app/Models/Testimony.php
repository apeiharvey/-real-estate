<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimony extends Model
{
    protected $fillable=['testimony_name','text','image','created_by','updated_by','status'];

}
