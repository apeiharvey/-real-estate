<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Subdistrict;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(){

    }

    public function district($region_id){

        $result_status = "SUCCESS";
        $result_message = "Your data was loaded successfully!";
        $title = "Loaded!";

        $data = District::select('id','name as text')
            ->where('region_id',$region_id)->get();
        $data[] = array(
            'id' => '',
            'text' => 'Pilih Kecamatan'
        );

        return array(
            "data" => $data,
            "title" => $title,
            "status" => $result_status,
            "message" => $result_message
        );
    }
}
