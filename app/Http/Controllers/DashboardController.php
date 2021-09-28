<?php

namespace App\Http\Controllers;

use App\Models\OrderCheckoutDetail;
use App\Models\OrderComplaint;
use App\Models\OrderComplaintDetail;
use App\Models\MessagingRooms;
use App\Models\MessagingMessages;
use App\Models\UserCart;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index(){

        $vendor_data = Vendor::where('id', auth()->user()->user_ref_id)->first();

        $this_month_sales = OrderCheckoutDetail::where('vendor_id', auth()->user()->user_ref_id)
                                                ->whereMonth('created_at', date('m'))
                                                ->whereYear('created_at', date('Y'))
                                                ->sum('total_price');
        $total_order = OrderCheckoutDetail::where('vendor_id', auth()->user()->user_ref_id)
                                            ->count();
        $total_new_order = OrderCheckoutDetail::where('vendor_id', auth()->user()->user_ref_id)
                                                ->whereIn('status',['CREATED'])
                                                ->count();
        $total_process_order = OrderCheckoutDetail::where('vendor_id', auth()->user()->user_ref_id)
                                                  ->whereNotIn('status',['CREATED'])
                                                  ->count();
        $total_complain = OrderComplaint::where('vendor_id', auth()->user()->user_ref_id)
                                          ->count();
        $total_new_complain = OrderComplaint::where('vendor_id', auth()->user()->user_ref_id)
                                            ->whereIn('complain_status',['OPEN'])
                                            ->count();
        $total_process_complain = OrderComplaint::where('vendor_id', auth()->user()->user_ref_id)
                                                ->whereNotIn('complain_status',['OPEN'])
                                                ->count();
        $total_negotiation = UserCart::whereHas('product', function($query){
                                            $query->where('vendor_id', auth()->user()->user_ref_id);
                                       })
                                     ->with('product')
                                     ->whereNotNull('negotiation_status')
                                     ->count();
        $total_new_negotiation = UserCart::whereHas('product', function($query){
                                            $query->where('vendor_id', auth()->user()->user_ref_id);
                                           })
                                         ->whereNotNull('negotiation_status')
                                         ->whereIn('negotiation_status',['STARTED'])
                                         ->count();
        $total_process_negotiation =  UserCart::whereHas('product', function($query){
                                                    $query->where('vendor_id', auth()->user()->user_ref_id);
                                                })
                                              ->whereNotNull('negotiation_status')
                                              ->whereIn('negotiation_status',['APPROVED'])
                                               ->count();
        $total_active_satdik =  Customer::where('is_enabled', true)
                                                ->count();
        $total_active_user_of_vendor =  User::where('user_type', 'VENDOR')->where('user_ref_id', auth()->user()->user_ref_id)->where('is_active', true)
                                                ->count();

        $list_order_checkout_detail = OrderCheckoutDetail::with(['order','customer','order.region','order.district'])
                                                           ->where('vendor_id', auth()->user()->user_ref_id)
                                                           ->orderBy('id', 'DESC')
                                                           ->limit(5)
                                                           ->get();

        $data = array(
            "vendor_data" => $vendor_data,
            "disk" => Storage::disk('gcs'),
            "this_month_sales" => $this_month_sales,
            "total_order" => $total_order,
            "total_new_order" => $total_new_order,
            "total_process_order" => $total_process_order,
            "total_complain" => $total_complain,
            "total_new_complain" => $total_new_complain,
            "total_process_complain" => $total_process_complain,
            "total_negotiation" => $total_negotiation,
            "total_new_negotiation" => $total_new_negotiation,
            "total_active_satdik" => $total_active_satdik,
            "total_active_user_of_vendor" => $total_active_user_of_vendor,
            "total_process_negotiation" => $total_process_negotiation,
            "list_order_checkout_detail" => $list_order_checkout_detail
        );
        return view('pages.dashboard.index',$data);

    }

    public function api_get_notif(Request $request){

        if($request->ajax()) {
            $validated = $request->validate([
                'user' => 'required',
                'vendor' => 'required',
            ]);

            $param = $request->all();
            $msg = 'membentuk notifikasi';

            // try {
                $notif = array();
                $notif['order'] = OrderCheckoutDetail::select('status', DB::raw('count(id) as count'))->groupBy('status')->get();
                $notif['complaint']['open']        = OrderComplaint::select(
                                                        DB::raw(with(new OrderComplaint)->getTable().'.*'),
                                                        DB::raw("encode(digest(".with(new OrderComplaint)->getTable().".id::text, 'sha256'), 'hex') as complain_hash"))
                                                    ->where('vendor_id', $param['vendor'])->where('is_read_by_vendor', false)->where('resolve_at',null)
                                                    ->with('customer')->with('order_detail')
                                                    ->with(['complaint_detail' => function($q){
                                                        $q->where('is_enabled', '=', true)->latest();
                                                    }])
                                                    ->take(6)->get();
                $notif['complaint']['resolved']    = OrderComplaint::select(
                                                        DB::raw(with(new OrderComplaint)->getTable().'.*'),
                                                        DB::raw("encode(digest(".with(new OrderComplaint)->getTable().".id::text, 'sha256'), 'hex') as complain_hash"))
                                                    ->where('vendor_id', $param['vendor'])->where('is_read_by_vendor', false)->where('resolve_at','!=',null)
                                                    ->with('customer')->with('order_detail')
                                                    ->with(['complaint_detail' => function($q){
                                                        $q->where('is_enabled', '=', true)->latest();
                                                    }])->take(6)->get();
                $notif['messaging']['transaction'] = MessagingRooms::where('vendor_id', auth()->user()->user_ref_id)->where('is_read_by_vendor', false)->where('ref_type',strtoupper('transaction'))
                                                    ->with('customer')->with('order_detail')->orderBy('last_message_created_at', 'DESC')->take(6)->get();
                $notif['messaging']['customer']    = MessagingRooms::where('vendor_id', auth()->user()->user_ref_id)->where('is_read_by_vendor', false)->where('ref_type','')
                                                    ->with('customer')->orderBy('last_message_created_at', 'DESC')->take(6)->get();

                $output = array('status'=>true, 'message'=>'Sukses '.$msg, 'detail'=>$notif);
            // } catch (\Exception $e) {
            //     $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
            // }

            return $output;
        }
    }
}
