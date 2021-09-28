<?php

namespace App\Http\Controllers;

use App\Helpers\BroadcastHelper;
use App\Helpers\MailHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\OrderCheckout;
use App\Models\OrderCheckoutDetail;
use App\Models\OrderDelivery;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\OrderCheckoutItem;
use App\Models\OrderBast;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\SelectionList;
use App\Models\Config;
use App\Models\Log;
use App\Models\EmailTemplate;
use App\Models\OrderUpdate;
use App\Models\OrderUpdateDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\UTCDateTime;
use Cookie;

class OrderController extends Controller
{
    private $status;
    private $red_status;
    private $status_ship;

    public function __construct()
    {
        parent::__construct();
        $selection_list = SelectionList::where('selection_type','ORDER_STATUS')->get();
        //dd($selection_list);
        //foreach($selected_list as $list){
        //    $this->status[$list->name] = $list->desc_idn;
        //}

        $this->status = array(
            'CREATED' => 'PESANAN BARU', //IJO NO JNE
            'ORDER_CONFIRM' => 'PESANAN DIKONFIRMASI', //IJO NO JNE
            'ORDER_REJECTED' => 'PESANAN DITOLAK', //MERAH NO JNE
            'BUYER_REJECT' => 'PESANAN DITOLAK PELANGGAN', //MERAH NO JNE
            'BUYER_APPROVED' => 'PESANAN DISETUJUI', //IJO NO JNE
            'ORDER_PROCESSED' => 'PESANAN DIPROSES', //IJO NO JNE
            'ORDER_SHIPPED' => 'PESANAN DIKIRIM', //IJO
            'ORDER_DELIVERED' => 'PESANAN TELAH DIKIRIM', //IJO
            'ORDER_RECEIVED' => 'PESANAN DITERIMA', //IJO
            'BAST_CREATED' => 'MENUNGGU KONFIRMASI BAST', //IJO
            'BAST_SUBMITTED' => 'MENUNGGU PEMBAYARAN', //IJO
            'BAST_REJECTED' => 'BAST DITOLAK', //MERAH
            'PAYMENT_PROCESSED' => 'PEMBAYARAN DIPROSES', //IJO
            'PAYMENT_COMPLAINED' => 'PEMBAYARAN DIKELUHKAN',
            'PAYMENT_CONFIRMED' => 'PEMBAYARAN TELAH DIKONFIRMASI', //IJO
            'COMPLETED' => 'SELESAI', //IJO
            'TESTIMONY_SUBMITTED' => 'PESANAN TELAH DIULAS', //IJO
            'AGREEMENT_UPDATED' => 'PERSETUJUAN DIPERBAHARUI', //IJO
            'CANCELED' => 'DIBATALKAN', // MERAH
            'EXPIRED' => 'KADALUARSA' // MERAH
        );

        $this->red_status = array(
            'EXPIRED', 'CANCELED', 'BAST_REJECTED', 'BUYER_REJECT', 'ORDER_REJECTED'
        );

        $this->status_ship = array(
            'ORDER_SHIPPED','ORDER_DELIVERED', 'ORDER_RECEIVED','BAST_CREATED','BAST_SUBMITTED','BAST_REJECTED','PAYMENT_PROCESSED',
            'PAYMENT_CONFIRMED', 'COMPLETED', 'TESTIMONY_SUBMITTED'
        );
    }

    private $status_receive = array(
        'ORDER_RECEIVED','BAST_CREATED','BAST_SUBMITTED','BAST_REJECTED','PAYMENT_PROCESSED',
        'PAYMENT_CONFIRMED', 'COMPLETED', 'TESTIMONY_SUBMITTED'
    );

    private $cancel_status = array(
        'CANCELLATION_PROPOSED' => 'MENGAJUKAN PEMBATALAN', //danger
        'CANCELLATION_APPROVED' => 'PEMBATALAN DITERIMA', //primary
        'CANCELLATION_REJECTED' => 'PEMBATALAN DITOLAK', //info
        'CANCEL_SISTEM' => 'PEMBATALAN OTOMATIS' //info
    );

    private $bast_status = array(
        'CREATED' => 'BUTUH DIRESPON',
        'REJECTED' => 'DITOLAK',
        'SUBMITTED' => 'DITERIMA'
    );

    private $status_invoice = array(
        'BAST_SUBMITTED','PAYMENT_PROCESSED','PAYMENT_CONFIRMED','COMPLETED','TESTIMONY_SUBMITTED'
    );

    private $status_after_payment = array(
        'PAYMENT_CONFIRMED','COMPLETED','TESTIMONY_SUBMITTED', 'BAST_REJECTED'
    );

    private $status_doc = array(
        'CREATED', 'ORDER_CONFIRM', 'ORDER_REJECTED', 'BUYER_REJECT'
    );

    public function index(){
        $data = array();
        return view('pages/order/index',$data);
    }

