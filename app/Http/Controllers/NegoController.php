<?php

namespace App\Http\Controllers;

use App\Helpers\BroadcastHelper;
use App\Helpers\MailHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\UserCart;
use App\Models\Negotiation;
use App\Models\EmailTemplate;
use Carbon\Carbon;

class NegoController extends Controller
{
    public function index(Request $request){
        //dd(auth()->user());
        $idx_status = $request->get('stid');
        if(intval($idx_status) > 0 && intval($idx_status) < 7 || $idx_status == null){
            return view('pages/nego/index',$this->data);
        }else{
            return redirect()->route('nego');
        }

    }

    public function apiGetList(Request $request)
    {
        try{
            // Default Value
            $columns = array(
                0 => 'mp.name',
                1 => 'quantity',
                2 => 'nego_price', // negotiation
                3 => 'tr_user_cart.created_at', // negotiation
                4 => 'offer_price', // negotiation | offer_price
            );

            $status = array(
                1 => 'BUYER_COUNTERED',
                2 => 'SELLER_COUNTER',
                3 => 'APPROVED',
                4 => 'REJECTED',
                5 => 'CANCELED',
                6 => 'EXPIRED'
            );

            $idx_status = $request->get('stid');
            $int_idx_status = intval($idx_status);

            $page_start = $request->input('start');
            $page_length = $request->input('length');

            //Variable Filter for query
            $draw = $request->input('draw');
            $fl = $request->input('columns'); //array of filter column
            $sort = $request->input('order');
            //\DB::enableQueryLog();
            $fl_name = $fl[0]['search']['value'];
            $fl_qty = explode("|",$fl[1]['search']['value']); //array 0 is operation | array 1 is the value of search
            $fl_nego_price= explode("|",$fl[2]['search']['value']);
            $fl_offer_date = explode("|",$fl[3]['search']['value']);
            $fl_offer_price = explode("|",$fl[4]['search']['value']);

            $filter_status = array("STARTED",$status[1]);
            if($idx_status != "1" && $idx_status != null) $filter_status = array($status[$idx_status]);

            if($request->ajax()) {
                $query = UserCart::query();
                $query->select('mp.name','tr_user_cart.id as actions', 'tr_user_cart.quantity as quantity', DB::raw("CONCAT(mp.name,'|',mp.image) as product"), 'tucn1.nego_price as nego_price', 'tucn2.offer_price as offer_price', 'tr_user_cart.updated_at as offer_date');
                $query->leftJoin('ms_product as mp', 'mp.id', '=', 'tr_user_cart.product_id');
                $query->leftJoin(DB::raw('(SELECT DISTINCT ON (cart_id) cart_id, nego_price FROM tr_user_cart_negotiation WHERE vendor_id IS NULL ORDER BY cart_id, created_at DESC) tucn1'),
                    function ($join) {
                        $join->on('tr_user_cart.id', '=', 'tucn1.cart_id');
                    });
                $query->leftJoin(DB::raw('(SELECT DISTINCT ON (cart_id) cart_id, nego_price as offer_price FROM tr_user_cart_negotiation WHERE vendor_id IS NOT NULL AND nego_price IS NOT NULL ORDER BY cart_id, created_at DESC) tucn2'),
                    function ($join) {
                        $join->on('tr_user_cart.id', '=', 'tucn2.cart_id');
                    });

                //Filter Negotiation status
                $query->whereIn('negotiation_status', $filter_status);

                //Filter Product Vendor
                $query->where('mp.vendor_id', '=', auth()->user()->user_ref_id);
                $total_count = $query->count();
                //Filter Product Name
                if ($fl_name != "") $query->where('mp.name', 'ilike', '%' . $fl_name . '%');

                //Filter Quantity
                if ($fl_qty[0] != "" && $fl_qty[1] != "") $query->where('quantity', $fl_qty[0], $fl_qty[1]);

                //Filter Nego Price
                if ($fl_nego_price[0] != "") {
                    if (array_key_exists(1, $fl_nego_price)) {
                        if($fl_nego_price[1] != ""){
                            $query->whereBetween('nego_price', [$fl_nego_price[0], $fl_nego_price[1]]);
                        }else{
                            $query->where('nego_price', '>=', $fl_nego_price[0]);
                        }
                    } else {
                        $query->where('nego_price', '>=', $fl_nego_price[0]);
                    }
                }

                if ($fl_offer_date[0] != "" && $fl_offer_date[1] != "") {
                    $query->whereDate('tr_user_cart.updated_at', '>=', $fl_offer_date[0]);
                    $query->whereDate('tr_user_cart.updated_at','<=',$fl_offer_date[1]);
                }
                //dd($fl_offer_price);
                if ($fl_offer_price[0] != "") {
                    if (array_key_exists(1, $fl_offer_price)) {
                        if($fl_offer_price[1] != ""){
                            $query->whereBetween('offer_price', [(int) $fl_offer_price[0], (int) $fl_offer_price[1]]);
                        }else{
                            $query->where('offer_price', '>=', (int) $fl_offer_price[0]);
                        }
                    } else {
                        $query->where('offer_price', '>=', (int) $fl_offer_price[0]);
                    }
                }

                //dd($sort[0]['dir']);
                $query->orderBy($columns[$sort[0]['column']], $sort[0]['dir']);
                $filter_count = $query->count();
                $data_nego = $query->skip($page_start)->take($page_length)->get()->toArray();

                //if(count($data_nego) < 1) $count_nego = 0;

                $data_nego = array_map(function($data){
                    $data['actions'] = Crypt::encryptString($data['actions']);
                    return $data;
                },$data_nego);
                //dd($list_cart[0]->negotiation);
                //dd(\DB::getQueryLog());
                //dd($list_cart[0]->negotiation[0]->nego_price);
                //dd($list_cart[0]->product->images[0]->image_url);
                $data = array(
                    'draw' => $draw,
                    'recordsFiltered' => $filter_count,
                    'recordsTotal' => $total_count,
                    'data' => $data_nego
                );
                return $data;
            }
        }catch(Exception $err){
            return array('status'=>'error','message'=>'Terjadi error mohon dicoba lagi','data'=>array());
        }
    }

