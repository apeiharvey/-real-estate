<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cluster extends Model
{
    protected $fillable=['name','photo','status','created_at','updated_at','created_by','updated_by'];

    public function houses(){
        return $this->hasMany('App\Models\House','cluster_id','id')->where('status','active');
    }
}
