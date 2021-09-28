<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use App\Models\MessagingRooms;
use App\Models\MessagingMessages;
use App\Models\OrderCheckoutDetail;
use App\Models\Customer;
use App\Models\Vendor;
use App\Models\School;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use DB;
use App\Helpers\BroadcastHelper;
use App\Helpers\MailHelper;


class MessagingController extends Controller
{
    public function __construct()
	{
		parent::__construct();
    }

    public function index(){
        // phpinfo(); // check mongo drivernya bisa disini // https://stackoverflow.com/a/36022781 // utk setup mongodb d windows
        // dump('show you the mongodb::'.with(new MessagingRooms)->getTable());
        // $room = MessagingRooms::where('ref_type', strtoupper('transaction'))->where('ref_id',248)->get();
        // $messages = MessagingMessages::where('room',$room[0]->_id)->get();
        // dump($room,$messages);
        // die();
        return view('pages/messaging/index',$this->data);
    }

    public function api_get_list(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();
            $data = array();
            DB::enableQueryLog();

            if(array_key_exists('type',$param) && !empty($param['type'])){
                if($param['type'] == 'transaction'){

                    $data = OrderCheckoutDetail::select(
                                DB::raw(with(new OrderCheckoutDetail)->getTable().'.*'),
                                DB::raw(with(new OrderCheckoutDetail)->getTable().".id as order_detail_hash"))
                            ->with('customer')
                            ->with(['messaging_rooms' => function($q){
                                $q->where('ref_type', strtoupper('transaction'));
                            }])
                            ->where('vendor_id', '=', auth()->user()->user_ref_id);
                            // ->whereNotIn('status', $order_final_status); // hanya untuk on going

                    if(array_key_exists('search',$param) && $param['search'] != null){ // search by customer & order
                        $data = $data->whereHas('customer', function($q) use ($param){
                                    $q->where(DB::raw('LOWER('.with(new Customer)->getTable().'.name)'),'LIKE','%'.strtolower($param['search']).'%');
                                })
                                ->orWhere(DB::raw('LOWER('.with(new OrderCheckoutDetail)->getTable().'.order_no)'),'LIKE','%'.strtolower($param['search']).'%');
                    }

                    $data = $data->orderBy('updated_at', 'DESC')->orderBy('created_at', 'DESC')->get();

                    // if($data){
                        // $data->makeHidden(['id']);
                    // }

                }else if($param['type'] == 'customer'){
                    $data = MessagingRooms::where('ref_type','')
                            ->with('customer')
                            ->where('vendor_id', '=', auth()->user()->user_ref_id);

                    if(array_key_exists('search',$param) && $param['search'] != null){  // search by customer
                        $data = $data->whereHas('customer', function($q) use ($param){
                                    $q->where(DB::raw('LOWER('.with(new Customer)->getTable().'.name)'),'LIKE','%'.strtolower($param['search']).'%');
                                });
                    }

                    $data = $data->orderBy('last_message_created_at', 'DESC')->get();
                    if($data){
                        $data->makeHidden(['id']);
                    }
                }
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

            if(!$param['ref_type'] || !$param['ref_id_hash']){
                 if($param['room_hash']){
                    $data['parent'] =  MessagingRooms::with(['customer','customer.school'])->with('vendor')->where('room_id',$param['room_hash'])->latest()->first();
                 }else{
                    return array('status'=>true,'detail'=>'parameter tidak lengkap');
                 }
            }else{
                $ref_type = $this->my_type($param['ref_type']);
                $data['parent'] = MessagingRooms::with(['customer','customer.school'])->with('vendor')->where('vendor_id',auth()->user()->user_ref_id);
                if($param['ref_type'] == 'customer'){
                    $data['parent'] = $data['parent']->where('user_id',intval($param['ref_id_hash']));
                }else{
                    $data['parent'] = $data['parent']->where('ref_id',intval($param['ref_id_hash']));
                }
                $data['parent'] = $data['parent']->where('ref_type',strtoupper($ref_type))->latest()->first();
            }


            if($data['parent']){
                $data['child'] = MessagingMessages::where('room',$data['parent']->room_id)
                        ->orderBy('created_at', 'ASC')
                        ->get();
            }
            //dd($data['parent']);
            // dd($data['child']);
            return array('status'=>true,'detail'=>$data);
        }
    }

