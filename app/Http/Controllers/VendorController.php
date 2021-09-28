<?php

namespace App\Http\Controllers;

use App\Models\Region;
use App\Models\SelectionList;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorDocument;
use App\Models\District;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Helpers\BroadcastHelper;

class VendorController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        $vendor_data = Vendor::where('id', auth()->user()->user_ref_id)->first();
        $data = array(
            "vendor_data" => $vendor_data,
            "disk" => Storage::disk('gcs')
        );
        return view('pages.vendor.index',$data);
    }

    public function edit(){

        $vendor_data = Vendor::where('id', auth()->user()->user_ref_id)->first();
        $list_business_type = SelectionList::where('selection_type','BUSINESS_TYPE')->get();
        $list_business_class = SelectionList::where('selection_type','BUSINESS_LEVEL')->get();
        $list_legal_status = SelectionList::where('selection_type','LEGAL_STATUS')->get();
        $list_regions = Region::where("is_enabled", true)->get();
        $data = array(
            "vendor_data" => $vendor_data,
            "list_business_type" => $list_business_type,
            "list_business_class" => $list_business_class,
            "list_legal_status" => $list_legal_status,
            "list_regions" => $list_regions,
            "disk" => Storage::disk('gcs')
        );
        return view('pages.vendor.edit',$data);;
    }

    public function submit(Request $request){
        if($request->ajax()) {

            if(empty(auth()->user()->user_ref_id)){
                $result = $this->saved($request);
            }else{
                $result = $this->update($request);
            }

            return json_encode($result);
        }
    }

    private function saved($request){

        $this->validating_request($request);

        $param = $request->all();
        
        $result_status = "SUCCESS";
        $result_message = "Your data was submitted successfully!";
        $title = "Saved!";
        $data = array();

        $vendor = new Vendor;

        $vendor->name = @$param["company_name"];
        $vendor->business_type = @$param["business_type"];
        $vendor->business_class = @$param["business_class"];
        $vendor->tax_status = @$param["legal_status"];
        $vendor->npwp = @$param["npwp"];
        $vendor->siup_nib = @$param["siup_nib"];
        $vendor->tdp = @$param["tdp"];
        $vendor->address = @$param["address"];
        $geolocation = @$param["geolocation"];
        $vendor->address_latitude = @explode(',',$geolocation)[0];
        $vendor->address_longitude = @explode(',',$geolocation)[1];
        $vendor->address_region_id = @$param["region"];
        $vendor->address_district_id = @$param["districts"];
        //$vendor->address_subdistrict_id = @$param["sub-district"];
        $vendor->address_zip_code = @$param["zip-code"];
        $vendor->status = "CREATED";
        $vendor->email = @$param["email"];
        $vendor->pic_name = @$param["pic-fullname"];
        $vendor->pic_id_card_no = @$param["pic-nik"];
        $vendor->pic_position = @$param["pic-title"];
        $vendor->pic_email = @$param["pic-email"];
        $vendor->phone2 = @$param["pic-phone"];
        $vendor->phone1 = @$param["pic-office-phone"];
        $vendor->bank_name = @$param["bank-name"];
        $vendor->bank_account_name = @$param["bank-owner-name"];
        $vendor->bank_account_no = @$param["bank-number"];
        $vendor->bank_branch_name = @$param["bank-branch"];

        //$profile_avatar_remove = @$param["profile_avatar_remove"];

        $profile_avatar = @$request->file("profile_avatar");
        $profile_avatar_name = 'vendor-doc/'.md5(date("Ym")).'/'.sha1(time()).'-logo.'.$profile_avatar->getClientOriginalExtension();

        $vendor->avatar_img = $profile_avatar_name;

        $disk = Storage::disk('gcs');
        $disk->put($profile_avatar_name,File::get($profile_avatar));

        $vendor->created_by = auth()->user()->id;
        $save = $vendor->save();
        $documents = array();
        if($save){

            $ktp_file = @$request->file("ktp-file");
            if($ktp_file){
                $filename = sha1(time()).'-ktp.'.$ktp_file->getClientOriginalExtension();
                $vendor_document = new VendorDocument;
                $ktp_file_name = 'vendor-doc/'.md5(date("Ym")).'/'.$filename;
                $vendor_document->vendor_id = $vendor->id;
                $vendor_document->type = "KTP";
                $vendor_document->url = $ktp_file_name;
                $vendor_document->created_by = auth()->user()->id;
                $disk->put($ktp_file_name,File::get($ktp_file));
                $vendor_document->save();
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => $filename,
                    'type' => 'KTP'
                );
                array_push($documents,$doc);
            }

            $siup_nib_file = @$request->file("siup-nib-file");
            if($siup_nib_file){
                $filename = sha1(time()).'-siup-nib.'.$siup_nib_file->getClientOriginalExtension();
                $vendor_document = new VendorDocument;
                $siup_nib_file_name = 'vendor-doc/'.md5(date("Ym")).'/'.$filename;
                $vendor_document->vendor_id = $vendor->id;
                $vendor_document->type = "SIUP_NIB";
                $vendor_document->url = $siup_nib_file_name;
                $vendor_document->created_by = auth()->user()->id;
                $disk->put($siup_nib_file_name,File::get($siup_nib_file));
                $vendor_document->save();
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => $filename,
                    'type' => 'SIUP_NIB'
                );
                array_push($documents,$doc);
            }

            $npwp_file = @$request->file("npwp-file");
            if($npwp_file){
                $filename = sha1(time()).'-npwp.'.$npwp_file->getClientOriginalExtension();
                $vendor_document = new VendorDocument;
                $npwp_file_name = 'vendor-doc/'.md5(date("Ym")).'/'.$filename;
                $vendor_document->vendor_id = $vendor->id;
                $vendor_document->type = "NPWP";
                $vendor_document->url = $npwp_file_name;
                $vendor_document->created_by = auth()->user()->id;
                $disk->put($npwp_file_name,File::get($npwp_file));
                $vendor_document->save();
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => $filename,
                    'type' => 'NPWP'
                );
                array_push($documents,$doc);
            }

            $tdp_file = @$request->file("tdp-file");
            if($tdp_file){
                $filename = sha1(time()).'-tdp.'.$tdp_file->getClientOriginalExtension();
                $vendor_document = new VendorDocument;
                $tdp_file_name = 'vendor-doc/'.md5(date("Ym")).'/'.$filename;
                $vendor_document->vendor_id = $vendor->id;
                $vendor_document->type = "TDP";
                $vendor_document->url = $tdp_file_name;
                $vendor_document->created_by = auth()->user()->id;
                $disk->put($tdp_file_name,File::get($tdp_file));
                $vendor_document->save();
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => $filename,
                    'type' => 'TDP'
                );
                array_push($documents,$doc);
            }

            $update = User::where('id',auth()->user()->id)
                ->update([
                    "user_ref_id" => $vendor->id,
                    "updated_by" => auth()->user()->id
                ]);

            if(!$update){
                $result_status = "ERROR";
                $result_message = "There was a problem when assign user ref id";
            }

            $this->broadcast("MerchantCreated","createdMerchant",$request, $param, $documents, 1,$vendor->id);

        }else{
            $result_status = "ERROR";
            $result_message = "There was a problem saving data";
        }


        return array(
            "data" => $data,
            "title" => $title,
            "status" => $result_status,
            "message" => $result_message
        );
    }

    public function validating_request($request){
        $request->validate([
            'siup_nib' => 'max:13|min:13',
            'zip-code' => 'max:5|min:5',
            'npwp' => 'max:15|min:15',
        ]);

        
        // return array(
        //     "data" => $data,
        //     "title" => $title,
        //     "status" => $result_status,
        //     "message" => $result_message
        // );
    }

    private function update($request){

        $this->validating_request($request);

        $param = $request->all();

        $result_status = "SUCCESS";
        $result_message = "Your data was updated successfully!";
        $title = "Updated!";
        $data = array();

        $status = 1;
        $vendor_status = Vendor::select('status')->where('id',auth()->user()->user_ref_id)->first();
        if($vendor_status->status == "REJECTED"){
            $status = 2;
        }

        $geolocation = @$param["geolocation"];
        $update = Vendor::where('id', auth()->user()->user_ref_id)
            ->where('created_by', auth()->user()->id)
            ->update([
                "name" => @$param["company_name"],
                "business_type" => @$param["business_type"],
                "business_class" =>  @$param["business_class"],
                "tax_status" => @$param["legal_status"],
                "npwp" => @$param["npwp"],
                "siup_nib" => @$param["siup_nib"],
                "tdp" => @$param["tdp"],
                "address" => @$param["address"],
                "address_latitude" => @explode(',',$geolocation)[0],
                "address_longitude" => @explode(',',$geolocation)[1],
                "address_region_id" => @$param["region"],
                "address_district_id" => @$param["districts"],
                "address_zip_code" => @$param["zip-code"],
                "status" => "MODIFIED",
                "email"=> @$param["email"],
                "pic_name" => @$param["pic-fullname"],
                "pic_id_card_no" => @$param["pic-nik"],
                "pic_position" => @$param["pic-title"],
                "pic_email" => @$param["pic-email"],
                "phone2" => @$param["pic-phone"],
                "phone1" => @$param["pic-office-phone"],
                "bank_name" => @$param["bank-name"],
                "bank_account_name" => @$param["bank-owner-name"],
                "bank_account_no" => @$param["bank-number"],
                "bank_branch_name" => @$param["bank-branch"],
                "updated_by" => auth()->user()->id
            ]);

        if(!$update){
            $result_status = "ERROR";
            $result_message = "There was a problem when update data";
        }

        $documents = array();

        $profile_avatar = @$request->file("profile_avatar");
        if($profile_avatar){
            $profile_avatar_name = 'vendor-doc/'.md5(date("Ym")).'/'.sha1(time()).'-logo.'.$profile_avatar->getClientOriginalExtension();
            $disk = Storage::disk('gcs');
            $disk->put($profile_avatar_name,File::get($profile_avatar));

            $update = Vendor::where('id', auth()->user()->user_ref_id)
                ->where('created_by', auth()->user()->id)
                ->update([
                    "avatar_img" => $profile_avatar_name,
                    "status" => "MODIFIED",
                    "updated_by" => auth()->user()->id
                ]);

            if(!$update){
                $result_status = "ERROR";
                $result_message = "There was a problem when update vendor logo";
            }
        }

        $ktp_file = @$request->file("ktp-file");
        if($ktp_file){

            $update = VendorDocument::where('vendor_id', auth()->user()->user_ref_id)
                ->where('created_by', auth()->user()->id)
                ->where('type', "KTP")
                ->where('is_enabled', true)
                ->update([
                    "is_enabled" => false,
                    "updated_by" => auth()->user()->id
                ]);

            if($update){
                $filename = sha1(time()).'-ktp.'.$ktp_file->getClientOriginalExtension();
                $vendor_document = new VendorDocument;
                $ktp_file_name = 'vendor-doc/'.md5(date("Ym")).'/'.$filename;
                $vendor_document->vendor_id = auth()->user()->user_ref_id;
                $vendor_document->type = "KTP";
                $vendor_document->url = $ktp_file_name;
                $vendor_document->created_by = auth()->user()->id;
                $disk = Storage::disk('gcs');
                $disk->put($ktp_file_name,File::get($ktp_file));
                $vendor_document->save();
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => $filename,
                    'type' => 'KTP'
                );
                array_push($documents,$doc);
            }
        }else{
            $vendor_document = VendorDocument::select('id','url')
                ->where('vendor_id', auth()->user()->user_ref_id)
                ->where('type', "KTP")
                ->first();
            if($vendor_document != null){
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => explode('/',$vendor_document->url)[2],
                    'type' => 'KTP'
                );
                array_push($documents,$doc);
            }
        }

        $siup_nib_file = @$request->file("siup-nib-file");
        if($siup_nib_file){

            $update = VendorDocument::where('vendor_id', auth()->user()->user_ref_id)
                ->where('created_by', auth()->user()->id)
                ->where('type', "SIUP_NIB")
                ->where('is_enabled', true)
                ->update([
                    "is_enabled" => false,
                    "updated_by" => auth()->user()->id
                ]);

            if($update){
                $filename = sha1(time()).'-siup-nib.'.$siup_nib_file->getClientOriginalExtension();
                $vendor_document = new VendorDocument;
                $siup_nib_file_name = 'vendor-doc/'.md5(date("Ym")).'/'.$filename;
                $vendor_document->vendor_id = auth()->user()->user_ref_id;
                $vendor_document->type = "SIUP_NIB";
                $vendor_document->url = $siup_nib_file_name;
                $vendor_document->created_by = auth()->user()->id;
                $disk = Storage::disk('gcs');
                $disk->put($siup_nib_file_name,File::get($siup_nib_file));
                $vendor_document->save();
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => $filename,
                    'type' => 'SIUP_NIB'
                );
                array_push($documents,$doc);
            }
        }else{
            $vendor_document = VendorDocument::select('id','url')
                ->where('vendor_id', auth()->user()->user_ref_id)
                ->where('type', "NIUP_SIB")
                ->first();
            if($vendor_document != null){
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => explode('/',$vendor_document->url)[2],
                    'type' => 'SIUP_NIB'
                );
                array_push($documents,$doc);
            }
        }

        $npwp_file = @$request->file("npwp-file");
        if($npwp_file){

            $update = VendorDocument::where('vendor_id', auth()->user()->user_ref_id)
                ->where('created_by', auth()->user()->id)
                ->where('type', "NPWP")
                ->where('is_enabled', true)
                ->update([
                    "is_enabled" => false,
                    "updated_by" => auth()->user()->id
                ]);

            if($update){
                $filename = sha1(time()).'-npwp.'.$npwp_file->getClientOriginalExtension();
                $vendor_document = new VendorDocument;
                $npwp_file_name = 'vendor-doc/'.md5(date("Ym")).'/'.$filename;
                $vendor_document->vendor_id = auth()->user()->user_ref_id;
                $vendor_document->type = "NPWP";
                $vendor_document->url = $npwp_file_name;
                $vendor_document->created_by = auth()->user()->id;
                $disk = Storage::disk('gcs');
                $disk->put($npwp_file_name,File::get($npwp_file));
                $vendor_document->save();
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => $filename,
                    'type' => 'NPWP'
                );
                array_push($documents,$doc);
            }
        }else{
            $vendor_document = VendorDocument::select('id','url')
                ->where('vendor_id', auth()->user()->user_ref_id)
                ->where('type', "NPWP")
                ->first();
            if($vendor_document != null){
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => explode('/',$vendor_document->url)[2],
                    'type' => 'NPWP'
                );
                array_push($documents,$doc);
            }
        }

        $tdp_file = @$request->file("tdp-file");
        if($tdp_file){
            $update = VendorDocument::where('vendor_id', auth()->user()->user_ref_id)
                ->where('created_by', auth()->user()->id)
                ->where('type', "TDP")
                ->where('is_enabled', true)
                ->update([
                    "is_enabled" => false,
                    "updated_by" => auth()->user()->id
                ]);

            if($update){
                $filename = sha1(time()).'-tdp.'.$tdp_file->getClientOriginalExtension();
                $vendor_document = new VendorDocument;
                $tdp_file_name = 'vendor-doc/'.md5(date("Ym")).'/'.$filename;
                $vendor_document->vendor_id = auth()->user()->user_ref_id;
                $vendor_document->type = "TDP";
                $vendor_document->url = $tdp_file_name;
                $vendor_document->created_by = auth()->user()->id;
                $disk = Storage::disk('gcs');
                $disk->put($tdp_file_name,File::get($tdp_file));
                $vendor_document->save();
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => $filename,
                    'type' => 'TDP'
                );
                array_push($documents,$doc);
            }
        }else{
            $vendor_document = VendorDocument::select('id','url')
                ->where('vendor_id', auth()->user()->user_ref_id)
                ->where('type', "TDP")
                ->first();
            if($vendor_document != null){
                $doc = array(
                    'id' => strval($vendor_document->id),
                    'number' => explode('/',$vendor_document->url)[2],
                    'type' => 'TDP'
                );
                array_push($documents,$doc);
            }
        }

        $this->broadcast("MerchantInfoUpdated","updatedMerchant", $request, $param, $documents, $status,null);

        return array(
            "data" => $data,
            "title" => $title,
            "status" => $result_status,
            "message" => $result_message
        );
    }

    private function broadcast($agregation_name, $collector, $request, $param, $documents, $status, $vendorId){

        $timestamp = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now(), 'UTC')->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i:s\Z');
        $kecamatan = District::select('name')->where('id',$param["districts"])->first();
        $legalId = SelectionList::select('value')->where(['selection_type'=>'LEGAL_STATUS','name'=>$param["legal_status"]])->first();

        if($vendorId == null){
            $vendorId = auth()->user()->user_ref_id;
        }

        if($collector == "updateMerchant"){
            $event_desc = "Data Vendor ".$vendorId." telah diupdate";
        }else{
            $event_desc = "Data Vendor ".$vendorId." telah dicreate";
        }

        $exclusiveInfo = array();
        if($param["business_type"] == 'Individu'){
            $exclusiveInfo = array(
                'individual' => array(
                    'nik' => @$param["pic-nik"]
                )
            );
        }else{
            $exclusiveInfo = array(
                'corporation' => array(
                    'picName' => $param["pic-fullname"],
                    'picRole' => $param["pic-title"],
                    'nibSiup' => $param["siup_nib"]
                )
            );
        }
        $isUmkm = false;
        if($param['business_class'] != 'Besar'){
            $isUmkm = true;
        }

        $payload = array(
            'vendor_id'=> $vendorId,
            'vendor_user_id' => auth()->user()->id,
            'agregation_name' => $agregation_name,
            'event_desc' => $event_desc,
            'data' => json_encode(array(
                $collector => array(
                    'merchantInfo' => array(
                        'name' => @$param["company_name"],
                        'rating' => 0,
                        'regionId' => @$param["region"],
                        'npwp' => @$param["npwp"],
                        'address' => @$param["address"],
                        'kecamatan' => $kecamatan->name,
                        'postalCode' => @$param["zip-code"],
                        'locationLat' => floatval(explode(',',$param['geolocation'])[0]),
                        'locationLong' => floatval(explode(',',$param['geolocation'])[1]),
                        'phone' => @$param["pic-office-phone"],
                        'email' => @$param["email"],
                        'bankAccName' => @$param["bank-name"],
                        'bankAccNum' => @$param["bank-number"],
                        'bankAccOwner' => @$param["bank-owner-name"],
                        'legalStatus' => intval($legalId->value),
                        'exclusiveInfo' => $exclusiveInfo,
                        'isUmkm' => $isUmkm,
                        'merchantStatus' => $status
                    ),
                    'documents' => $documents,
                    'occurredAt' => $timestamp
                ),
                'sendAt' => $timestamp
            ))
        );
        BroadcastHelper::send($request, $payload);
    }
}
