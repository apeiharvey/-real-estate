<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use App\Models\Settings;
use App\Models\WebsiteKey;
use Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(){
        $this->website_key = WebsiteKey::get();
        if(Session::get('website_key') == null){
            Session::put('website_key',$this->website_key[0]->website_key);
        }
        $this->setting = Settings::first();
    }
}