    public function show_detail($hash_id){
        $hash_id = Crypt::decryptString($hash_id);
        //dd($hash_id);
        $log = Log::where('transaction_id',(int)$hash_id)->orderBy('id','desc')->get();
        //dd($log);
        //dd(OrderCheckoutDetail::where('id',$hash_id)->with('order_delivery')->first());
        $selected_order_checkout_detail = OrderCheckoutDetail::where('id',$hash_id)
                                ->with([
                                    'order_delivery',
                                    'order','order_item',
                                    'customer','vendor',
                                    'order_item.product',
                                    'order_item.user_cart'=>function($query){
                                        $query->whereNotNull('negotiation_status');
                                    },
                                    'shipment_service','shipment_service.provider',
                                    'bast'=>function($query){
                                        $query->orderBy('id','asc');
                                    },
                                    'receive','receive.receive_detail',
                                    'complaint','invoice_payment'=>function($query){
                                        $query->where('status','PAID');
                                    },
                                    'update_order'
                                    ])->first();
        //dd(DB::getQueryLog());
        if($selected_order_checkout_detail->expired_cancel_at != null){
            $date1 = strtotime(Carbon::createFromFormat('Y-m-d H:i:s', $selected_order_checkout_detail->expired_cancel_at, 'UTC')->setTimezone('Asia/Jakarta'));
            $date2 = strtotime(Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), 'UTC')->setTimezone('Asia/Jakarta'));