    public function my_type($ref_type){
        if($ref_type == 'customer'){
            $ref_type = '';
        }
        return $ref_type;
    }

    public function create_room(Request $request)
    {
        $param = $request->all();
        $data = array();

        $ref_type = $this->my_type($param['ref_type']);
        $data['room_id'] = hash('sha256',auth()->user()->user_ref_id.'-'.auth()->user()->id.'-'.$ref_type.'-'.$param['ref_id_hash']);
        $data['ref_type'] = strtoupper($ref_type);
        $data['ref_id'] = intval($param['ref_id_hash']);
        $data['vendor_id'] = auth()->user()->user_ref_id;
        $data['vendor_name'] = @Vendor::where('id','=',auth()->user()->user_ref_id)->value('name');
        $data['is_read_by_vendor'] = true;
        $data['is_read_by_member'] = false;

        if($ref_type == 'transaction'){ // ref_id_hash is order_checkout_detail_id
            $orderCheckoutDetailModel = OrderCheckoutDetail::where('id',$param['ref_id_hash'])->latest()->first();
            if($orderCheckoutDetailModel){
                $data['user_id'] = $orderCheckoutDetailModel->created_by;
                $data['user_name'] = @Customer::where('user_id','=',$orderCheckoutDetailModel->created_by)->value('name');
                $data['school_id'] = $orderCheckoutDetailModel->school_id;
                $data['school_name'] = @School::where('sekolah_id','=',$orderCheckoutDetailModel->school_id)->value('school_name');
            }else{
                return array('status'=>false, 'message'=>'Informasi order detail tidak ditemukan');
            }
        }else{ // ref_id_hash is user_id
            $customerModel = Customer::where('user_id','=',$param['ref_id_hash'])->first();
            if($customerModel){
                $data['user_id'] = $param['ref_id_hash'];
                $data['user_name'] = $customerModel->name;
                $data['school_id'] = $customerModel->sekolah_id;
                $data['school_name'] = @School::where('sekolah_id','=',$customerModel->sekolah_id)->value('school_name');
            }else{
                return array('status'=>false, 'message'=>'Informasi kustomer tidak ditemukan');
            }
        }
        $data2['last_message'] ='';
        $data2['last_message_from'] = '';
        $data2['last_message_from_type'] = '';
        $data2['last_message_created_at'] = '';

        $messagingRoomModel = MessagingRooms::create($data);
        return array('status'=>true, 'data'=>$messagingRoomModel);
    }

