<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class House extends Model
{
    protected $fillable=['name','images_thumbnail','images_detail',
    'description','status','bathroom','bedroom','area','floor','created_by','updated_by',
    'area_surface','area_building','price'];

    public static function countActiveHouse(){
        $data=self::where('status','active')->where('website_key',Session::get('website_key'))->count();
        if($data){
            return $data;
        }
        return 0;
    } 
}
