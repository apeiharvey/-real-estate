<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\HybridRelations;

class OrderCheckoutDetail extends Model
{
//    use HasFactory;
//    use SoftDeletes;
    use HybridRelations;
    protected $connection = 'pgsql';
    protected $table = 'tr_order_checkout_detail';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    public function creator(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(){
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function order(){
        return $this->belongsTo(OrderCheckout::class, 'checkout_id');
    }

    public function order_item (){
        return $this->hasMany(OrderCheckoutItem::class,'checkout_detail_id','id');
    }

    public function customer (){
        return $this->hasOne(Customer::class,'user_id','created_by');
    }

    public function vendor (){
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function shipment_service(){
        return $this->hasOne(LogisticService::class,'id','shipment_services_id');
    }

    public function order_delivery(){
        return $this->hasOne(OrderDelivery::class, 'order_detail_id', 'id');
    }

    public function bast(){
        return $this->hasMany(OrderBast::class,'checkout_detail_id','id');
    }

    public function invoice(){
        return $this->hasOne(Invoice::class,'checkout_detail_id','id');
    }

    public function receive(){
        return $this->hasMany(OrderReceive::class,'checkout_detail_id','id');
    }

    public function messaging_rooms(){
        return $this->hasOne(MessagingRooms::class,'ref_id','id');
    }

    public function complaint(){
        return $this->hasOne(OrderComplaint::class, 'checkout_detail_id','id');
    }

    public function invoice_payment(){
        return $this->hasOne(InvoicePayment::class, 'checkout_detail_id','id');
    }

    public function school(){
        return $this->hasOne(School::class,'sekolah_id','school_id');
    }

    public function update_order(){
        return $this->hasOne(OrderUpdate::class,'checkout_detail_id','id');
    }
}