    public function apiGetDetail(Request $request){
        //return $request->all();
        $id = Crypt::decryptString($request->input('id'));
        //return $id;
        $nego = Negotiation::where('cart_id',$id)->with(['customer','vendor','customer.school'])->latest()->get();
        $data_nego = array();
        foreach($nego as $n){
            $nego_price = '-';

            if($n->nego_price != null){
                $nego_price = 'Rp. '.number_format($n->nego_price,0,",",".");
            }

            $temp = array(
                'date' => date('Y-m-d H:i:s',strtotime(Carbon::createFromFormat('Y-m-d H:i:s',$n->created_at,'UTC')->setTimezone('Asia/Jakarta'))),
                'nego_price' => $nego_price,
                'nego_note' => $n->nego_note
            );

            if($n->vendor_id != null){
                $temp['name'] = $n->vendor->name;
            }else{
                $temp['name'] = $n->customer->school->school_name."<br>".$n->customer->name;
            }

            array_push($data_nego,$temp);
        }
        return $data_nego;
    }

    public function apiActionNego(Request $request){

        $hash_id = Crypt::decryptString($request->input('id'));
        $status = $request->input('status');
        $data = $request->input('data');
        $last_price_nego = 0;
        $nego_price = null;
        $payload = array();
        $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');

        $nego_status = array(
            'menawar' => 'SELLER_COUNTER',
            'menolak' => 'REJECTED',
            'menerima' => 'APPROVED'
        );

        $template_name = "";
        try{
            $user_cart = UserCart::where('id',$hash_id)->with('customer','product')->first();
            $user_cart->negotiation_status = $nego_status[$status];
            $user_cart->updated_at = date('Y-m-d H:i:s');

            $payload = array(
                'user_id' => $user_cart->user_id,
                'school_id' => $user_cart->school_id,
                'negotiation_id' => $user_cart->id
            );

            if($status == 'menerima'){
                $last_price_nego = $user_cart->last_counter_negotiation_price;
                $user_cart->negotiation_approved_date = date('Y-m-d H:i:s');
                $user_cart->valid_negotiation_price = $user_cart->last_counter_negotiation_price;
                $nego_price = $user_cart->last_counter_negotiation_price;

                //Payload Agregation
                $payload['agregation_name'] = 'NegotiationApproved';
                $payload['event_desc'] = 'Negotiation Approved by VendorID : '.auth()->user()->user_ref_id;
                $payload['data'] = json_encode(array(
                    'approvedPartyBy' => array(
                        'merchant' => array(
                            'user' => strval(auth()->user()->id),
                            'merchant' => strval(auth()->user()->user_ref_id)
                        ),
                    ),
                    "approvedUnitPrice"=> $last_price_nego,
                    "occurredAt" => $timestamp,
                    "sentAt" => $timestamp
                ));

                $template_name = "NEGOTIATION_APPROVED";

            }

            if($status == "menawar"){
                $last_price_nego = $user_cart->last_counter_negotiation_price;
                $user_cart->last_counter_negotiation_price = $user_cart->valid_negotiation_price;
                $user_cart->valid_negotiation_price = (int) str_replace('.','',$data['nego_price']);
                $nego_price = (int) str_replace('.','',$data['nego_price']);
                $payload['agregation_name'] = 'NegotiationCountered';
                $payload['event_desc'] = 'Negotiation Countered for by VendorID : '.auth()->user()->user_ref_id;
                $payload['data'] = json_encode(array(
                    'counteredBy' => array(
                        'merchant' => array(
                            'user' => strval(auth()->user()->id),
                            'merchant' => strval(auth()->user()->user_ref_id)
                        ),
                    ),
                    "expectedUnitPrice"=> $last_price_nego,
                    "counteredUnitPrice" => $nego_price,
                    "context" => $data['nego_note'],
                    "occurredAt" => $timestamp,
                    "sentAt" => $timestamp
                ));
                $template_name = "NEGOTIATION_COUNTERED_TOBUYER";
                $last_price_nego = $nego_price;
            }

            if($status == "menolak"){
                $last_price_nego = $user_cart->last_counter_negotiation_price;
                $user_cart->negotiation_approved_date = date('Y-m-d H:i:s');
                $payload['agregation_name'] = 'NegotiationRejected';
                $payload['event_desc'] = 'Negotiation Rejected for by VendorID : '.auth()->user()->user_ref_id;
                $payload['data'] = json_encode(array(
                    'rejectedPartyBy' => array(
                        'merchant' => array(
                            'user' => strval(auth()->user()->id),
                            'merchant' => strval(auth()->user()->user_ref_id)
                        ),
                    ),
                    "rejectedUnitPrice"=> $user_cart->last_counter_negotiation_price,
                    "occurredAt" => $timestamp,
                    "sentAt" => $timestamp
                ));
                $template_name = "NEGOTIATION_REJECTED";
            }

            $nego = new Negotiation;
            $nego->cart_id = $user_cart->id;
            $nego->product_id = $user_cart->product_id;
            $nego->nego_price = $nego_price;
            $nego->negotiation_status = $nego_status[$status];
            $nego->nego_note = $data['nego_note'];
            $nego->created_at = date('Y-m-d H:i:s');
            $nego->vendor_id = auth()->user()->user_ref_id;
            $nego->nego_from = (int) $user_cart->user_id;
            $nego->created_by = (int) $user_cart->user_id;
            $save_nego = $nego->save();

            $save_user_cart = $user_cart->save();
            if($save_nego && $save_user_cart){
                BroadcastHelper::send($request,$payload);

                //Payload Email
                $template = EmailTemplate::where('name',$template_name)->first();
                $payload_email = array(
                    "template_name" => $template_name,
                    "email_to" => $user_cart->customer->email,
                    "email_atribute" => array(
                        array('name' => 'vendor_name', 'value' => auth()->user()->name),
                        array('name'=> 'buyer_name', 'value' => $user_cart->customer->name),
                        array('name' => 'product_name', 'value' => $user_cart->product->name),
                        array('name' => 'nego_price', 'value' => $last_price_nego),
                        array('name' => 'message', 'value' => ($data['nego_note'] == "") ? "-" : $data['nego_note'] ),
                        array('name' => 'link', 'value' => $template->ref_link)
                    )
                );
                //dd($payload_email);
                MailHelper::send($payload_email);
            }

            $data = array('status'=>'success','message'=>'Tawaran telah berhasil diperbaharui');
        }catch(Exception $err){
            $data = array('status'=>'error','message'=>'Maaf telah terjadi error ketika memperbaharui tawaran. Mohon untuk mencoba kembali');
        }
        return $data;
    }

