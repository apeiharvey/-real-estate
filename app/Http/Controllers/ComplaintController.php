<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Models\OrderComplaint;
use App\Models\OrderComplaintDetail;
use App\Models\OrderCheckoutDetail;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\School;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use DB;
use App\Helpers\BroadcastHelper;
use App\Helpers\MailHelper;

class ComplaintController extends Controller
{
    public function __construct()
	{
		parent::__construct();
    }

    public function index(){
        // dd(auth()->user()->user_ref_id, auth()->user()->id);
        return view('pages/complaint/index',$this->data);
    }

    public function api_get_list(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();
            $data = array();

            if(array_key_exists('status',$param) && !empty($param['status'])){
                // DB::enableQueryLog();
                $data = OrderComplaint::select(
                            DB::raw(with(new OrderComplaint)->getTable().'.*'),
                            DB::raw(with(new OrderComplaint)->getTable().".id as complain_hash"))
                        ->with('customer')
                        ->with('order_detail')
                        ->with(['complaint_detail' => function($q){
                            $q->where('is_enabled', '=', true)->latest();
                        }])
                        ->where('vendor_id', '=', auth()->user()->user_ref_id)->where('is_enabled',true)
                        ->whereIn('complain_status', $param['status']);

                if(array_key_exists('search',$param) && $param['search'] != null){
                    $data = $data->whereHas('customer', function($q) use ($param){
                                $q->where(DB::raw('LOWER('.with(new Customer)->getTable().'.name)'),'LIKE','%'.strtolower($param['search']).'%');
                            })
                            ->orWhereHas('order_detail', function($q) use ($param){
                                $q->where(DB::raw('LOWER('.with(new OrderCheckoutDetail)->getTable().'.order_no)'),'LIKE','%'.strtolower($param['search']).'%');
                            });
                }
                $data = $data->orderBy('updated_at', 'DESC')->orderBy('created_at', 'DESC')->get();
                if($data){
                    $data->makeHidden(['id']);
                }
                // dd(DB::getQueryLog());
                // dump($data);

            }else{
                return array('status'=>false,'detail'=>'permintaan tidak valid, coba lagi');
            }

            return array('status'=>true,'detail'=>$data);
        }
    }

    public function api_get_list_message(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();
            $data = array();

            // DB::enableQueryLog();
            $data['parent'] =   OrderComplaint::select(
                                DB::raw(with(new OrderComplaint)->getTable().'.*'),
                                DB::raw(with(new OrderComplaint)->getTable().".id as complain_hash"));
            if(array_key_exists('order_detail_hash',$param) && $param['order_detail_hash']){
                $data['parent'] =   $data['parent']->where(DB::raw(with(new OrderComplaint)->getTable().".checkout_detail_id"), $param['order_detail_hash']);
            }else{
                $data['parent'] =   $data['parent']->where(DB::raw(with(new OrderComplaint)->getTable().".id"), $param['complain_hash']);
            }
            $data['parent'] =   $data['parent']->with(['customer' => function($q){
                                                $q->first();
                                            },'customer.school'])
                                            ->with(['vendor' => function($q){
                                                $q->first();
                                            }])
                                            ->latest()->first();


            if($data['parent']){
                $data['parent']->makeHidden(['id']);

                $data['child'] = OrderComplaintDetail::select(
                        DB::raw(with(new OrderComplaintDetail)->getTable().'.*'),
                        DB::raw(with(new OrderComplaintDetail)->getTable().".id as complain_detail_hash"));
                if(array_key_exists('order_detail_hash',$param) && $param['order_detail_hash']){
                    $data['child'] =   $data['child']->where(DB::raw(with(new OrderComplaintDetail)->getTable().".checkout_detail_id"), $param['order_detail_hash']);
                }else{
                    $data['child'] =   $data['child']->where(DB::raw(with(new OrderComplaintDetail)->getTable().".complain_id"), $param['complain_hash']);
                }
                $data['child'] =   $data['child']->where('is_enabled',true)->orderBy('created_at', 'ASC')->get();

                $data['child']->makeHidden(['id','complain_id']);
            }else{
                return array('status'=>false,'message'=>'Komplain yang dipilih '.(@$param['message']).' tidak valid');
            }
            // dd(DB::getQueryLog());

            return array('status'=>true,'detail'=>$data);
        }
    }

    public function api_store_message(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();
            $msg = 'menambahkan pesan baru';
            // dd($param);

            try {
                // DB::enableQueryLog();
                // date_default_timezone_set('Asia/Jakarta');

                $filename_original  = null;
                $image_status = false;
                $template_name = 'SELLER_REPLY_COMPLAIN';
                if(array_key_exists('image',$param)){
                    $disk = Storage::disk('gcs');
                    $filename_unique    = 'complain/'.md5(date("Ym")).'/'.sha1(time()).'-'.auth()->user()->user_ref_id;
                    $filename_original  = $filename_unique.'.'.($param['image']->getClientOriginalExtension());
                    $image_status = $disk->put($filename_original,File::get($param['image']));
                    $template_name = 'SELLER_REPLY_COMPLAIN__IMAGE';
                }

                if(array_key_exists('image',$param) && !$image_status){
                    $output = array('status'=>false, 'message'=>'Foto gagal diupload');
                }else{

                    if(array_key_exists('order_detail_hash',$param) && $param['order_detail_hash']){
                        $orderComplaintModel = OrderComplaint::where(DB::raw(with(new OrderComplaint)->getTable().".checkout_detail_id"), $param['order_detail_hash'])->first();
                    }else{
                        $orderComplaintModel = OrderComplaint::where(DB::raw(with(new OrderComplaint)->getTable().".id"),$param['complain_hash'])->first();
                    }

                    if($orderComplaintModel){

                        // to database
                        \DB::beginTransaction();
                        $orderComplaintDetailModel = new OrderComplaintDetail;
                        $orderComplaintDetailModel->from_vendor =  auth()->user()->user_ref_id;
                        $orderComplaintDetailModel->created_by =  auth()->user()->id;
                        $orderComplaintDetailModel->created_at =  date('Y-m-d H:i:s');
                        $orderComplaintDetailModel->is_enabled =  true;
                        $orderComplaintDetailModel->read =  false;
                        $orderComplaintDetailModel->complain_id = $orderComplaintModel->id;
                        $orderComplaintDetailModel->checkout_detail_id = $orderComplaintModel->checkout_detail_id;
                        $orderComplaintDetailModel->complain_message = @$param['message'];
                        $orderComplaintDetailModel->image =  $filename_original;
                        $orderComplaintDetailModel->save();
                        OrderComplaint::where('id','=',$orderComplaintModel->id)->update(['is_read_by_member'=>false]);

                        $orderCheckoutDetailModel = @OrderCheckoutDetail::where('id','=',$orderComplaintModel->checkout_detail_id)->first();
                        $customerModel =  @Customer::where('user_id','=',$orderComplaintModel->created_by)->first();

                        // broadcast::AGREGASI
                        $payload_agregation = array(
                            "agregation_name"=>"ComplaintFollowedUp",
                            "user_id"=>$orderComplaintModel->created_by,
                            "school_id"=>@$orderCheckoutDetailModel->school_id,
                            "event_desc"=>"Complaint Followed Up by Vendor",
                            "transaction_id"=>$orderComplaintModel->checkout_detail_id,
                            "data"=>json_encode(
                                array(
                                    "complaint"=>(string)$orderComplaintModel->id,
                                    "followedupBy"=>array(
                                        "merchant"=>array(
                                            "user"=>(string)auth()->user()->id,
                                            "merchant"=>(string)$orderComplaintModel->vendor_id
                                        )
                                    ),
                                    "context"=>$filename_original.' '.@$param['message'],
                                    "occurredAt"=>$orderComplaintDetailModel->created_at,
                                    "sentAt"=>$orderComplaintDetailModel->created_at
                                )
                            )
                        );
                        BroadcastHelper::send($request,$payload_agregation,'',$orderComplaintDetailModel->created_at);

                        // broadcast::MAIL
                        $template = EmailTemplate::where('name',$template_name)->first();
                        $payload_email = array(
                            "template_name" => $template_name,
                            "email_to" => @$customerModel->email,
                            // "email_to" => "elinyine@gmail.com", // test only
                            "email_atribute" => array(
                                array('name' => 'order_no', 'value' => @$orderCheckoutDetailModel->order_no),
                                array('name' => 'vendor_name', 'value' => auth()->user()->name),
                                array('name' => 'buyer_name', 'value' => @$customerModel->name),
                                array('name' => 'message', 'value' => @$param['message']),
                                array('name' => 'image', 'value' => $this->data['url']['disk']->url($filename_original)),
                                array('name' => 'link', 'value' => env('FE_APP_URL').'/user/orders/detail/'.@$orderCheckoutDetailModel->order_code),
                            )
                        );
                        MailHelper::send($payload_email);

                        \DB::commit();
                        $output = array('status'=>true, 'message'=>'Sukses '.$msg);

                    }else{
                        $output = array('status'=>false, 'message'=>'Komplain tidak ditemukan');
                    }

                }

                // dd(DB::getQueryLog());
            } catch (\Exception $e) {
                $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
                \DB::rollback();
            }

            return $output;
        }
    }

    public function api_set_action(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();
            $msg = 'melakukan aksi';
            $date_now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');
            $date_now__after_pause = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->addMinutes(1), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');
            // dd($param);

            try {
                // DB::enableQueryLog();
                // date_default_timezone_set('Asia/Jakarta');
                $template_name = 'SELLER_RESOLVE_COMPLAIN';
                $template_name2 = 'ORDER_RETURNED';

                if(!array_key_exists('complain_hash',$param) || !array_key_exists('status',$param)){
                    $output = array('status'=>false, 'message'=>'Parameter tidak lengkap');
                }else{
                    \DB::beginTransaction();
                    $action_confirmed = 0;
                    if($param['status'] == 'close_continue' || $param['status'] == 'close_return'){
                        $orderComplaintModel_action =  OrderComplaint::where(DB::raw(with(new OrderComplaint)->getTable().".id"),$param['complain_hash'])->update([
                            'complain_status'=>'RESOLVED',
                            'resolve_by_type'=>'VENDOR',
                            'resolve_by'=>auth()->user()->user_ref_id,
                            'resolve_at'=>$date_now
                        ]);
                        $action_confirmed = 1;

                        $orderComplaintModel = OrderComplaint::where(DB::raw(with(new OrderComplaint)->getTable().".id"),$param['complain_hash'])->first();
                        $orderCheckoutDetailModel = @OrderCheckoutDetail::where('id','=',$orderComplaintModel->checkout_detail_id)->first();
                        $customerModel =  @Customer::where('user_id','=',$orderComplaintModel->created_by)->first();
                        $schoolModel =  @School::where('sekolah_id','=',$orderCheckoutDetailModel->school_id)->first();

                        // broadcast::AGREGASI [1]
                        $payload_agregation = array(
                            "agregation_name"=>"ComplaintResolved",
                            "user_id"=>$orderComplaintModel->created_by,
                            "school_id"=>$orderCheckoutDetailModel->school_id,
                            "event_desc"=>"Complaint Resolved by Vendor",
                            "transaction_id"=>$orderComplaintModel->checkout_detail_id,
                            "data"=>json_encode(
                                array(
                                    "complaint"=>(string)$orderComplaintModel->id,
                                    "resolvedBy"=>array(
                                        "merchant"=>array(
                                            "user"=>(string)auth()->user()->id,
                                            "merchant"=>(string)$orderComplaintModel->vendor_id
                                        )
                                    ),
                                    "context"=>"Komplain diselesaikan.",
                                    "occurredAt"=>$date_now,
                                    "sentAt"=>$date_now
                                )
                            )
                        );
                        BroadcastHelper::send($request,$payload_agregation,'',$date_now);
                        // broadcast::MAIL [1]
                        // $template = EmailTemplate::where('name',$template_name)->first();
                        $payload_email = array(
                            "template_name" => $template_name,
                            "email_to" => @$customerModel->email,
                            // "email_to" => "elinyine@gmail.com", // test only
                            "email_atribute" => array(
                                array('name' => 'order_no', 'value' => @$orderCheckoutDetailModel->order_no),
                                array('name' => 'vendor_name', 'value' => auth()->user()->name),
                                array('name' => 'buyer_name', 'value' => @$customerModel->name),
                                array('name' => 'school_name', 'value' => @$schoolModel->school_name),
                                array('name' => 'link', 'value' => env('FE_APP_URL').'/user/orders/detail/'.@$orderCheckoutDetailModel->order_code),
                            )
                        );
                        MailHelper::send($payload_email);

                        if($param['status'] == 'close_return'){ // agregasi tambahan u/ yg close_return
                            $close_return_context = "Pesanan transaksi ".$orderComplaintModel->checkout_detail_id." ditolak rampung dan dikembalikan".(@$param['reason']?" karena '".$param['reason']."'":"");
                            OrderCheckoutDetail::where('id','=',$orderComplaintModel->checkout_detail_id)->update([
                                'status'=>"CANCELED",
                                'status_note'=>$close_return_context
                            ]);
                            // broadcast::AGREGASI [2/opt]
                            $payload_agregation2 = array(
                                "agregation_name"=>"OrderReturned",
                                "user_id"=>$orderComplaintModel->created_by,
                                "school_id"=>$orderCheckoutDetailModel->school_id,
                                "event_desc"=>"Appeal to Order Returned via Vendor Complain",
                                "transaction_id"=>$orderComplaintModel->checkout_detail_id,
                                "data"=>json_encode(
                                    array(
                                        "returnedBy"=>array(
                                            "user"=>(string)@$customerModel->pengguna_id,
                                            "school"=>(string)@$orderCheckoutDetailModel->school_id,
                                            "role"=>(string)@$customerModel->peran_id,
                                            "title"=>@$customerModel->jabatan,
                                        ),
                                        "context"=>$close_return_context,
                                        "occurredAt"=>$date_now__after_pause,
                                        "sentAt"=>$date_now__after_pause
                                    )
                                )
                            );
                            BroadcastHelper::send($request,$payload_agregation2);
                            // broadcast::MAIL [2/opt]
                            // $template2 = EmailTemplate::where('name',$template_name2)->first();
                            $payload_email2 = array(
                                "template_name" => $template_name2,
                                "email_to" => @$customerModel->email,
                                // "email_to" => "elinyine@gmail.com", // test only
                                "email_atribute" => array(
                                    array('name' => 'order_no', 'value' => @$orderCheckoutDetailModel->order_no),
                                    array('name' => 'vendor_name', 'value' => auth()->user()->name),
                                    array('name' => 'buyer_name', 'value' => @$customerModel->name),
                                    array('name' => 'school_name', 'value' => @$schoolModel->school_name),
                                    array('name' => 'link', 'value' => env('FE_APP_URL').'/user/orders/detail/'.@$orderCheckoutDetailModel->order_code),
                                )
                            );
                            MailHelper::send($payload_email2);
                        }


                    }else if($param['status'] == 'request_admin_help'){
                        $orderComplaintModel_action =  OrderComplaint::where(DB::raw(with(new OrderComplaint)->getTable().".id"),$param['complain_hash'])->update([
                            'request_admin_help'=>true,
                            'request_admin_help_by_type'=>'VENDOR',
                            'request_admin_help_by'=>auth()->user()->user_ref_id,
                            'request_admin_help_at'=>$date_now
                        ]);
                        $action_confirmed = 1;
                    }

                    $output = ($action_confirmed ? array('status'=>true, 'message'=>'Sukses '.$msg) : array('status'=>false, 'message'=>'Parameter tidak sesuai'));
                    \DB::commit();
                }

                // dd(DB::getQueryLog());
            } catch (\Exception $e) {
                $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
                \DB::rollback();
            }

            return $output;
        }
    }

    public function api_set_read(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();
            $msg = "mengatur 'sudah dibaca'";

            try {
                // DB::enableQueryLog();
                // date_default_timezone_set('Asia/Jakarta');
                if(!array_key_exists('complain_hash',$param)){
                    $output = array('status'=>false, 'message'=>'Parameter tidak lengkap');
                }else{
                    \DB::beginTransaction();
                    $orderComplaintModel_set =  OrderComplaint::where(DB::raw(with(new OrderComplaint)->getTable().".id"),$param['complain_hash'])->update([
                        'is_read_by_vendor'=>'true'
                    ]);
                    $output = ($orderComplaintModel_set ? array('status'=>true, 'message'=>'Sukses '.$msg) : array('status'=>false, 'message'=>'Parameter tidak sesuai'));
                    \DB::commit();
                }
                // dd(DB::getQueryLog());
            } catch (\Exception $e) {
                $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
                \DB::rollback();
            }

            return $output;
        }
    }

    public function download(Request $request, $hash){

        $this->data['parent'] = OrderComplaint::select(
                DB::raw(with(new OrderComplaint)->getTable().'.*'),
                DB::raw(with(new OrderComplaint)->getTable().".id as complain_hash"))
                ->where(DB::raw(with(new OrderComplaint)->getTable().".id"), $hash)
                ->with(['customer' => function($q){
                    $q->first();
                }])
                ->with(['vendor' => function($q){
                    $q->first();
                }])
                ->latest()->first();

        if($this->data['parent']){
            $this->data['order_detail'] = OrderCheckoutDetail::where('id','=',$this->data['parent']->checkout_detail_id)->first();

            $this->data['child'] = OrderComplaintDetail::select(
                    DB::raw(with(new OrderComplaintDetail)->getTable().'.*'),
                    DB::raw(with(new OrderComplaintDetail)->getTable().".id as complain_detail_hash"))
                    ->where(DB::raw(with(new OrderComplaintDetail)->getTable().".complain_id"), $hash)
                    ->where('is_enabled',true)
                    ->orderBy('created_at', 'ASC')
                    ->get();


            $this->data['disk'] = Storage::disk('gcs');

            return view('pages/complaint/pdf',$this->data);
        }else{
            return 'komplain tidak ditemukan';
        }
    }

    public function print(Request $request, $hash){
        $explode_hash = explode("-",$hash);
        if(count($explode_hash)===2){
            $private_key = $explode_hash[0];
            $public_key = $explode_hash[1];
            $public_key_base64_decode = base64_decode($public_key);
            $public_key_value_explode = explode(",",$public_key_base64_decode);
            if(count($explode_hash)===2) {
                $complain_id = $public_key_value_explode[0];
                $timestamp = $public_key_value_explode[1];
                if($private_key===hash('sha256',$complain_id.$timestamp.env("PRINT_PRIVATE_KEY"))){
                    $interval  = abs(strtotime("now") - strtotime($timestamp));
                    $minutes   = round($interval / 60);
                    if($minutes<=env("PRINT_MINUTES_EXPIRED")){
                        $complain = OrderComplaint::where('id',$complain_id)->first();
                        $hash_complain_id = hash('sha256',$complain_id);
                        if($complain){
                            return $this->download($request, $hash_complain_id);
                        }else{
                            return "Content not found";
                        }
                    }else{
                        return "URL has Expired";
                    }
                }else{
                    return "Wrong Private Key";
                }
            }else{
                return "Wrong Public Key";
            }
        }else{
            return "Wrong URL";
        }
    }
}

// date : https://stackoverflow.com/questions/30668373/moment-js-test-if-a-date-is-today-yesterday-within-a-week-or-two-weeks-ago