    public function api_store_message(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();
            $msg = 'menambahkan pesan baru';
            $date_now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');
            // dump($param);

            try {
                // DB::enableQueryLog();
                // date_default_timezone_set('Asia/Jakarta');

                $filename_original  = null;
                $image_status = false;
                if(array_key_exists('image',$param)){
                    $disk = Storage::disk('gcs');
                    $filename_unique    = 'complain/'.md5(date("Ym")).'/'.sha1(time()).'-'.auth()->user()->user_ref_id;
                    $filename_original  = $filename_unique.'.'.($param['image']->getClientOriginalExtension());
                    $image_status = $disk->put($filename_original,File::get($param['image']));
                }

                if(array_key_exists('image',$param) && !$image_status){
                    $output = array('status'=>false, 'message'=>'Foto gagal diupload');
                }else{
                    // check room
                    if(!(array_key_exists('room_hash',$param) && $param['room_hash'])){
                        // get existing room
                        $ref_type = $this->my_type($param['ref_type']);
                        $parent = MessagingRooms::with('customer')->with('vendor')->where('vendor_id',auth()->user()->user_ref_id);
                        if($param['ref_type'] == 'customer'){
                            $parent = $parent->where('user_id',intval($param['ref_id_hash']));
                        }else{
                            $parent = $parent->where('ref_id',intval($param['ref_id_hash']));
                        }
                        $parent = $parent->where('ref_type',strtoupper($ref_type))->latest()->first();

                        if(!$parent){ // if no room then create new room
                            $parent = $this->create_room($request);

                            if(!$parent['status']){
                                return array('status'=>false, 'message'=>'Gagal membuat ruang percakapan baru. '.$parent['message']);
                            }else if(!$parent['data']){
                                return array('status'=>false, 'message'=>'Gagal membuat ruang percakapan baru. Coba lagi.');
                            }else{
                                $param['room_hash'] = $parent['data']->room_id;
                            }
                        }else{
                            $param['room_hash'] = $parent->room_id;
                        }
                    }

                    \DB::beginTransaction();

                    $data['room'] = $param['room_hash'];
                    $data['message'] = $param['message']?$param['message']:'';
                    $data['image'] =  $filename_original;
                    $data['from'] = auth()->user()->user_ref_id;
                    $data['from_type'] = 'VENDOR';
                    $data['created_at'] = Carbon::now()->toIso8601String();
                    $data['ip'] = $request->ip();
                    $data['browser'] = $request->header('User-Agent');
                    $messagingMessagesModel = MessagingMessages::create($data);

                    $data2 = array();
                    $data2['last_message'] = $data['message'];
                    $data2['last_message_from'] = $data['from'];
                    $data2['last_message_from_type'] = $data['from_type'];
                    $data2['last_message_created_at'] = $data['created_at'];
                    $data2['is_read_by_member'] = false;
                    MessagingRooms::where('room_id',$data['room'])->update($data2);

                    $messagingRoomModel = MessagingRooms::where('room_id',$param['room_hash'])->first();
                    $customerModel = @Customer::where('user_id','=',@$messagingRoomModel->user_id)->first();
                    $schoolModel =  @School::where('sekolah_id','=',@$customerModel->sekolah_id)->first();

                    // broadcast::AGREGASI
                    $payload_agregation = array(
                        "agregation_name"=>"ChatSubmitted",
                        "user_id"=>@$messagingRoomModel->user_id,
                        "school_id"=>@$schoolModel->sekolah_id,
                        "event_desc"=>"Chat Submitted by Vendor",
                        "transaction_id"=>($param['ref_type']=='transaction'?$param['ref_id_hash']:''),
                        "chat_id"=>@$messagingMessagesModel->_id,
                        "data"=>json_encode(
                            array(
                                "submittedChat"=>array(
                                    "chatRoomType"=>array(
                                        "merchant"=>array(
                                            "school"=>(string)@$schoolModel->sekolah_id,
                                            "merchant"=>(string)@$messagingRoomModel->vendor_id,
                                            "chatBy"=>array(
                                                "merchant"=>array(
                                                    "user"=>(string)auth()->user()->id,
                                                    "merchant"=>(string)@$messagingRoomModel->vendor_id
                                                )
                                            )
                                        )
                                    ),
                                    "context"=>$data['message'],
                                    "occurredAt"=>$date_now
                                ),
                                "sentAt"=>$date_now
                            )
                        )
                    );
                    BroadcastHelper::send($request,$payload_agregation,'',$date_now);

                    // broadcast::MAIL
                    if($param['ref_type'] == 'customer'){
                        $template_name = ($filename_original?'NEW_MESSAGE_TOBUYER__IMAGE':'NEW_MESSAGE_TOBUYER');
                        $template = EmailTemplate::where('name',$template_name)->first();
                        $payload_email = array(
                            "template_name" => $template_name,
                            "email_to" => @$customerModel->email,
                            // "email_to" => "elinyine@gmail.com", // test only
                            "email_atribute" => array(
                                array('name' => 'school_name', 'value' => @$schoolModel->school_name),
                                array('name' => 'vendor_name', 'value' => auth()->user()->name),
                                array('name' => 'buyer_name', 'value' => @$customerModel->name),
                                array('name' => 'message', 'value' => @$data['message']),
                                array('name' => 'image', 'value' => $this->data['url']['disk']->url($filename_original)),
                                array('name' => 'link', 'value' => @$template->ref_link),
                            )
                        );
                    }else{
                        $orderCheckoutDetailModel = OrderCheckoutDetail::where('id',$param['ref_id_hash'])->latest()->first();
                        $template_name = ($filename_original?'NEW_MESSAGE_ORDER_TOBUYER__IMAGE':'NEW_MESSAGE_ORDER_TOBUYER');
                        $template = EmailTemplate::where('name',$template_name)->first();
                        $payload_email = array(
                            "template_name" => $template_name,
                            "email_to" => @$customerModel->email,
                            // "email_to" => "elinyine@gmail.com", // test only
                            "email_atribute" => array(
                                array('name' => 'order_no', 'value' => @$orderCheckoutDetailModel->order_no),
                                array('name' => 'vendor_name', 'value' => auth()->user()->name),
                                array('name' => 'buyer_name', 'value' => @$customerModel->name),
                                array('name' => 'message', 'value' => @$data['message']),
                                array('name' => 'image', 'value' => $this->data['url']['disk']->url($filename_original)),
                                array('name' => 'link', 'value' => @$template->ref_link),
                            )
                        );
                    }
                    MailHelper::send($payload_email);

                    $output = array('status'=>true, 'message'=>'Sukses '.$msg, 'detail'=>$messagingMessagesModel);
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
                if(!array_key_exists('room',$param)){
                    $output = array('status'=>false, 'message'=>'Parameter tidak lengkap');
                }else{
                    \DB::beginTransaction();
                    $data = array();
                    $data['is_read_by_vendor'] = true;
                    $messagingRoomModel_set = MessagingRooms::where('room_id',$param['room'])->update($data);
                    $output = ($messagingRoomModel_set ? array('status'=>true, 'message'=>'Sukses '.$msg) : array('status'=>false, 'message'=>'Parameter tidak sesuai'));
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

    public function download(Request $request, $room_hash){

        $this->data['transcript_title'] = '';
        $this->data['parent'] = MessagingRooms::with('customer')->with('vendor')->find($room_hash);
        if($this->data['parent']){
            $this->data['child'] = MessagingMessages::where('room',$room_hash)
                                    ->orderBy('created_at', 'ASC')
                                    ->get();
            if($this->data['parent']->ref_type == 'TRANSACTION'){
                $this->data['transcript_title'] = 'Order '.(OrderCheckoutDetail::where('id','=',$this->data['parent']->ref_id)->value('order_no'));
            }else{
                $this->data['transcript_title'] = '-';
            }
        }
        $this->data['disk'] = Storage::disk('gcs');

        return view('pages/messaging/pdf',$this->data);
    }

    public function print(Request $request, $hash){
        $explode_hash = explode("-",$hash);
        if(count($explode_hash)===2){
            $private_key = $explode_hash[0];
            $public_key = $explode_hash[1];
            $public_key_base64_decode = base64_decode($public_key);
            $public_key_value_explode = explode(",",$public_key_base64_decode);
            if(count($explode_hash)===2) {
                $room_id = $public_key_value_explode[0];
                $timestamp = $public_key_value_explode[1];
                if($private_key===hash('sha256',$room_id.$timestamp.env("PRINT_PRIVATE_KEY"))){
                    $interval  = abs(strtotime("now") - strtotime($timestamp));
                    $minutes   = round($interval / 60);
                    if($minutes<=env("PRINT_MINUTES_EXPIRED")){
                        $room = Room::where('room_id',$room_id)->first();
                        $hash_room_id = hash('sha256',$room_id);
                        if($room){
                            return $this->download($request, $hash_room_id);
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
