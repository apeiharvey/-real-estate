<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    protected $fillable=['name','images_thumbnail','images_detail',
    'description','status','bathroom','bedroom','area','floor','created_by','updated_by',
    'area_surface','area_building','price'];

    public static function countActiveHouse(){
        $data=self::where('status','active')->count();
        if($data){
            return $data;
        }
        return 0;
    } 
}
