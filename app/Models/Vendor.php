<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 
// use Jenssegers\Mongodb\Eloquent\HybridRelations; // kalau mau relate dgn yg mongodb

class Vendor extends Model
{
//    use HasFactory;
    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'ms_vendor';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function documents(){
        return $this->hasMany(VendorDocument::class,'vendor_id','id');
    }

    public function region(){
        return $this->belongsTo(Region::class, 'address_region_id','region_id');
    }

    public function district(){
        return $this->belongsTo(District::class, 'address_district_id');
    }
}
