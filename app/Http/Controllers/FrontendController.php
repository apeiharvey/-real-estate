<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\UserMortage;
use App\Models\House;
use App\Models\Room;
use App\Models\Banner;
use App\Models\Testimony;

class FrontendController extends Controller
{
   
    public function index(Request $request){
        return redirect()->route($request->user()->role);
    }

    public function home(){
        $banner = Banner::select('title','photo','description','url','type')
                        ->where('status','active')
                        ->get();
        $unit_type = House::select('name','images_thumbnail','bedroom','bathroom','floor','area_building','area_surface','description','images_detail')
                            ->where('status','active')
                            ->orderBy('id','asc')
                            ->get();
        $rooms = Room::select('houses.name as house_name','rooms.name as room_name','rooms.images')
                    ->leftJoin('houses','houses.id','rooms.house_id')
                    ->where('type','room')
                    ->where('rooms.status','active')
                    ->where('houses.status','active')
                    ->orderBy('rooms.id','asc')
                    ->get();
        $facilities = Room::select('rooms.name as room_name','rooms.images')
                    ->where('type','facility')
                    ->where('rooms.status','active')
                    ->orderBy('id','asc')
                    ->get();
        $testimonies = Testimony::select('testimony_name','text','image')
                                  ->where('status','active')
                                  ->get();
        $setting = $this->setting;
        return view('frontend.index', compact(['banner', 'unit_type', 'rooms', 'facilities','testimonies', 'setting']));
    }

    public function submitMortgage(Request $request){
        $user = new UserMortage;
        $user->name = $request->user_name;
        $user->email = $request->user_email;
        $user->phone_number = $request->user_phone;
        $user->save();
        $uid = $user->id;
        return redirect()->route('simulate.mortgage',['uid' => $uid]);
    }

    public function simulateMortgage(Request $request){
        $unit_type = House::select('id','name','price')->where('status','active')->get();
        $setting = $this->setting;
        return view('frontend.simulate-mortages', compact(['unit_type', 'setting']));
    }

    public function saveUserMortgage(Request $request){
        $user_mortgage = UserMortage::find($request->uid);
        $user_mortgage->house_id = $request->house_id;
        $user_mortgage->payment = strtoupper($request->payment);
        $user_mortgage->time_period = $request->time_period;
        $user_mortgage->save();
        return $user_mortgage->id;
    }
    
}
