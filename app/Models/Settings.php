<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable=[
        'short_des',
        'description',
        'photo',
        'address',
        'phone',
        'email',
        'logo',
        'phone_2',
        'phone_3',
        'phone_4',
        'tiktok',
        'instagram',
        'facebook'
    ];
}
