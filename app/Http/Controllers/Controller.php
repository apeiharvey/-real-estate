<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct() {
        $this->data['url'] = array(
            'disk' => Storage::disk('gcs')
        ); 
    }

    
    function callAPI($method, $url, $data = false, $data2 = false) 
    {
        // Method: POST, PUT, GET etc
        // Data: array("param" => "value") ==> index.php?param=value
        $curl = curl_init();
        $headers = [
            'WEBSITE: '.env('API_HEADER_WEBSITE'),
            'IPADDRESS:'.($this->getClientIP())
        ];
        
        if($data && is_array($data)){
            if(array_key_exists('need_auth',$data) && $data['need_auth'] == 1){
                if(isset($_COOKIE['s_t'])){
                    array_push($headers,
                        'TOKEN: '.$_COOKIE['s_t']
                    );   
                    unset($data['need_auth']);
                }else if(array_key_exists('direct_token',$data)){
                    array_push($headers,
                        'TOKEN: '.$data['direct_token']
                    );   
                    unset($data['direct_token']);
                }else{
                    return array('step'=>'break', 'subject'=>'token', 'message'=>'Required session not exist');
                }
            }
        }
        
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                
                if ($data){
                    if(is_array($data) && array_key_exists('json_pretty',$data)){
                        unset($data['json_pretty']);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data, JSON_PRETTY_PRINT));
                        // why JSON_PRETTY_PRINT? we can casualy throw $data, but since the API build on .net, we neet to make sure no error because of JSON structure so we transform it to Json.NET
                    }else if(is_array($data2) && array_key_exists('json_multi_d',$data)){
                        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data2, JSON_PRETTY_PRINT));
                    }else{
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    } 
                }
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE" );
            break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1); 
            // break; // put also serve the data like GET method; so we disable break here
            default:
                curl_setopt($curl, CURLOPT_HTTPGET, 1);
                if ($data){
                    // original:
                    // $url = sprintf("%s?%s", $url, http_build_query($data)); 
                    // custom :
                    $i = 0;
                    foreach ($data as $data_key => $data_value) {
                        $url .= ($i==0 ? "?" : "&");
                        $data_value = (is_string($data_value) ? $data_value :json_encode($data_value));
                        $url .= $data_key."=".$data_value;
                        $i++;
                    }
                }
        }
        
        // Optional Authentication:
        // curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        // curl_setopt($curl, CURLOPT_USERPWD, "username:password");
        
        // Required:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        
        $result = curl_exec($curl);
        curl_close($curl);

        // return $url;
        // dump($result);
        return $result;
    }

    function getClientIP(){
        if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
                $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
                return trim($addr[0]);
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    function templateReturn($result, $method = NULL, $url = NULL, $data = NULL){
        if(is_array($result)){
            if(array_key_exists('step',$result) && $result['step'] == 'break'){
                return json_encode(array('status'=>false, 'message'=>$result['message'], 'action'=>'login', 'last_stop'=>'general'));
            }else{
                return json_encode(array('status'=>false, 'message'=>'Break, no message'));
            }
        }else{
            $readResult = json_decode($result, true);
            if(!empty($readResult) && array_key_exists('is_ok',$readResult)){
                if($readResult['is_ok'] == "true"){ 
                    if(array_key_exists('data',$readResult)){
                        return json_encode(array('status'=>true, 'detail'=>$readResult['data']));
                    }else{
                        return json_encode(array('status'=>true, 'message'=>$readResult['message']));
                    }
                }else{
                    return json_encode(array('status'=>false, 'message'=>$readResult['message']));
                }
            }else if(!empty($readResult) && array_key_exists('exp',$readResult)){
                if(strtoupper($readResult['exp']) == strtoupper('token expired')){
                    // return app('App\Http\Controllers\Auth\LoginController')->ajax_refresh_token($method, $url, $data); // not handled in siplah
                    return json_encode(array('status'=>false, 'message'=>'token expired'));
                }
            }else{
                return json_encode(array('status'=>false, 'message'=>'Something wrong, please try again later', 'detail'=>$readResult));
            }
        }
    }

    
}
