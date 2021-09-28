<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Jenssegers\Mongodb\Eloquent\HybridRelations; // kalau mau relate dgn yg mongodb

class Customer extends Model
{

    protected $connection = 'pgsql';
    protected $table = "ms_member_profile";
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function school(){
        return $this->hasOne(School::class, 'sekolah_id', 'sekolah_id');
    }
}
