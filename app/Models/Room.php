<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable=['name','house_id','images','created_by','updated_by','status','type'];

    public static function countActiveRoom(){
        $data=self::where('status','active')->where('type','room')->count();
        if($data){
            return $data;
        }
        return 0;
    }
    
    public static function countActiveFacility(){
        $data=self::where('status','active')->where('type','facility')->count();
        if($data){
            return $data;
        }
        return 0;
    }
}
