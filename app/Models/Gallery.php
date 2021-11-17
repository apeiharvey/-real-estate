<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable=['website_key','title','photo','description','created_by','updated_by','status'];

}
