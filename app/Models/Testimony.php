<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Session;

class Testimony extends Model
{
    protected $fillable=['testimony_name','text','image','created_by','updated_by','status'];

    public static function countActiveTestimony(){
        $data=self::where('status','active')->where('website_key',Session::get('website_key'))->count();
        if($data){
            return $data;
        }
        return 0;
    } 
}
