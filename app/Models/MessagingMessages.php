<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
// MONGO ::
use Jenssegers\Mongodb\Eloquent\Model;
// use Jenssegers\Mongodb\Eloquent\HybridRelations;


class MessagingMessages extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    // use HybridRelations;

    protected $connection = 'mongodb';
    protected $collection = 'messages';
    protected $guarded = [];
    // protected $dates = ['deleted_at'];

    public function customer(){
        return $this->belongsTo(Customer::class, 'user_id'); 
    }

    public function vendor (){
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function rooms (){
        return $this->belongsTo(MessagingRooms::class,'room', 'room_id');
    }
}

