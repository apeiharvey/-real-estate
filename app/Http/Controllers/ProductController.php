<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\ProductUpload;
use App\Models\ProductUploadResult;
use App\Models\SelectionList;
use App\Models\Category;
use App\Models\ViewProductCategoryTags;
use App\Models\OrderCheckoutItem;
use App\Models\LogisticProvider;
use Carbon\Carbon;
use DB;
use App\Helpers\BroadcastHelper;

class ProductController extends Controller
{
    public function __construct()
	{
		parent::__construct();
    }

    public function index(){
        $list_item_status = SelectionList::where('selection_type','ITEM_STATUS')->get();
        $list_item_type = SelectionList::where('selection_type','ITEM_TYPE')->get();
        $list_category_class = SelectionList::where('selection_type','CATEGORY_CLASS')->get();

        $this->data['list_item_status'] = $list_item_status;
        $this->data['list_item_type'] = $list_item_type;
        $this->data['list_category_class'] = $list_category_class;
        // dd($this->data);
        return view('pages/product/index',$this->data);
    }

    public function show_detail($id){
        $selected_product = Product::where('id','=',$id)->first();

        if(!$selected_product){
            return view('pages/error/error_blue_breaking_link', array(
                'error' => array(
                    'title' => 'Ooops...',
                    'message' => 'Produk tidak ditemukan'
                )
            ));
        }else if(isset($selected_product->vendor_id) && $selected_product->vendor_id != auth()->user()->user_ref_id){
            return view('pages/error/error_blue_breaking_link', array(
                'error' => array(
                    'title' => 'Ooops',
                    'message' => 'Akun vendor/penyedia Anda tidak berwenang untuk mengedit produk ini'
                )
            ));
        }else{
            $selected_product   = Product::where('id','=',$id)
                                    ->with('images')
                                    ->with('stock')
                                    ->with('price')
                                    ->with('category')
                                    ->first();
            $stock_sum = ProductStock::where('product_id', $id)->sum('stock');

            $list_category      = Category::where('is_enabled','=',true)->where('parent_id','=',null)->get();
            $list_class_level   = SelectionList::where('selection_type','CLASS_LEVEL')->get();
            $list_item_condition   = SelectionList::where('selection_type','ITEM_CONDITION')->get();
            $list_item_type   = SelectionList::where('selection_type','ITEM_TYPE')->get();
            $list_uom = SelectionList::where('selection_type','UOM')->get();

            $this->data['list_category'] = $list_category;
            $this->data['list_class_level'] = $list_class_level;
            $this->data['list_item_condition'] = $list_item_condition;
            $this->data['list_item_type'] = $list_item_type;
            $this->data['list_uom'] = $list_uom;

            $this->data['selected'] = $selected_product;
            $this->data['stock_sum'] = $stock_sum;

            return view('pages/product/detail',$this->data);
        }
    }

    public function show_form_add(){
        $list_category      = Category::where('is_enabled','=',true)->where('parent_id','=',null)->get();
        $list_class_level   = SelectionList::where('selection_type','CLASS_LEVEL')->get();
        $list_item_condition   = SelectionList::where('selection_type','ITEM_CONDITION')->get();
        $list_item_type   = SelectionList::where('selection_type','ITEM_TYPE')->get();
        $list_uom = SelectionList::where('selection_type','UOM')->get();

        $this->data['list_category'] = $list_category;
        $this->data['list_class_level'] = $list_class_level;
        $this->data['list_item_condition'] = $list_item_condition;
        $this->data['list_item_type'] = $list_item_type;
        $this->data['list_uom'] = $list_uom;

        return view('pages/product/form_add',$this->data);
    }

    public function show_form_upload(){
        return view('pages/product/form_add_bulk',$this->data);
    }

    public function get_shipping_options(){
        $shipping_options = array();
        $providers = LogisticProvider::where('is_enabled',true)->get();
        foreach ($providers as $obj){
            array_push($shipping_options, array(
                'courier' => array(
                    "id" => (string)$obj->id,
                    "name" => $obj->name,
                ),
                "method" => $obj->provider_code
            ));
        }
        return $shipping_options;
    }

    public function api_get_list(Request $request)
    {
        if($request->ajax()) {
            // DB::enableQueryLog();
            $list_product = Product::select(DB::raw(with(new Product)->getTable().'.*'), DB::raw('SUM('.with(new ProductStock)->getTable().'.stock) as sum_stock'))
                            ->leftJoin(with(new ProductStock)->getTable(), with(new Product)->getTable().'.id', '=', with(new ProductStock)->getTable().'.product_id')
                            ->with(['images' => function($q){
                                $q->where('is_enabled', '=', true)->where('type', '=', 'image');
                            }])
                            // ->with('stock')
                            ->with('price')
                            ->with('category')
                            ->where('vendor_id', '=', auth()->user()->user_ref_id)
                            // ->orderBy(with(new Product)->getTable().'.id', 'DESC')
                            ->orderBy('updated_at', 'DESC')
                            ->orderBy('created_at', 'DESC')
                            ->groupBy(with(new Product)->getTable().'.id')
                            ->get()->toArray();
            // dd(DB::getQueryLog());
            // dd($list_product);
            $json_data = array(
                'meta' => array(
                    "page" => 1,
                    "pages" => 1,
                    "perpage" => -1,
                    "total" => count($list_product),
                    "sort" => "desc",
                    "field" => "updated_at",
                ),
                'data' => $list_product
            );

            return json_encode($json_data);
        }
    }

