<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\House;
use App\Models\Room;
use App\Models\Testimony;
use App\Models\UserMortage;
use App\User;
use App\Rules\MatchOldPassword;
use Hash;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use Session;

class AdminController extends Controller
{
    public function index(){
        $data = array();
        $data['property'] = House::countActiveHouse();
        $data['promo'] = Room::countActiveFacility();
        $data['website_key'] = $this->website_key;
        $website_key = Session::get('website_key');

        $query = UserMortage::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
        ->where('created_at', '>', Carbon::today()->subDay(6))
        ->where('website_key',$website_key)
        ->groupBy('day_name','day')
        ->orderBy('day')
        ->get();
        $array[] = ['Name', 'Number'];
        foreach($query as $key => $value)
        {
            $array[++$key] = [$value->day_name, $value->count];
        }
        $data['users'] = json_encode($array);
        //  return $data;
        return view('backend.index',$data);
    }

    public function profile(){
        $profile=Auth()->user();
        // return $profile;
        return view('backend.users.profile')->with('profile',$profile);
    }

    public function profileUpdate(Request $request,$id){
        // return $request->all();
        $user=User::findOrFail($id);
        $data=$request->all();
        $status=$user->fill($data)->save();
        if($status){
            request()->session()->flash('success','Successfully updated your profile');
        }
        else{
            request()->session()->flash('error','Please try again!');
        }
        return redirect()->back();
    }

    public function settings(){
        $data['website_key'] = $this->website_key;
        $data['data'] = Settings::where('website_key',Session::get('website_key'))->first();
        return view('backend.setting',$data);
    }

    public function settingsUpdate(Request $request){
        // return $request->all();
        $this->validate($request,[
            'logo'=>'required',
            'photo'=>'required',
            'mobile_phone'=>'required|string',
            'photo'=>'required|string',
            'maps2'=>'required|string',
            'promotion_title'
        ]);
        $data=$request->all();
        $website_key = Session::get('website_key');
        // return $data;
        $settings=Settings::where('website_key',$website_key)->first();
        // return $settings;
        $status=$settings->fill($data)->save();
        if($status){
            request()->session()->flash('success','Setting successfully updated');
        }
        else{
            request()->session()->flash('error','Please try again');
        }
        return redirect()->route('admin');
    }

    public function changePassword(){
        return view('backend.layouts.changePassword');
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);
        
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
   
        return redirect()->route('admin')->with('success','Password successfully changed');
    }

    // Pie chart
    public function userPieChart(Request $request){
        // dd($request->all());
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
        ->where('created_at', '>', Carbon::today()->subDay(6))
        ->groupBy('day_name','day')
        ->orderBy('day')
        ->get();
     $array[] = ['Name', 'Number'];
     foreach($data as $key => $value)
     {
       $array[++$key] = [$value->day_name, $value->count];
     }
    //  return $data;
     return view('backend.index')->with('course', json_encode($array));
    }

    // public function activity(){
    //     return Activity::all();
    //     $activity= Activity::all();
    //     return view('backend.layouts.activity')->with('activities',$activity);
    // }
}