            $diff = floor(($date1 - $date2)/86400);
            $selected_order_checkout_detail->expired_date = $diff;
        }

        if($selected_order_checkout_detail->cancel_status != null) {$selected_order_checkout_detail->id_cancel_status = $this->cancel_status[$selected_order_checkout_detail->cancel_status];}

        $selected_order_checkout_detail->id_order_status = $this->status[$selected_order_checkout_detail->status];

        $selected_order_checkout_detail->status_update_order = null;
        if($selected_order_checkout_detail->update_order != null){
            $selected_order_checkout_detail->status_update_order = $selected_order_checkout_detail->update_order->status;
        }

        $selected_order_checkout_detail->payment_diff = null;
        $selected_order_checkout_detail->is_complained = false;
        if(count($selected_order_checkout_detail->bast) > 0 ){
            $date = Carbon::parse($selected_order_checkout_detail->bast[count($selected_order_checkout_detail->bast)-1]->seller_confirm_date);
            $now = Carbon::now();
            $selected_order_checkout_detail->payment_diff = $date->diffInDays($now);
        }
        if($selected_order_checkout_detail->invoice != null){
            if($selected_order_checkout_detail->invoice->is_complained == true) $selected_order_checkout_detail->is_complained = true;
        }

        $this->data['selected'] = $selected_order_checkout_detail;
        $this->data['status_ship'] = $this->status_ship;
        $this->data['status_invoice'] = $this->status_invoice;
        //$this->data['status_doc'] = $this->status_doc;
        $this->data['bast_status'] = $this->bast_status;
        //$this->data['status_receive'] = $this->status_receive;
        $this->data['red_status'] = $this->red_status;
        $this->data['logs'] = $log;
        $this->data['status_after_payment'] = $this->status_after_payment;
        //dd($this->data['selected']);
        //dd(!in_array($selected_order_checkout_detail->status,$this->status_doc));
        return view('pages/order/detail',$this->data);
    }

    public function api_get_list(Request $request)
    {
        if($request->ajax()) {
            $draw = $request->input('draw');
            $fl = $request->input('columns');
            $fl_po_no = $fl[0]['search']['value'];
            $fl_cust = $fl[1]['search']['value'];
            $fl_dest = $fl[2]['search']['value'];
            $fl_date= explode("|",$fl[3]['search']['value']);
            $fl_price = explode("|",$fl[4]['search']['value']);
            $page_start = $request->input('start');
            $page_length = $request->input('length');
            $req_status = $request->input('status');
            //dd($fl_po_no);
            $list_order_checkout_detail = OrderCheckoutDetail::select('tr_order_checkout_detail.status as status',
                DB::raw("CONCAT(mmp.name,'|',mmp.email,'|',mmp.phone,'|',tr_order_checkout_detail.order_no,'|',tr_order_checkout_detail.po_no) as customer"),
                DB::raw("CONCAT(toc.delivery_address,'|',toc.delivery_postal_code) as order"),
                'tr_order_checkout_detail.created_at as created_at','tr_order_checkout_detail.cancel_status as cancel_status',
                'tr_order_checkout_detail.total_price as total_price','tr_order_checkout_detail.id as id','toco.complain_status','tou.status as edit_status')
                ->leftJoin('tr_order_checkout as toc','tr_order_checkout_detail.checkout_id','=','toc.id')
                ->leftJoin('ms_member_profile as mmp','tr_order_checkout_detail.created_by','=','mmp.user_id')
                ->leftJoin('tr_order_complain as toco','tr_order_checkout_detail.id','=','toco.checkout_detail_id')
                ->leftJoin('tr_order_update as tou','tr_order_checkout_detail.id','=','tou.checkout_detail_id')
                ->leftJoin('tr_order_invoice as toi','tr_order_checkout_detail.id','=','toi.checkout_detail_id');
            $list_order_checkout_detail->where('tr_order_checkout_detail.vendor_id',auth()->user()->user_ref_id);
            if($req_status != ""){
                if($req_status == "COMPLAINT"){
                    $list_order_checkout_detail->whereNotNull('toco.complain_status');
                }elseif($req_status == "ORDER_UPDATE"){
                    $list_order_checkout_detail->where('tou.status','=','REQUEST');
                }elseif($req_status == "PAYMENT_COMPLAINED"){
                    $list_order_checkout_detail->where('toi.is_complained','=',true);
                }else{
                    $status = explode(",",$req_status);
                    $list_order_checkout_detail = $list_order_checkout_detail->whereIn('tr_order_checkout_detail.status',$status);
                }
            }
            $total_count = $list_order_checkout_detail->count();
            if($fl_po_no != "") $list_order_checkout_detail->where(function($query) use ($fl_po_no){
                $query->where('po_no','ilike','%'.$fl_po_no.'%');
                $query->where('order_no','ilike','%'.$fl_po_no.'%');
            });
                //$list_order_checkout_detail->where('po_no','ilike','%'.$fl_po_no.'%')->orWhere('order_no','ilike','%'.$fl_po_no.'%');
            if($fl_cust != "") $list_order_checkout_detail->where(function($query) use ($fl_cust){
                $query->where('mmp.name','ilike','%'.$fl_cust.'%')
                    ->orWhere('mmp.phone','ilike','%'.$fl_cust.'%')
                    ->orWhere('mmp.phone','ilike','%'.$fl_cust.'%');
            });

            if($fl_dest != "") $list_order_checkout_detail->where(function($query) use ($fl_dest){
                $query->where('toc.delivery_address','ilike','%'.$fl_dest.'%')
                    ->orWhere('toc.delivery_postal_code','ilike','%'.$fl_dest.'%');
            });

            if ($fl_price[0] != "") {
                if (array_key_exists(1, $fl_price)) {
                    if($fl_price[1] != ""){
                        $list_order_checkout_detail->whereBetween('tr_order_checkout_detail.total_price', [(int) str_replace(".","",$fl_price[0]), (int) str_replace(".","",$fl_price[1])]);
                    }else{
                        $list_order_checkout_detail->where('tr_order_checkout_detail.total_price', '>=', (int) str_replace(".","",$fl_price[0]));
                    }
                } else {
                    $list_order_checkout_detail->where('tr_order_checkout_detail.total_price', '>=', (int) str_replace(".","",$fl_price[0]));
                }
            }

            if ($fl_date[0] != "" && $fl_date[1] != "") {
                $list_order_checkout_detail->whereDate('tr_order_checkout_detail.updated_at', '>=', $fl_date[0]);
                $list_order_checkout_detail->whereDate('tr_order_checkout_detail.updated_at','<=',$fl_date[1]);
            }
            $filter_count = $list_order_checkout_detail->count();
            $list_order_checkout_detail =  $list_order_checkout_detail->orderBy('id','desc')->skip($page_start)->take($page_length)->get()->toArray();

            if(count($list_order_checkout_detail) < 1) $total_count = 0;
            //dd($list_order_checkout_detail);
            $list_order_checkout_detail = array_map(function($data) use($req_status){
                $data['id'] = Crypt::encryptString($data['id']);
                if($req_status == "COMPLAINT"){
                    $data['status'] = "Transaksi Dikomplain";
                }else{
                    $data['status'] = $this->status[$data['status']];
                }
                $data['total_price'] = "Rp. ".number_format($data['total_price'],0,",",".");
                $data['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s',strtotime($data['created_at'])), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');
                return $data;
            },$list_order_checkout_detail);

            $json_data = array(
                'draw' => $draw,
                'recordsFiltered' => $filter_count,
                'recordsTotal' => $total_count,
                'data' => $list_order_checkout_detail
            );
            //dd($json_data);
            return json_encode($json_data);
        }
    }

    public function download_bast(Request $request, $id){

        setlocale(LC_ALL, 'IND');
        $bast = OrderBast::where('id',Crypt::decryptString($id))->with(['bast_detail','customer'])->first();
        //dd($bast);
        $date = Carbon::parse($bast->created_at);
        $bast->idn_created_date = $date;
        $this->data['bast'] = $bast;
        //dd($data['bast']);
        return view('pages/order/pdf/bast',$this->data);
    }

    public function download_po(Request $request, $id){
        $this->data['order'] = OrderCheckoutDetail::where('id',Crypt::decryptString($id))
            ->with(['order_item','customer','vendor','customer.school','order_item.product','order','order.district','order.region','vendor.region','vendor.district','vendor.region'])->first();
        //dd($this->data['order']);
        return view('pages/order/pdf/purchase_order',$this->data);
    }

    public function download_invoice(Request $request, $id){
        $web_config = Config::where('config_name','BANK_NAME')->orWhere('config_name','BANK_NUMBER')->get();
        $config = array();
        foreach($web_config as $row){
            $config[$row->config_name] = $row->config_value;
        }
        $this->data['config'] = $config;
        $this->data['order'] = OrderCheckoutDetail::where('id',Crypt::decryptString($id))
            ->with(['order_item','customer','vendor','customer.school','order','order.district','order.region','vendor.region','vendor.district','invoice',
                'bast'=>function($query){
                    $query->orderBy('id','desc')->first();
                },
                'bast.bast_detail',
                'bast.bast_detail.product'])->first();

        foreach($this->data['order']->bast[0]->bast_detail as $item){
            $item->product->packet_description = str_replace("\n","<br>",$item->product->packet_description);
        }
        //dd($this->data['order']);
        return view('pages/order/pdf/invoice',$this->data);
    }

    public function download_label_jne(Request $request, $id){
        $this->data['order'] = OrderCheckoutDetail::where('id',Crypt::decryptString($id))->with(
            'order_delivery','order','shipment_service','shipment_service.provider',
            'customer','vendor','customer.school','vendor.district','vendor.region',
            'order.region','order.district','customer.school','order_item','order_item.product'
        )->first();
        return view('pages/order/pdf/label',$this->data);
    }

    public function request_awb(Request $request){
        $id = Crypt::decryptString($request->input('id'));
        $data = array('status'=>'fail','message'=>'Maaf mohon untuk mencoba kembali beberapa saat kembali');
        $now = date('Y-m-d H:i:s');

        $delivery = OrderDelivery::where('order_detail_id',$id)->first();

        $payload = array(
            'delivery_id' => $delivery->id,
            'request_timestamp' => $now
        );
        //dd(strval($delivery->id));
        //dd(Cookie::get('request_awb'));
        if(Cookie::get('request_awb_'.$delivery->id) == null){
            BroadcastHelper::send_awb($request,$payload);
            $data['status'] = 'success';
            $data['message'] = 'Anda telah berhasil mengajukan nomor resi';
            Cookie::queue('request_awb_'.$delivery->id,true,5);
        }

        $selected_order_checkout_detail = OrderCheckoutDetail::where('id',$id)
            ->with([
                'shipment_service','shipment_service.provider',
                'order_delivery'=>function($query){
                    $query->orderBy('id','desc');
                },
                'bast'=>function($query){
                    $query->orderBy('id','asc');
                },
                'update_order'
            ])->first();

        if($selected_order_checkout_detail->expired_cancel_at != null){
            $date1 = strtotime($selected_order_checkout_detail->expired_cancel_at);
            $date2 = strtotime(date('Y-m-d H:i:s'));

            $diff = floor(($date1 - $date2)/86400);
            $selected_order_checkout_detail->expired_date = $diff;
        }

        if($selected_order_checkout_detail->cancel_status != null){$selected_order_checkout_detail->id_cancel_status = $this->cancel_status[$selected_order_checkout_detail->cancel_status];}

        $selected_order_checkout_detail->id_order_status = $this->status[$selected_order_checkout_detail->status];

        if($selected_order_checkout_detail->order_delivery != null){
            if($selected_order_checkout_detail->order_delivery->no_resi != ""){
                Cookie::queue(Cookie::forget('request_awb_'.$delivery->id));
                $data['status'] = 'success';
                $data['message'] = 'Anda telah mendapatkan nomor resi';
            }
            $data['no_resi'] = $selected_order_checkout_detail->order_delivery->no_resi;
        }
        //dd($selected_order_checkout_detail);
        $this->data['selected'] = $selected_order_checkout_detail;
        $this->data['status_ship'] = $this->status_ship;
        $this->data['status_invoice'] = $this->status_invoice;
        $this->data['status_doc'] = $this->status_doc;
        $this->data['bast_status'] = $this->bast_status;
        $this->data['status_receive'] = $this->status_receive;
        $this->data['red_status'] = $this->red_status;
        $this->data['status_after_payment'] = $this->status_after_payment;
        $data['view'] = view('pages/order/summary',$this->data)->render();

        return $data;
    }
    public function update_status_order(Request $request){
        $id = Crypt::decryptString($request->input('id'));
        $status = $request->input('status');
        $no_resi = $request->input('no_resi');
        $note = $request->input('note');
        $delivery = $request->input('delivery');
        //dd($request->all());
        $data = array('status'=>'success','message'=>'');
        $now = date('Y-m-d H:i:s');
        $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');
        try{
            $order = OrderCheckoutDetail::where('id',$id)->with(['shipment_service','shipment_service.provider','customer','school'])->first();
            $template_name = "";
            $email_to = $order->customer->email;
            $order->updated_at = $now;
            $payload = array(
                'user_id' => $order->created_by,
                'school_id' => $order->school_id,
                'transaction_id' => $order->id
            );

            switch($status){
                case 'ORDER_CONFIRM' : {
                    $order->status = $status;
                    $order->seller_confirm_date = $now;
                    if($order->shipment_service->code == "PRIV") $order->delivery_fee = (int) str_replace('.','',$delivery);
                    $data['message'] = 'Transaksi telah berhasil diterima';
                    $payload['event_desc'] = 'Transaksi dikonfirmasi oleh '.auth()->user()->name;
                    $payload['sent'] = true;
                    $payload['note'] = 'Non Agregation data';
                    $payload['agregation_name'] = 'OrderConfirm';

                    $template_name = "CHECKOUT_WAITING_APPROVAL";
                    break;
                }
                case 'ORDER_REJECTED' : {
                    $order->status = $status;
                    $order->seller_confirm_date = $now;
                    $order->status_note = $note;
                    $data['message'] = 'Transaksi telah berhasil ditolak';

                    $payload['agregation_name'] = 'OrderRejected';
                    $payload['event_desc'] = 'Transaksi ditolak oleh Penyedia : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        "rejectedBy" => array(
                            'user' => strval(auth()->user()->id),
                            'merchant' => strval(auth()->user()->user_ref_id),
                        ),
                        'context' => "Pesanan transaksi ".$order->id." ditolak karena ".$note,
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp
                    ));

                    $template_name = "ORDER_REJECTED";
                    break;
                }
                case 'ORDER_PROCESSED' : {
                    $order->status = $status;
                    $data['message'] = 'Transaksi telah berhasil diproses';

                    $payload['agregation_name'] = 'OrderProcessed';
                    $payload['event_desc'] = 'Transaksi diproses oleh Penyedia : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        "processedBy" => array(
                            'user' => strval(auth()->user()->id),
                            'merchant' => strval(auth()->user()->user_ref_id),
                        ),
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp
                    ));

                    $template_name = "ORDER_PROCESSED";
                    break;
                }
                case 'InfoUpdateApproved' : {
                    //Update Order Update
                    $header = OrderUpdate::where('checkout_detail_id',$id)->first();
                    $header->status = "APPROVED";
                    $header->updated_at = $now;
                    $header->updated_by = auth()->user()->id;
                    $header->save();

                    $order->total_price = $header->total_price;
                    $order->delivery_fee = $header->delivery_fee;
                    $order->total_net_weight = $header->total_net_weight;
                    $order->total_gross_weight = $header->total_gross_weight;
                    $order->total_tax = $header->total_tax;
                    $order->total_tax_vendor = $header->total_tax_vendor;
                    $order->total_tax_buyer = $header->total_tax_buyer;

                    //Update Order Update Detail
                    $detail = OrderUpdateDetail::where('order_update_id',$header->id)->get();
                    foreach($detail as $item){
                        $update_item = OrderCheckoutItem::where([
                            'checkout_detail_id' => $id,
                            'product_id' => $item->product_id
                        ])->first();
                        $update_item->quantity = $item->quantity;
                        $update_item->updated_at = $now;
                        $update_item->save();
                    }

                    //Set the payload agregation
                    $data['message'] = 'Perubahan transaksi telah disetujui';
                    $payload['agregation_name'] = $status;
                    $payload['event_desc'] = 'Pembaharuan transaksi diterima oleh Penyedia : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        "approvedBy" => array(
                            'merchant' => array(
                                'user' => strval(auth()->user()->id),
                                'merchant' => strval(auth()->user()->user_ref_id),
                            ),
                        ),
                        "context" => "Kuantitas pesanan ".$header->checkout_detail_id." telah diubah karena ".$header->status_note,
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp
                    ));
                    break;
                }
                case 'InfoUpdateRejected' : {
                    //Update Order Update
                    $header = OrderUpdate::where('checkout_detail_id',$id)->first();
                    $header->status = "REJECTED";
                    $header->status_note = $note;
                    $header->updated_at = $now;
                    $header->updated_by = auth()->user()->id;
                    $header->save();

                    //Set the payload agregation
                    $data['message'] = 'Perubahan transaksi telah ditolak';
                    $payload['agregation_name'] = $status;
                    $payload['event_desc'] = 'Pembaharuan transaksi ditolak oleh Penyedia : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        "rejectedBy" => array(
                            'merchant' => array(
                                'user' => strval(auth()->user()->id),
                                'merchant' => strval(auth()->user()->user_ref_id),
                            ),
                        ),
                        "context" => "Kuantitas pesanan ".$header->checkout_detail_id." telah dibatalkan karena ".$note,
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp
                    ));
                    break;
                }
                case 'ORDER_SHIPPED' : {
                    $order->status = $status;
                    $data['message'] = 'Transaksi telah dikirim';

                    $payload['agregation_name'] = 'OrderShipped';
                    $payload['event_desc'] = 'Transaksi dikirim oleh Penyedia : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        "shippedBy" => array(
                            'user' => strval(auth()->user()->id),
                            'merchant' => strval(auth()->user()->user_ref_id),
                        ),
                        "shippingInfo" => array(
                            "courier" => array(
                                "id" => strval($order->shipment_service->provider->id),
                                'name' => strval($order->shipment_service->provider->name)
                            ),
                            "method" => $order->shipment_service->code
                        ),
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp
                    ));
                    $template_name = "ORDER_SHIPPED";

                    if($order->shipment_service->code == "PRIV"){
                        $delivery = OrderDelivery::where('order_detail_id',$id)->first();
                        $delivery->no_resi = $no_resi;
                        $delivery->total_price = $order->delivery_fee;
                        $delivery->total_weight = $order->total_net_weight;
                        $delivery->dispatch_date = $now;
                        $delivery->status = "ON_DELIVERY";
                        $delivery->status_note = $order->status_note;
                        $delivery->status_date = $now;
                        $delivery->updated_at = $now;
                        $delivery->updated_by = auth()->user()->id;
                        $delivery->save();
                    }else{
                        $delivery = OrderDelivery::where('order_detail_id',$id)->first();
                        $delivery->status = "ON_DELIVERY";
                        $delivery->status_date = $now;
                        $delivery->updated_at = $now;
                        $delivery->updated_by = auth()->user()->id;
                        $delivery->dispatch_date = $now;
                        $delivery->save();
                    }
                    break;
                }
                case 'CANCELLATION_APPROVED' : {
                    $order->status = "CANCELED";
                    $data['message'] = "Pengajuan pembatalan telah berhasil diterima";
                    $order->cancel_status = $status;
                    $order->cancel_approved_at = $now;
                    $order->cancel_note_buyer = $note;

                    $payload['agregation_name'] = 'CancellationApproved';
                    $payload['event_desc'] = 'Transaksi telah dibatalkan oleh Penyedia : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        "approvedBy" => array(
                            'merchant' => array(
                                'user' => strval(auth()->user()->id),
                                'merchant' => strval(auth()->user()->user_ref_id),
                            ),
                        ),
                        'context' => "Pembatalan transaksi ".$order->id." telah dibatalkan ditolak karena ".$note,
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp
                    ));

                    $template_name = "ORDER_CANCELED";
                    break;
                }
                case 'CANCELLATION_REJECTED' : {
                    $data['message'] = "Pengajuan pembatalan telah berhasil ditolak";
                    $order->cancel_status = $status;
                    $order->cancel_approved_at = $now;
                    $order->cancel_note_buyer = $note;

                    $payload['agregation_name'] = 'CancellationRejected';
                    $payload['event_desc'] = 'Transaksi telah dibatalkan oleh Penyedia : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        "rejectedBy" => array(
                            'merchant' => array(
                                'user' => strval(auth()->user()->id),
                                'merchant' => strval(auth()->user()->user_ref_id),
                            ),
                        ),
                        'context' => "Pembatalan transaksi ".$order->id." telah dibatalkan ditolak karena ".$note,
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp
                    ));
                    $template_name = "";
                    break;
                }
                case 'BAST_SUBMITTED' : {
                    $data['message'] = "BAST telah diterima";
                    $order->status = $status;
                    $order_detail = OrderCheckoutDetail::where('id','=',$id)->with([
                            'order',
                            'bast'=>function($query){
                                $query->orderBy('id','desc')->first();
                            },
                            'bast.bast_detail',
                            'customer']
                    )->orderBy('id','desc')->first();
                    $invoice = new Invoice;
                    //dd($order_detail->bast[0]->id);
                    $invoice->checkout_id = $order_detail->order->id;
                    $invoice->checkout_detail_id = $order_detail->id;
                    $invoice->vendor_id = $order_detail->vendor_id;
                    $invoice->bast_id = $order_detail->bast[0]->id;
                    $invoice->invoice_no = str_replace("OD","INV",$order_detail->order_no);
                    $invoice->total_price_before_tax = $order_detail->bast[0]->total_price_before_tax;
                    $invoice->total_price_tax = $order_detail->bast[0]->total_price_tax_vendor + $order_detail->bast[0]->total_price_tax_buyer;
                    $invoice->total_price = $order_detail->bast[0]->total_price;
                    $invoice->delivery_fee = $order_detail->delivery_fee;
                    $invoice->total_penalty = $order_detail->bast[0]->penalty_amount;
                    $invoice->grant_total = (int) $order_detail->bast[0]->total_price_before_tax + (int) $order_detail->bast[0]->total_price_tax_vendor + (int) $order_detail->delivery_fee - (int) $order_detail->bast[0]->penalty_amount;
                    $invoice->created_at = $now;
                    $invoice->status = "CREATED";
                    $invoice->created_by = auth()->user()->user_ref_id;
                    $saved_invoice = $invoice->save();

                    $bast = OrderBast::where('checkout_detail_id',$id)->orderBy('id','desc')->first();
                    $bast->status = "SUBMITTED";
                    $bast->bast_type = "FINAL";
                    $bast->bast_no = str_replace("OD","BAST",$order_detail->order_no);
                    $bast->updated_at = $now;
                    $bast->seller_confirm_date = $now;
                    $bast->updated_by = auth()->user()->user_ref_id;
                    $saved_bast = $bast->save();

                    if($saved_invoice && $saved_bast){
                        $payload['agregation_name'] = 'BastGenerated';
                        $payload['event_desc'] = 'BAST transaksi telah diterima oleh Penyedia : '.auth()->user()->name;
                        $payload['data'] = json_encode(array(
                            "generatedBy" => array(
                                'user' => strval($order_detail->customer->pengguna_id),
                                'school' => strval($order_detail->school_id),
                                'role' => strval($order_detail->customer->peran_id),
                                'title' => strval($order_detail->customer->jabatan),
                            ),
                            'bast' => array(
                                "id" => strval($bast->id),
                                "number" => strval($bast->id),
                                "type" => strval($bast->bast_no)
                            ),
                            'context' => "Pembatalan transaksi ".$order->id." telah dibatalkan ditolak karena ".$note,
                            "occurredAt" => $timestamp,
                            "sentAt" => $timestamp
                        ));

                        $template_name = "BAST_SUBMITED";
                    }

                    break;
                }
                case 'BAST_REJECTED' : {
                    $data['message'] = "BAST telah ditolak";
                    $payload['event_desc'] = 'Bast ditolak oleh '.auth()->user()->name;
                    $payload['sent'] = true;
                    $payload['note'] = 'Non Agregation data';
                    $payload['agregation_name'] = 'BastRejected';

                    $order->status = $status;
                    $bast = OrderBast::where('checkout_detail_id',$id)->first();
                    $bast->updated_at = $now;
                    $bast->status = "REJECTED";
                    $bast->seller_confirm_date = $now;
                    $bast->updated_by = auth()->user()->id;
                    $saved_bast = $bast->save();

                    $template_name = "";
                    break;
                }
                case 'PAYMENT_COMPLAINED' : {
                    $data['message'] = "Berhasil mengajukan keluhan pembayaran";
                    $invoice = Invoice::where('checkout_detail_id',$id)->with('invoice_payment')->first();
                    $invoice->is_complained = true;
                    $invoice->complained_date = $now;
                    $invoice->complained_note = $note;
                    $invoice->save();

                    $payload['agregation_name'] = 'PaymentComplained';
                    $payload['event_desc'] = 'Pembayaran transaksi telah dikomplain : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        'payment' => "0",
                        'subject' => "Komplain pembayaran",
                        'context' => "Saya komplain terhadap order ".$order->id." karena ".$note,
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp,
                        "complainedPartyBy" => array(
                            'merchant' => array(
                                'user' => strval(auth()->user()->id),
                                'merchant' => strval(auth()->user()->user_ref_id),
                            ),
                        )
                    ));
                    $email_to = "cs@klikmro.com";
                    $template_name = "PAYMENT_COMPLAINED";
                    break;
                }
                case 'COMPLETED' : {
                    $data['message'] = "Pembayaran sudah diterima";
                    $order->status = $status;

                    $payment = InvoicePayment::where(['checkout_detail_id' => $id, 'status' => 'PAID'])->first();
                    $payload['agregation_name'] = 'PaymentSettled';
                    $payload['event_desc'] = 'Pembayaran sudah diterima oleh Penyedia : '.auth()->user()->name;
                    $payload['data'] = json_encode(array(
                        "payment" => "0",
                        "occurredAt" => $timestamp,
                        "sentAt" => $timestamp
                    ));

                    $template_name = "PAYMENT_RECEIVED";
                    break;
                }
            }

            $saved_order = $order->save();
            if($saved_order){
                BroadcastHelper::send($request,$payload);
                if($template_name != ""){
                    $template = EmailTemplate::where('name',$template_name)->first();
                    $payload_email = array(
                        "template_name" => $template_name,
                        "email_to" => $email_to,
                        "email_atribute" => array(
                            array('name' => 'vendor_name', 'value' => auth()->user()->name),
                            array('name' => 'mitra_name', 'value' => 'Klikmro'),
                            array('name' => 'buyer_name', 'value' => $order->customer->name),
                            array('name' => 'kepsek_name', 'value' => $order->school->nama_kepsek),
                            array('name' => 'school', 'value' => $order->school->school_name),
                            array('name' => 'order_no', 'value' => $order->order_no),
                            array('name' => 'link', 'value' => $template->ref_link)
                        )
                    );
                    MailHelper::send($payload_email);
                }
            }

            $selected_order_checkout_detail = OrderCheckoutDetail::where('id',$id)
                ->with([
                    'shipment_service','shipment_service.provider',
                    'order_delivery'=>function($query){
                        $query->orderBy('id','desc');
                    },
                    'bast'=>function($query){
                        $query->orderBy('id','asc');
                    },
                    'update_order','invoice'
                ])->first();
            if($selected_order_checkout_detail->expired_cancel_at != null){
                $date1 = strtotime($selected_order_checkout_detail->expired_cancel_at);
                $date2 = strtotime(date('Y-m-d H:i:s'));

                $diff = floor(($date1 - $date2)/86400);
                $selected_order_checkout_detail->expired_date = $diff;
            }

            if($selected_order_checkout_detail->cancel_status != null){$selected_order_checkout_detail->id_cancel_status = $this->cancel_status[$selected_order_checkout_detail->cancel_status];}

            $selected_order_checkout_detail->id_order_status = $this->status[$selected_order_checkout_detail->status];

            if($selected_order_checkout_detail->order_delivery != null){
                $this->data['no_resi'] = $selected_order_checkout_detail->order_delivery->no_resi;
            }

            $selected_order_checkout_detail->status_update_order = null;
            if($selected_order_checkout_detail->update_order != null) {
                $selected_order_checkout_detail->status_update_order = $selected_order_checkout_detail->update_order->status;
            }

            $selected_order_checkout_detail->payment_diff = null;
            $selected_order_checkout_detail->is_complained = false;
            if(count($selected_order_checkout_detail->bast) > 0 ){
                $date = Carbon::parse($selected_order_checkout_detail->bast[count($selected_order_checkout_detail->bast)-1]->seller_confirm_date);
                $now = Carbon::now();
                $selected_order_checkout_detail->payment_diff = $date->diffInDays($now);
            }
            if($selected_order_checkout_detail->invoice != null){
                if($selected_order_checkout_detail->invoice->is_complained == true) $selected_order_checkout_detail->is_complained = true;
            }

            //dd($selected_order_checkout_detail);
            $this->data['selected'] = $selected_order_checkout_detail;
            $this->data['status_ship'] = $this->status_ship;
            $this->data['status_invoice'] = $this->status_invoice;
            //$this->data['status_doc'] = $this->status_doc;
            $this->data['bast_status'] = $this->bast_status;
            //$this->data['status_receive'] = $this->status_receive;
            $this->data['red_status'] = $this->red_status;
            $this->data['status_after_payment'] = $this->status_after_payment;
            $data['view'] = view('pages/order/summary',$this->data)->render();

        }catch(Exception $err){
            $data['status'] = 'error';
            $data['message'] = $err;
        }

        return $data;
    }

    public function api_upload_tax(Request $request){
        $output = array('status'=>false, 'message'=>'Gagal');

        if($request->ajax()) {
            $param = $request->all();
            $order_id = Crypt::decryptString($param['id']);
            //dd($param['files']);
            //dd($param['files']->getClientOriginalName());

            if(array_key_exists('files', $param) && !empty($param['files'])){
                try{
                    DB::beginTransaction();
                    $disk = Storage::disk('gcs');
                    $extension = explode('.', $param['files']->getClientOriginalName())[1];
                    $filename_unique    = 'tax/'.md5(date("Ym")).'/'.sha1(time()).'-'.$order_id;
                    $filename_original = $filename_unique.'.'.$extension;

                    //$replace = substr($param['files'], 0, strpos($param['files'],',')+1);
                    //$image = str_replace($replace,'',$param['files']);
                    //$image = str_replace(' ','+',$image);

                    $disk->put($filename_original,File::get($param['files']));

                    $order = OrderCheckoutDetail::where('id','=',$order_id)->first();
                    $order->tax_document = @$filename_original;
                    $order->updated_at = date('Y-m-d H:i:s');
                    $order->save();

                    $this->set_data_summary($order_id);
                    $view = view('pages/order/summary',$this->data)->render();
                    $output = array('status'=>true, 'message'=> 'File telah berhasil diupload', 'view' => $view);

                    DB::commit();
                }catch(Exception $e){
                    DB::rollBack();
                    return $output;
                }
            }
        }
        return $output;
    }

    public function set_data_summary($id){
        $selected_order_checkout_detail = OrderCheckoutDetail::where('id',$id)
            ->with([
                'shipment_service','shipment_service.provider',
                'order_delivery'=>function($query){
                    $query->orderBy('id','desc');
                },
                'bast'=>function($query){
                    $query->orderBy('id','asc');
                },
                'update_order','invoice'
            ])->first();
        if($selected_order_checkout_detail->expired_cancel_at != null){
            $date1 = strtotime($selected_order_checkout_detail->expired_cancel_at);
            $date2 = strtotime(date('Y-m-d H:i:s'));

            $diff = floor(($date1 - $date2)/86400);
            $selected_order_checkout_detail->expired_date = $diff;
        }

        if($selected_order_checkout_detail->cancel_status != null){$selected_order_checkout_detail->id_cancel_status = $this->cancel_status[$selected_order_checkout_detail->cancel_status];}

        $selected_order_checkout_detail->id_order_status = $this->status[$selected_order_checkout_detail->status];

        if($selected_order_checkout_detail->order_delivery != null){
            $this->data['no_resi'] = $selected_order_checkout_detail->order_delivery->no_resi;
        }

        $selected_order_checkout_detail->status_update_order = null;
        if($selected_order_checkout_detail->update_order != null) {
            $selected_order_checkout_detail->status_update_order = $selected_order_checkout_detail->update_order->status;
        }

        $selected_order_checkout_detail->payment_diff = null;
        $selected_order_checkout_detail->is_complained = false;
        if(count($selected_order_checkout_detail->bast) > 0 ){
            $date = Carbon::parse($selected_order_checkout_detail->bast[count($selected_order_checkout_detail->bast)-1]->seller_confirm_date);
            $now = Carbon::now();
            $selected_order_checkout_detail->payment_diff = $date->diffInDays($now);
        }
        if($selected_order_checkout_detail->invoice != null){
            if($selected_order_checkout_detail->invoice->is_complained == true) $selected_order_checkout_detail->is_complained = true;
        }

        //dd($selected_order_checkout_detail);
        $this->data['selected'] = $selected_order_checkout_detail;
        $this->data['status_ship'] = $this->status_ship;
        $this->data['status_invoice'] = $this->status_invoice;
        //$this->data['status_doc'] = $this->status_doc;
        $this->data['bast_status'] = $this->bast_status;
        //$this->data['status_receive'] = $this->status_receive;
        $this->data['red_status'] = $this->red_status;
        $this->data['status_after_payment'] = $this->status_after_payment;
    }

    public function print_po(Request $request, $hash){
        $explode_hash = explode("-",$hash);
        if(count($explode_hash)===2){
            $private_key = $explode_hash[0];
            $public_key = $explode_hash[1];
            $public_key_base64_decode = base64_decode($public_key);
            $public_key_value_explode = explode(",",$public_key_base64_decode);
            if(count($explode_hash)===2) {
                $order_id = $public_key_value_explode[0];
                $timestamp = $public_key_value_explode[1];
                if($private_key===hash('sha256',$order_id.$timestamp.env("PRINT_PRIVATE_KEY"))){
                    $interval  = abs(strtotime("now") - strtotime($timestamp));
                    $minutes   = round($interval / 60);
                    if($minutes<=env("PRINT_MINUTES_EXPIRED")){
                        $order = OrderCheckoutDetail::where('id',$order_id)->first();
                        $hash_order_id = Crypt::encryptString($order_id);
                        if($order){
                            return $this->download_po($request, $hash_order_id);
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

    public function print_bast(Request $request, $hash){
        $explode_hash = explode("-",$hash);
        //dd($explode_hash);
        if(count($explode_hash)===2){
            $private_key = $explode_hash[0];
            $public_key = $explode_hash[1];
            $public_key_base64_decode = base64_decode($public_key);
            $public_key_value_explode = explode(",",$public_key_base64_decode);
            if(count($explode_hash)===2) {
                $bast_id = $public_key_value_explode[0];
                $timestamp = $public_key_value_explode[1];
                //dd($bast_id);
                if($private_key===hash('sha256',$bast_id.$timestamp.env("PRINT_PRIVATE_KEY"))){
                    $interval  = abs(strtotime("now") - strtotime($timestamp));
                    $minutes   = round($interval / 60);
                    if($minutes<=env("PRINT_MINUTES_EXPIRED")){
                        $bast = OrderBast::where('id',$bast_id)->first();
                        $hash_order_id = Crypt::encryptString($bast_id);
                        if($bast){
                            return $this->download_bast($request, $hash_order_id);
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

    public function print_invoice(Request $request, $hash){
        $explode_hash = explode("-",$hash);
        if(count($explode_hash)===2){
            $private_key = $explode_hash[0];
            $public_key = $explode_hash[1];
            $public_key_base64_decode = base64_decode($public_key);
            $public_key_value_explode = explode(",",$public_key_base64_decode);
            if(count($explode_hash)===2) {
                $invoice_id = $public_key_value_explode[0];
                $timestamp = $public_key_value_explode[1];
                if($private_key===hash('sha256',$invoice_id.$timestamp.env("PRINT_PRIVATE_KEY"))){
                    $interval  = abs(strtotime("now") - strtotime($timestamp));
                    $minutes   = round($interval / 60);
                    if($minutes<=env("PRINT_MINUTES_EXPIRED")){
                        $invoice = OrderCheckoutDetail::where('id',$invoice_id)->first();
                        $hash_order_id = Crypt::encryptString($invoice_id);
                        if($invoice){
                            return $this->download_invoice($request, $hash_order_id);
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