    public function api_get_bulk_history(Request $request)
    {
        if($request->ajax()) {
            $list_product = ProductUpload::where('vendor_id', '=', auth()->user()->user_ref_id)
                            ->orderBy('created_at', 'DESC')
                            ->get()->toArray();

            $json_data = array(
                'meta' => array(
                    "page" => 1,
                    "pages" => 1,
                    "perpage" => -1,
                    "total" => count($list_product),
                    "sort" => "asc",
                    "field" => "id",
                ),
                'data' => $list_product
            );

            return json_encode($json_data);
        }
    }

    public function api_get_bulk_history_detail(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();

            if(array_key_exists('upload_id',$param)){
                $list_product = ProductUploadResult::where('upload_id', '=', $param['upload_id'])
                                ->orderBy('created_at', 'ASC')
                                ->get()->toArray();

                $json_data = array(
                    'meta' => array(
                        "page" => 1,
                        "pages" => 1,
                        "perpage" => -1,
                        "total" => count($list_product),
                        "sort" => "asc",
                        "field" => "id",
                    ),
                    'data' => $list_product
                );

                return json_encode($json_data);
            }
        }
    }

    public function api_get_category(Request $request)
    {
        if($request->ajax()) {

            $param = $request->all();
            $data = array();

            if(array_key_exists('parent_id',$param)){ // get list category
                $data = Category::where('parent_id',$param['parent_id'])->where('is_enabled',true)->get()->toArray();
            }else if(array_key_exists('child_id',$param)){ // get category related to child
                $i = 0;
                $selected[$i] = Category::where('id',$param['child_id'])->first()->toArray();
                while($selected[$i] != null){
                    $i++;
                    $selected[$i] = Category::where('id',$selected[$i-1]['parent_id'])->first();
                    if($selected[$i]){
                        $selected[$i]->toArray();
                    }
                }

                $data = array_filter($selected); // get rid of the empty
            }else{
                return array('status'=>false,'detail'=>'permintaan tidak valid, coba lagi');
            }

            return array('status'=>true,'detail'=>$data);
        }

    }

    public function api_get_price(Request $request)
    {
        if($request->ajax()) {

            $param = $request->all();
            $data = array();

            if(array_key_exists('product_id',$param)){
                $data = ProductPrice::where('product_id',$param['product_id'])->where('is_enabled',true)->orderBy('quantity_type','ASC')->get();
            }else{
                return array('status'=>false,'detail'=>'permintaan tidak valid, coba lagi');
            }

            return array('status'=>true,'detail'=>$data);
        }

    }



    public function api_get_list_book(Request $request)
    {
        // dump($request->all());
        $param = $request->all();
        $method = 'GET';
        $challenge = date('YmdHis');
        $token = hash('sha256',$challenge.env("PRINT_PRIVATE_KEY"));


        $data = array(
            'page'=>@$param['pagination']['page'],
            'search'=>urlencode(@$param['search']),
            'challenge'=>$challenge,
            'token'=>$token,

        );
        $url = env('API_BASE_URL').'/master/book';

        $result = $this->callAPI($method, $url, $data);
        $readResult = json_decode($result, true);

        if($readResult && array_key_exists('data',$readResult)){
            $json_data = array(
                'meta' => array(
                    "page" => @$param['pagination']['page'],
                    "pages" => intval($readResult['data']['pagination']['total_items']/10),
                    "perpage" => count($readResult['data']['data']),
                    "total" => $readResult['data']['pagination']['total_items'],
                    "sort" => "asc",
                    "field" => "id",
                ),
                'data' => $readResult['data']['data']
            );
        }else{
            $json_data = array(
                'meta' => array(
                    "page" => 1,
                    "pages" => 1,
                    "perpage" => -1,
                    "total" => 0,
                    "sort" => "asc",
                    "field" => "id",
                ),
                'data' => null
            );
        }
        return json_encode($json_data);

        // return $this->templateReturn($result, $method, $url, $data); // return using template
    }

    public function mapping_wholesaler_price($array){
        $array_new = array(); $i = 0;
        asort($array['min_quantity']);
        foreach ($array['min_quantity'] as $key => $value) {
            $array_new['min_quantity'][$i] = $value;
            $array_new['price'][$i] = $array['price'][$key];
            $i++;
        }
        return $array_new;
    }

    public function currency_to_float($str){
        // return floatval(preg_replace('/[^\d\.]/', '', $str));
        return floatval(str_replace(".",'',$str));
    }

    public function api_store_item(Request $request)
    {
        if($request->ajax()) {
            // $milestone = array();
            $param = $request->all();
            $msg = 'menambahkan produk baru';
            $date_now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');
            // dd($param);

            // try {
                \DB::beginTransaction();
                // DB::enableQueryLog();
                // date_default_timezone_set('Asia/Jakarta');

                // ----------------------------------------------------------[1.] PRODUCT
                $itemInfo = array();
                $priceOptions = array();
                $productModel = new Product;
                $productModel->vendor_id =  auth()->user()->user_ref_id;
                $productModel->created_by =  auth()->user()->id;
                $productModel->created_at =  date('Y-m-d H:i:s');
                $productModel->is_enabled =  true;
                $productModel->name = $itemInfo['name'] = @$param['name'];
                $productModel->description = $itemInfo['description'] =  @$param['description'];
                $productModel->packet_description = (@$param['is_packet'] ? @$param['packet_description'] : '');
                $productModel->item_status_id = $itemInfo['itemStatus'] = 2;
                $productModel->item_status_text = 'TIDAK TAYANG';
                $productModel->item_type_id = $itemInfo['itemType'] = @$param['item_type_id'];
                // $productModel->category_key = @strtoupper($param['category_key']);
                if(array_key_exists('category_id',$param)){
                    $productModel->category_id = $itemInfo['category'] = $param['category_id'][max(array_keys($param['category_id']))];
                    if($productModel->category_id){
                        $related_categories = ViewProductCategoryTags::where('id',$productModel->category_id)->first();
                        if($related_categories){
                            $productModel->main_category_key = $related_categories->category_key;
                            $productModel->category_key = $related_categories->category_keys;
                        }
                    }
                }
                if($param['item_type_id'] == 1 && array_key_exists('puskurbuk_id',$param) && $param['puskurbuk_id']){
                    $productModel->puskurbuk_id = $itemInfo['book'] = @$param['puskurbuk_id'];
                    $productModel->puskurbuk_isbn = @$param['puskurbuk_isbn'];
                    $productModel->puskurbuk_nuib = @$param['puskurbuk_nuib'];
                    $itemInfo['isKemdikbud'] = true;
                }else{
                    $itemInfo['isKemdikbud'] = false;
                }
                $productModel->class_code = (@$param['class_code']?implode(",",$param['class_code']):'');
                $productModel->brand = $itemInfo['brand'] = @$param['brand'];
                $productModel->listing_price = $priceOptions['itemPrice']['price'] = $this->currency_to_float(@$param['listing_price']);
                $productModel->sales_uom = @$param['sales_uom'];
                $productModel->sku = @$param['sku'];
                $productModel->gross_weight = $itemInfo['weight'] =  @$param['gross_weight'];
                $productModel->net_weight = @$param['net_weight'];
                $productModel->dimension_length = @$param['dimension_length'];
                $productModel->dimension_width = @$param['dimension_width'];
                $productModel->dimension_height = @$param['dimension_height'];
                $productModel->item_condition_type_id = $itemInfo['condition'] = @$param['item_condition_type_id'];
                $productModel->is_umkm = $itemInfo['isUMKM'] = @$param['is_umkm']??false;
                $productModel->made_in_indonesia = $itemInfo['isMadeInIndonesia'] = @$param['made_in_indonesia']??true;
                // $productModel->is_kemendikbud = $itemInfo['isKemdikbud'] = @$param['is_kemendikbud']??0;
                $productModel->is_every_zone_same_price = @$param['is_every_zone_same_price']??0;
                $productModel->po_duration = @$param['po_duration'];
                $productModel->est_delivery = @$param['est_delivery'];
                $productModel->warranty_days = $itemInfo['warranty'] = @$param['warranty_days'];
                $productModel->supplier_code = @$param['supplier_code'];
                $productModel->need_approval = 1;
                $productModel->save();
                $itemInfo['isPreorder'] = ($productModel->po_duration > 0?true:false);
                $itemInfo['pageUrl'] = env('APP_URL').'/detail/'.$productModel->id;
                $priceOptions['itemPrice']['itemMinQuantity'] = 1;
                $priceOptions['itemPrice']['itemPriceType'] = 'PRICE_RETAIL';

                // ----------------------------------------------------------[2.] PRICE
                $productPriceModel = array();
                $purchaseType = array('retail','wholesaler');
                $zone_limit = 5;

                $zone_counter = 1; // start from 1
                if(array_key_exists($purchaseType[1], $param['price']) && !empty($param['price'][$purchaseType[1]])){
                    $productPriceModel[$purchaseType[1]] = array();
                    $maxPriceInZone = array();

                    for($a = 1;$a <= $zone_limit;$a++){
                        if((array_key_exists('is_every_zone_same_price',$param) && $param['is_every_zone_same_price'] == 1 &&
                            array_key_exists($zone_counter,$param['price'][$purchaseType[1]])) || array_key_exists($a,$param['price'][$purchaseType[1]])
                        ){
                            $param['price'][$purchaseType[1]][$zone_counter] = $this->mapping_wholesaler_price($param['price'][$purchaseType[1]][$zone_counter]);

                            for($i=0;$i<sizeof($param['price'][$purchaseType[1]][$zone_counter]['price']);$i++) {
                                $productPriceModel[$purchaseType[1]][$a][$i] = new ProductPrice;
                                $productPriceModel[$purchaseType[1]][$a][$i]->product_id = $productModel->id;
                                $productPriceModel[$purchaseType[1]][$a][$i]->created_at =  date('Y-m-d H:i:s');
                                $productPriceModel[$purchaseType[1]][$a][$i]->is_enabled =  true;
                                $productPriceModel[$purchaseType[1]][$a][$i]->zone_id = 'Zone'.$a;
                                $productPriceModel[$purchaseType[1]][$a][$i]->price = $priceOptions['unitPrice'.$a.($i>0?'_'.$i:'')] = $this->currency_to_float(@$param['price'][$purchaseType[1]][$zone_counter]['price'][$i]??0);
                                $productPriceModel[$purchaseType[1]][$a][$i]->price_before = $this->currency_to_float($productPriceModel[$purchaseType[1]][$a][$i]->price);
                                $productPriceModel[$purchaseType[1]][$a][$i]->min_quantity = @$param['price'][$purchaseType[1]][$zone_counter]['min_quantity'][$i]??1;
                                $productPriceModel[$purchaseType[1]][$a][$i]->quantity_type = 'WHOLESALER';

                                if(array_key_exists($i+1,($param['price'][$purchaseType[1]][$zone_counter]['min_quantity']))){
                                    // dump('exist',$param['price'][$purchaseType[1]][$zone_counter]['min_quantity'][$i+1]);
                                    $productPriceModel[$purchaseType[1]][$a][$i]->max_quantity = intval($param['price'][$purchaseType[1]][$zone_counter]['min_quantity'][$i+1])-1;
                                }

                                if($i>0){
                                    if($productPriceModel[$purchaseType[1]][$a][$i]->price > $maxPriceInZone){
                                        $maxPriceInZone[$a] = $productPriceModel[$purchaseType[1]][$a][$i]->price;
                                    }
                                }else{
                                    $maxPriceInZone[$a] = $productPriceModel[$purchaseType[1]][$a][$i]->price;
                                }

                                $productPriceModel[$purchaseType[1]][$a][$i]->save();
                            }
                            $priceOptions['het'.$a] = $maxPriceInZone[$a];
                        }
                        $zone_counter = @$param['is_every_zone_same_price'] ? 1 : $zone_counter+1;
                    }
                }

                $zone_counter = 1; // start from 1 (well, again)
                if(array_key_exists($purchaseType[0], $param['price']) && !empty($param['price'][$purchaseType[0]])){
                    for($a = 1;$a <= $zone_limit;$a++){
                        $productPriceModel[$purchaseType[0]][$a] = new ProductPrice;
                        $productPriceModel[$purchaseType[0]][$a]->product_id = $productModel->id;
                        $productPriceModel[$purchaseType[0]][$a]->created_at =  date('Y-m-d H:i:s');
                        $productPriceModel[$purchaseType[0]][$a]->is_enabled =  true;
                        $productPriceModel[$purchaseType[0]][$a]->zone_id = 'Zone'.$a;
                        $productPriceModel[$purchaseType[0]][$a]->price = $this->currency_to_float(@$param['price'][$purchaseType[0]][$zone_counter]['price']??0);
                        $productPriceModel[$purchaseType[0]][$a]->price_before = $this->currency_to_float($productPriceModel[$purchaseType[0]][$a]->price);
                        $productPriceModel[$purchaseType[0]][$a]->min_quantity = @$param['price'][$purchaseType[0]][$zone_counter]['min_quantity']??1;
                        $productPriceModel[$purchaseType[0]][$a]->quantity_type = 'RETAIL';

                        if(array_key_exists($purchaseType[1],$param['price']) &&
                            array_key_exists($zone_counter,$param['price'][$purchaseType[1]]) &&
                            array_key_exists(0,($param['price'][$purchaseType[1]][$zone_counter]['min_quantity']))){
                            $productPriceModel[$purchaseType[0]][$a]->max_quantity = intval($param['price'][$purchaseType[1]][$zone_counter]['min_quantity'][0])-1;
                        }

                        $productPriceModel[$purchaseType[0]][$a]->save();
                        $zone_counter = @$param['is_every_zone_same_price'] ? 1 : $zone_counter+1;
                    }
                }

                // ----------------------------------------------------------[3.] STOCK
                $productStockModel = new ProductStock;
                $productStockModel->product_id = $productModel->id;
                $productStockModel->created_by =  auth()->user()->id;
                $productStockModel->created_at =  date('Y-m-d H:i:s');
                $productStockModel->stock = $itemInfo['stock'] = @$param['stock']??0;
                $productStockModel->stock_type = 'INITIAL';
                $productStockModel->save();
                // ----------------------------------------------------------[4.] IMAGE
                if(array_key_exists('video_link', $param)){
                    $productVideoModel = new ProductImage;
                    $productVideoModel->product_id = $productModel->id;
                    $productVideoModel->created_by =  auth()->user()->id;
                    $productVideoModel->created_at =  date('Y-m-d H:i:s');
                    $productVideoModel->is_enabled =  true;
                    $productVideoModel->index =  1;
                    $productVideoModel->type =  'video';
                    $productVideoModel->image_url = @$param['video_link'];
                    $productVideoModel->thumbnail_url = @$param['video_link'];
                    $productVideoModel->save();
                }

                if(array_key_exists('images', $param) && !empty($param['images'])){
                    $disk = Storage::disk('gcs');
                    $productImageModel = array();
                    foreach ($param['images'] as $key => $value) {
                        $extension = explode('/', mime_content_type($value))[1];
                        $filename_unique = 'product/'.md5(date("Ym")).'/'.sha1(time()).'-'.$productModel->id.'-'.$key;
                        $filename_original = $filename_unique.'.'.$extension;
                        // $filename_thumbnail = $filename_unique.'-thumb.'.$extension;

                        $replace = substr($value, 0, strpos($value,',')+1);
                        $image = str_replace($replace,'',$value);
                        $image = str_replace(' ','+',$image);

                        // $value_thumbnail = $this->make_thumbnail_image($value);
                        // $value_thumbnail = $value;
                        $disk->put($filename_original,base64_decode($image));
                        // $disk->put($filename_thumbnail,base64_decode($value_thumbnail));

                        $productImageModel[$key] = new ProductImage;
                        $productImageModel[$key]->product_id = $productModel->id;
                        $productImageModel[$key]->created_by =  auth()->user()->id;
                        $productImageModel[$key]->created_at =  date('Y-m-d H:i:s');
                        $productImageModel[$key]->is_enabled =  true;
                        $productImageModel[$key]->index =  $key;
                        $productImageModel[$key]->type =  'image';
                        $productImageModel[$key]->image_url = @$filename_original;
                        $productImageModel[$key]->thumbnail_url = @$filename_original;
                        // $productImageModel[$key]->thumbnail_url = @$filename_thumbnail;
                        $productImageModel[$key]->save();
                    }

                    $image_to_main = ProductImage::where('product_id',$productModel->id)->where('type','image')->orderBy('index','ASC')->first();
                    if($image_to_main){
                        Product::where('id',$productModel->id)->update(['image'=>$image_to_main['image_url']]);
                    }
                }

                // broadcast::AGREGASI // all
                $payload_agregation = array(
                    "agregation_name"=>"ItemCreated",
                    "event_desc"=>"Item Created by Vendor",
                    "product_id"=>$productModel->id,
                    "data"=>json_encode(
                        array(
                            "createdItem"=>array(
                                "merchant"=>(string)auth()->user()->user_ref_id,
                                "occurredAt"=>$date_now,
                                "itemInfo"=>$itemInfo,
                                "shippingOptions"=>$this->get_shipping_options(),
                                "priceOptions"=>$priceOptions,
                            ),
                            "sentAt"=>$date_now
                        )
                    )
                );
                BroadcastHelper::send($request,$payload_agregation,'',$date_now);

                // dd(DB::getQueryLog());
                $output = array('status'=>true, 'message'=>'Sukses '.$msg, 'aggregation'=>$payload_agregation);
                \DB::commit();
            // } catch (\Exception $e) {
            //     $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
            //     \DB::rollback();
            // }

            return $output;
        }
    }

    public function api_update_item($product_id, Request $request)
    {
        if($request->ajax()) {

            $param = $request->all();
            $msg = 'mengajukan perubahan produk';
            $date_now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');
            $date_now__after_pause = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->addMinutes(1), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');
            // dd($param);

            try {
                \DB::beginTransaction();
                // DB::enableQueryLog();
                // date_default_timezone_set('Asia/Jakarta');
                // ----------------------------------------------------------[1.] PRODUCT
                $itemInfo = array();
                $priceOptions = array();
                $productModel = array();
                // $productModel['vendor_id'] =  auth()->user()->user_ref_id;
                $productModel['updated_by'] =  auth()->user()->id;
                $productModel['updated_at'] =  date('Y-m-d H:i:s');
                $productModel['is_enabled'] =  true;
                $productModel['name'] = $itemInfo['name'] = @$param['name'];
                $productModel['description'] = $itemInfo['description'] = @$param['description'];
                $productModel['packet_description'] = (@$param['is_packet'] ? @$param['packet_description'] : '');
                $productModel['item_status_id'] = $itemInfo['itemStatus'] = 2;
                $productModel['item_status_text'] = 'TIDAK TAYANG';
                $productModel['item_type_id'] = $itemInfo['itemType'] = @$param['item_type_id'];
                // $productModel['category_key'] = @strtoupper($param['category_key']);
                if(array_key_exists('category_id',$param)){
                    $productModel['category_id'] = $itemInfo['category'] = $param['category_id'][max(array_keys($param['category_id']))];
                    if($productModel['category_id']){
                        $related_categories = ViewProductCategoryTags::where('id',$productModel['category_id'])->first();
                        if($related_categories){
                            $productModel['main_category_key'] = $related_categories->category_key;
                            $productModel['category_key'] = $related_categories->category_keys;
                        }
                    }
                }
                if($param['item_type_id'] == 1 && array_key_exists('puskurbuk_id',$param) && $param['puskurbuk_id']){
                    $productModel['puskurbuk_id'] = $itemInfo['book'] = @$param['puskurbuk_id'];
                    $productModel['puskurbuk_isbn'] = @$param['puskurbuk_isbn'];
                    $productModel['puskurbuk_nuib'] = @$param['puskurbuk_nuib'];
                    $itemInfo['isKemdikbud'] = true;
                }else{
                    $itemInfo['isKemdikbud'] = false;
                }
                if(array_key_exists('image_to_main_id',$param) && $param['image_to_main_id']){
                    $productModel['image'] = @ProductImage::where('id',$param['image_to_main_id'])->value('image_url');
                }
                $productModel['class_code'] = (@$param['class_code']?implode(",",$param['class_code']):'');
                $productModel['brand'] = $itemInfo['brand'] = @$param['brand'];
                $productModel['listing_price'] = $priceOptions['itemPrice']['price'] = $this->currency_to_float(@$param['listing_price']);
                $productModel['sales_uom'] = @$param['sales_uom'];
                // $productModel['sku'] = @$param['sku'];
                $productModel['gross_weight'] = $itemInfo['weight'] =  @$param['gross_weight'];
                $productModel['net_weight'] = @$param['net_weight'];
                $productModel['dimension_length'] = @$param['dimension_length'];
                $productModel['dimension_width'] = @$param['dimension_width'];
                $productModel['dimension_height'] = @$param['dimension_height'];
                $productModel['item_condition_type_id'] = $itemInfo['condition'] = @$param['item_condition_type_id'];
                $productModel['is_umkm'] = $itemInfo['isUMKM'] = @$param['is_umkm']??false;
                $productModel['made_in_indonesia'] = $itemInfo['isMadeInIndonesia'] = @$param['made_in_indonesia']??true;
                // $productModel['is_kemendikbud'] = $itemInfo['isKemdikbud'] = @$param['is_kemendikbud']??0;
                $productModel['is_every_zone_same_price'] = @$param['is_every_zone_same_price']??0;
                $productModel['po_duration'] = @$param['po_duration'];
                $productModel['est_delivery'] = @$param['est_delivery'];
                $productModel['warranty_days'] = $itemInfo['warranty'] = @$param['warranty_days'];
                $productModel['supplier_code'] = @$param['supplier_code'];
                $productModel['need_approval'] = 1;
                Product::where('id', $product_id)->update($productModel);
                $itemInfo['isPreorder'] = ($productModel['po_duration'] > 0?true:false);
                $itemInfo['pageUrl'] = env('APP_URL').'/detail/'.$product_id;
                $priceOptions['itemPrice']['itemMinQuantity'] = 1;
                $priceOptions['itemPrice']['itemPriceType'] = 'PRICE_RETAIL';
                $itemInfo['stock'] = @$param['stock']??0;

                // ----------------------------------------------------------[2.] PRICE
                // clean up break fee by ID
                ProductPrice::where('product_id',$product_id)->delete();

                // re-fill w/ other fee
                $productPriceModel = array();
                $purchaseType = array('retail','wholesaler');
                $zone_limit = 5;

                $zone_counter = 1; // start from 1
                if(array_key_exists($purchaseType[1], $param['price']) && !empty($param['price'][$purchaseType[1]])){
                    $productPriceModel[$purchaseType[1]] = array();
                    $maxPriceInZone = array();

                    for($a = 1;$a <= $zone_limit;$a++){
                        if((array_key_exists('is_every_zone_same_price',$param) && $param['is_every_zone_same_price'] == 1 &&
                            array_key_exists($zone_counter,$param['price'][$purchaseType[1]])) || array_key_exists($a,$param['price'][$purchaseType[1]])
                        ){
                            $param['price'][$purchaseType[1]][$zone_counter] = $this->mapping_wholesaler_price($param['price'][$purchaseType[1]][$zone_counter]);

                            for($i=0;$i<sizeof($param['price'][$purchaseType[1]][$zone_counter]['price']);$i++) {
                                $productPriceModel[$purchaseType[1]][$a][$i] = new ProductPrice;
                                $productPriceModel[$purchaseType[1]][$a][$i]->product_id = $product_id;
                                $productPriceModel[$purchaseType[1]][$a][$i]->created_at =  date('Y-m-d H:i:s');
                                $productPriceModel[$purchaseType[1]][$a][$i]->is_enabled =  true;
                                $productPriceModel[$purchaseType[1]][$a][$i]->zone_id = 'Zone'.$a;
                                $productPriceModel[$purchaseType[1]][$a][$i]->price = $priceOptions['unitPrice'.$a.($i>0?'_'.$i:'')] = $this->currency_to_float(@$param['price'][$purchaseType[1]][$zone_counter]['price'][$i]??0);
                                $productPriceModel[$purchaseType[1]][$a][$i]->price_before = $this->currency_to_float($productPriceModel[$purchaseType[1]][$a][$i]->price);
                                $productPriceModel[$purchaseType[1]][$a][$i]->min_quantity = @$param['price'][$purchaseType[1]][$zone_counter]['min_quantity'][$i]??1;
                                $productPriceModel[$purchaseType[1]][$a][$i]->quantity_type = 'WHOLESALER';

                                if(array_key_exists($i+1,($param['price'][$purchaseType[1]][$zone_counter]['min_quantity']))){
                                    // dump('exist',$param['price'][$purchaseType[1]][$zone_counter]['min_quantity'][$i+1]);
                                    $productPriceModel[$purchaseType[1]][$a][$i]->max_quantity = intval($param['price'][$purchaseType[1]][$zone_counter]['min_quantity'][$i+1])-1;
                                }

                                if($i>0){
                                    if($productPriceModel[$purchaseType[1]][$a][$i]->price > $maxPriceInZone){
                                        $maxPriceInZone[$a] = $productPriceModel[$purchaseType[1]][$a][$i]->price;
                                    }
                                }else{
                                    $maxPriceInZone[$a] = $productPriceModel[$purchaseType[1]][$a][$i]->price;
                                }

                                $productPriceModel[$purchaseType[1]][$a][$i]->save();
                            }
                            $priceOptions['het'.$a] = $maxPriceInZone[$a];
                        }
                        $zone_counter = @$param['is_every_zone_same_price'] ? 1 : $zone_counter+1;
                    }
                }

                $zone_counter = 1; // start from 1 (well, again)
                if(array_key_exists($purchaseType[0], $param['price']) && !empty($param['price'][$purchaseType[0]])){
                    for($a = 1;$a <= $zone_limit;$a++){
                        $productPriceModel[$purchaseType[0]][$a] = new ProductPrice;
                        $productPriceModel[$purchaseType[0]][$a]->product_id = $product_id;
                        $productPriceModel[$purchaseType[0]][$a]->created_at =  date('Y-m-d H:i:s');
                        $productPriceModel[$purchaseType[0]][$a]->is_enabled =  true;
                        $productPriceModel[$purchaseType[0]][$a]->zone_id = 'Zone'.$a;
                        $productPriceModel[$purchaseType[0]][$a]->price = $this->currency_to_float(@$param['price'][$purchaseType[0]][$zone_counter]['price']??0);
                        $productPriceModel[$purchaseType[0]][$a]->price_before = $this->currency_to_float($productPriceModel[$purchaseType[0]][$a]->price);
                        $productPriceModel[$purchaseType[0]][$a]->min_quantity = @$param['price'][$purchaseType[0]][$zone_counter]['min_quantity']??1;
                        $productPriceModel[$purchaseType[0]][$a]->quantity_type = 'RETAIL';

                        if(array_key_exists($purchaseType[1],$param['price']) &&
                            array_key_exists($zone_counter,$param['price'][$purchaseType[1]]) &&
                            array_key_exists(0,($param['price'][$purchaseType[1]][$zone_counter]['min_quantity']))){
                            $productPriceModel[$purchaseType[0]][$a]->max_quantity = intval($param['price'][$purchaseType[1]][$zone_counter]['min_quantity'][0])-1;
                        }

                        $productPriceModel[$purchaseType[0]][$a]->save();
                        $zone_counter = @$param['is_every_zone_same_price'] ? 1 : $zone_counter+1;
                    }
                }

                // dd($productPriceModel);
                // ----------------------------------------------------------[3.] STOCK
                // stock changed only via adjustment
                // ----------------------------------------------------------[4.] IMAGE
                // video
                if(array_key_exists('video_link', $param)){
                    ProductImage::where('product_id',$product_id)->where('type','video')->delete();
                    $productVideoModel = new ProductImage;
                    $productVideoModel->product_id = $product_id;
                    $productVideoModel->created_by =  auth()->user()->id;
                    $productVideoModel->created_at =  date('Y-m-d H:i:s');
                    $productVideoModel->is_enabled =  true;
                    $productVideoModel->index =  1;
                    $productVideoModel->type =  'video';
                    $productVideoModel->image_url = @$param['video_link'];
                    $productVideoModel->thumbnail_url = @$param['video_link'];
                    $productVideoModel->save();
                }
                // image::drop
                $currentMainImage = Product::where('id', $product_id)->value('image');
                if(array_key_exists('image_to_drop_ids', $param) && !empty($param['image_to_drop_ids'])){
                    $productImageDropModel = array();
                    foreach ($param['image_to_drop_ids'] as $key => $value) {
                        $productImageDropModel[$key] = ProductImage::where('id', $value)->update(['is_enabled' => false]);
                        if($currentMainImage == @ProductImage::where('id', $value)->value('image_url')){ // kalau yang di didabled adalah main imagenya maka hapus keberadaaanya di ms_product
                            Product::where('id',$product_id)->update(['image'=>'']);
                        }
                    }
                }
                // image::add new
                if(array_key_exists('images', $param) && !empty($param['images'])){
                    $disk = Storage::disk('gcs');
                    $productImageModel = array();
                    foreach ($param['images'] as $key => $value) {
                        $extension = explode('/', mime_content_type($value))[1];
                        $filename_unique = 'product/'.md5(date("Ym")).'/'.sha1(time()).'-'.$product_id.'-'.$key;
                        $filename_original = $filename_unique.'.'.$extension;
                        // $filename_thumbnail = $filename_unique.'-thumb.'.$extension;

                        $replace = substr($value, 0, strpos($value,',')+1);
                        $image = str_replace($replace,'',$value);
                        $image = str_replace(' ','+',$image);

                        // $value_thumbnail = $this->make_thumbnail_image($value);
                        // $value_thumbnail = $value;
                        $disk->put($filename_original,base64_decode($image));
                        // $disk->put($filename_thumbnail,base64_decode($value_thumbnail));

                        $productImageModel[$key] = new ProductImage;
                        $productImageModel[$key]->product_id = $product_id;
                        $productImageModel[$key]->created_by =  auth()->user()->id;
                        $productImageModel[$key]->created_at =  date('Y-m-d H:i:s');
                        $productImageModel[$key]->is_enabled =  true;
                        $productImageModel[$key]->index =  $key;
                        $productImageModel[$key]->type =  'image';
                        $productImageModel[$key]->image_url = @$filename_original;
                        $productImageModel[$key]->thumbnail_url = @$filename_original;
                        // $productImageModel[$key]->thumbnail_url = @$filename_thumbnail;
                        $productImageModel[$key]->save();
                    }
                }

                $currentMainImage = Product::where('id', $product_id)->value('image');
                if(!$currentMainImage){ //just if main image doesnt exist
                    $image_to_main = ProductImage::where('product_id',$product_id)->where('type','image')->orderBy('index','ASC')->first();
                    if($image_to_main){
                        Product::where('id',$product_id)->update(['image'=>$image_to_main['image_url']]);
                    }
                }

                // broadcast::AGREGASI // price only
                $payload_agregation = array(
                    "agregation_name"=>"ItemPriceUpdated",
                    "event_desc"=>"Item Price Updated by Vendor",
                    "product_id"=>$product_id,
                    "data"=>json_encode(
                        array(
                            "updatedItem" => array(
                                "priceOptions"=>$priceOptions,
                                "occurredAt"=>$date_now
                            ),
                            "sentAt"=>$date_now
                        )
                    )
                );
                BroadcastHelper::send($request,$payload_agregation,'',$date_now__after_pause);

                // broadcast::AGREGASI // all
                $payload_agregation = array(
                    "agregation_name"=>"ItemInfoUpdated",
                    "event_desc"=>"Item Info Updated by Vendor",
                    "product_id"=>$product_id,
                    "data"=>json_encode(
                        array(
                            "updatedItem"=>array(
                                "merchant"=>(string)auth()->user()->user_ref_id,
                                "occurredAt"=>$date_now__after_pause,
                                "itemInfo"=>$itemInfo,
                                "shippingOptions"=>$this->get_shipping_options(),
                                "priceOptions"=>$priceOptions,
                            ),
                            "sentAt"=>$date_now__after_pause
                        )
                    )
                );
                BroadcastHelper::send($request,$payload_agregation,'',$date_now__after_pause);

                // dd(DB::getQueryLog());
                $output = array('status'=>true, 'message'=>'Sukses '.$msg);
                \DB::commit();
            } catch (\Exception $e) {
                $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
                \DB::rollback();
            }

            return $output;
        }
    }


    public function api_check_item_in_ongoing_order($product_id, Request $request)
    {
        if($request->ajax()) {

            $param['order_done_status'] = array('EXPIRED', 'CANCELED', 'BAST_REJECTED', 'BUYER_REJECT', 'ORDER_REJECTED', 'PAYMENT_RECEIVED');
            $checkOrderCheckoutItem = OrderCheckoutItem::where('product_id',$product_id)
                                        ->whereHas('order_detail', function($q) use ($param){
                                            $q->whereNotIn('status', $param['order_done_status']);
                                        })->get()->toArray();

            if($checkOrderCheckoutItem){
                $msg = 'Produk ini ada dalam order yang aktif. Selesaikan order dahulu kemudian coba lagi';
                $output = array('status'=>false, 'is_free'=>false, 'message'=>$msg);
            }else{
                $msg = 'Produk ini tidak ada dalam order yang aktif. Bisa dilanjutkan';
                $output = array('status'=>true, 'is_free'=>true, 'message'=>$msg);
            }

            return $output;
        }
    }


    public function api_update_status_item($product_id, Request $request)
    {
        if($request->ajax()) {

            $param = $request->all();
            $msg = 'melakukan perubahan status';

            try {
                \DB::beginTransaction();

                $productModel = array();
                if($param['status_id'] == 1){
                    $productModel['need_approval'] = 1;
                    $productModel['item_status_id'] = 2;
                    $productModel['item_status_text'] = 'TIDAK TAYANG';
                    $output = array('status'=>true, 'message'=>'Sukses mengajukan perubahan status');
                }else{
                    if($param['status_id'] == 5){
                        $productModel['need_approval'] = 0;
                    }
                    $productModel['item_status_id'] = $param['status_id'];
                    $productModel['item_status_text'] = SelectionList::where('selection_type','ITEM_STATUS')->where('value',$param['status_id'])->value('name');
                    $output = array('status'=>true, 'message'=>'Sukses '.$msg);
                }
                Product::where('id', $product_id)->update($productModel);

                \DB::commit();
            } catch (\Exception $e) {
                $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
                \DB::rollback();
            }

            return $output;
        }
    }

    public function api_store_bulk(Request $request)
    {
        if($request->ajax()) {
            $param = $request->all();
            $msg = 'masuk ke antrian unggah produk (bulk).';

            if(array_key_exists('file',$param)){
                try {
                    \DB::beginTransaction();
                    // DB::enableQueryLog();
                    // date_default_timezone_set('Asia/Jakarta');
                    $extension_allowed = array('csv');
                    if(in_array($param['file']->getClientOriginalExtension(), $extension_allowed)){
                        $disk = Storage::disk('gcs');
                        $filename_unique    = 'product_bulk/'.md5(date("Ym")).'/'.sha1(time()).'-'.auth()->user()->user_ref_id;
                        $filename_original  = $filename_unique.'.'.($param['file']->getClientOriginalExtension());
                        $productUploadDisk = $disk->put($filename_original,File::get($param['file']));

                        if($productUploadDisk){
                            $productUploadModel = new ProductUpload;
                            $productUploadModel->vendor_id =  auth()->user()->user_ref_id;
                            $productUploadModel->created_by =  auth()->user()->id;
                            $productUploadModel->created_at =  date('Y-m-d H:i:s');
                            $productUploadModel->upload_date =  date('Y-m-d H:i:s');
                            $productUploadModel->status = 'new';
                            $productUploadModel->file_location = $filename_original;
                            $productUploadModel->original_file_name = $param['file']->getClientOriginalName();
                            $productUploadModel->save();
                            $output = array('status'=>true, 'message'=>'Sukses '.$msg.' Ini akan memakan beberapa waktu. Jika sudah diproses, status `BARU` akan berganti menjadi `SELESAI` dan jumlah produk yang berhasil/gagal ditambahkan akan ditampilkan');
                        }else{
                            $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$productUploadDisk);
                        }
                    }else{
                        $output = array('status'=>false, 'message'=>'Ekstensi file tidak didukung, hanya menerima '.implode(", ",$extension_allowed));
                    }

                    // dd(DB::getQueryLog());
                    \DB::commit();
                } catch (\Exception $e) {
                    $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
                    \DB::rollback();
                }
            }else{
                $output = array('status'=>false, 'message'=>'Tidak ada file', 'detail'=>$e);
            }

            return $output;
        }
    }

    public function api_adjust_stock($product_id, Request $request)
    {
            // https://stackoverflow.com/questions/1637019/how-to-get-the-jquery-ajax-error-response-text
        if($request->ajax()) {
            $param = $request->all();
            $msg = 'mengatur stock ('.$param['nature'].$param['amount'].')';
            $date_now = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');

            try {
                // ---------------------------------------------------------- STOCK
                $stock = ($param['nature'] == '+' ? $param['amount'] : -$param['amount']);

                $productStockModel = new ProductStock;
                $productStockModel->product_id = $product_id;
                $productStockModel->created_by =  auth()->user()->id;
                $productStockModel->created_at =  date('Y-m-d H:i:s');
                $productStockModel->stock = $stock;
                $productStockModel->stock_type = 'ADJUST';
                $productStockModel->save();

                $stock_sum = ProductStock::where('product_id', $product_id)->sum('stock');

                // broadcast::AGREGASI
                $payload_agregation = array(
                    "agregation_name"=>"ItemStockUpdated",
                    "event_desc"=>"Item Stock Updated by Vendor",
                    "product_id"=>$product_id,
                    "data"=>json_encode(
                        array(
                            "updatedItem"=>array(
                                "stock"=>(string)$stock_sum,
                                "occurredAt"=>$date_now
                            ),
                            "sentAt"=>$date_now
                        )
                    )
                );
                BroadcastHelper::send($request,$payload_agregation,'',$date_now);

                $output = array('status'=>true, 'message'=>'Sukses '.$msg, 'detail'=>array('sum'=>$stock_sum));
                \DB::commit();
            } catch (\Exception $e) {
                $output = array('status'=>false, 'message'=>'Gagal '.$msg, 'detail'=>$e);
                \DB::rollback();
            }

            return $output;
        }
    }

    public function api_upload_images(Request $request)
    {
        if($request->ajax()) {
            return 1;
        }
    }

    public function make_thumbnail_image($img){
    	$percent = 0.5;

    	// Content type
    	header('Content-Type: image/jpeg');

    	$data = base64_decode($img);
    	$im = imagecreatefromstring($data);
    	$width = imagesx($im);
    	$height = imagesy($im);
    	$newwidth = $width * $percent;
    	$newheight = $height * $percent;

    	$thumb = imagecreatetruecolor($newwidth, $newheight);

    	// Resize
    	imagecopyresized($thumb, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

    	// Output
    	return imagejpeg($thumb);
    }
}
