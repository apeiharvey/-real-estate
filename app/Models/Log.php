<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Log extends Model
{
    protected $connection = 'mongodb_aggregation';
    protected $collection = 'agregation';
    protected $guarded = [];
}
