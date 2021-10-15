<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable=[
        'photo',
        'address',
        'phone',
        'email',
        'logo',
        'tiktok',
        'instagram',
        'facebook',
        'twitter',
        'long',
        'lat',
        'mobile_phone',
        'twitter_name',
        'facebook_name',
        'instagram_name',
        'brochure',
        'maps2'
    ];
}