    public function download(Request $request, $hash_id){
        //dd($id);

        $query = UserCart::query();
        $query->select('tr_user_cart.id as id', 'tr_user_cart.quantity as quantity', 'mp.name as product_name', 'mp.image as product_image','tucn1.nego_price as nego_price', 'tucn2.offer_price as offer_price', 'tr_user_cart.created_at as offer_date');
        $query->leftJoin('ms_product as mp', 'mp.id', '=', 'tr_user_cart.product_id');
        $query->leftJoin(DB::raw('(SELECT cart_id, nego_price, created_at as po_date_customer FROM tr_user_cart_negotiation WHERE vendor_id IS NULL ORDER BY created_at DESC) tucn1'),
            function ($join) {
                $join->on('tr_user_cart.id', '=', 'tucn1.cart_id');
            });
        $query->leftJoin(DB::raw('(SELECT cart_id, nego_price as offer_price, created_at as po_date_vendor FROM tr_user_cart_negotiation WHERE vendor_id IS NOT NULL ORDER BY created_at DESC) tucn2'),
            function ($join) {
                $join->on('tr_user_cart.id', '=', 'tucn2.cart_id');
            });
        $query->where("tr_user_cart.id",Crypt::decryptString($hash_id));
        $this->data['nego'] = $query->first()->toArray();
        $nego = Negotiation::select('*')->where('cart_id',$this->data['nego']['id'])->with(['customer','vendor'])->latest()->get();

        $nego_detail = array();

        foreach($nego as $n){
            $temp = array(
                'date' =>  date('Y-m-d H:i:s',strtotime(Carbon::createFromFormat('Y-m-d H:i:s',$n->created_at,'UTC')->setTimezone('Asia/Jakarta'))),
                'nego_price' => $n->nego_price,
                'nego_note' => $n->nego_note
            );
            if($n->vendor_id != null){
                $temp['name'] = $n->vendor->name;
            }else{
                $temp['name'] = $n->customer->name;
            }

            array_push($nego_detail,$temp);
        }

        $this->data['nego_detail'] = $nego_detail;
        //dd($this->data);
        return view('pages/nego/pdf',$this->data);
    }

    public function print(Request $request, $hash){
        $explode_hash = explode("-",$hash);
        if(count($explode_hash)===2){
            $private_key = $explode_hash[0];
            $public_key = $explode_hash[1];
            $public_key_base64_decode = base64_decode($public_key);
            $public_key_value_explode = explode(",",$public_key_base64_decode);
            if(count($explode_hash)===2) {
                $cart_id = $public_key_value_explode[0];
                $timestamp = $public_key_value_explode[1];
                if($private_key===hash('sha256',$cart_id.$timestamp.env("PRINT_PRIVATE_KEY"))){
                    $interval  = abs(strtotime("now") - strtotime($timestamp));
                    $minutes   = round($interval / 60);
                    if($minutes<=env("PRINT_MINUTES_EXPIRED")){
                        $cart = UserCart::where('id',$cart_id)->first();
                        $hash_cart_id =  Crypt::encryptString($cart_id);
                        if($cart){
                            return $this->download($request, $hash_cart_id);
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
