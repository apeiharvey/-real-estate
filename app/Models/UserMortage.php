<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMortage extends Model
{
    protected $fillable=['name','email','phone_number','house_id','payment','time_period'];
}
