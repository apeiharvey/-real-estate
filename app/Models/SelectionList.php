<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SelectionList extends Model
{
//    use HasFactory;
    use SoftDeletes;
    protected $connection = 'pgsql';
    protected $table = 'ms_selection_list';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

//    public function creator(){
//        return $this->belongsTo(AdminModel::class, 'created_by');
//    }
//
//    public function updater(){
//        return $this->belongsTo(AdminModel::class, 'updated_by');
//    }
}
