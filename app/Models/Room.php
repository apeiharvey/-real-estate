<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

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
        $data=self::where('status','active')->where('website_key',Session::get('website_key'))->where('type','facility')->count();
        if($data){
            return $data;
        }
        return 0;
    }
}
