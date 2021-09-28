<?php

namespace App\Http\Responses;

use App\Helpers\BroadcastHelper;
use App\Models\Vendor;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {

        $data = array(
            "client" => array(
                "userAgent" => $request->server('HTTP_USER_AGENT'),
                "ipAddress" => $request->getClientIp()
            ),
            "expiredAt" => date("Y-m-d\TH:i:s\Z", strtotime('+120 minutes')),
            "occurredAt" => date("Y-m-d\TH:i:s\Z"),
            "sentAt" => date("Y-m-d\TH:i:s\Z")
        );

        $session_id = sha1(hash("sha256", @$request->all()["_token"]));
        Cookie::queue(Cookie::make("sid",$session_id, 120));

        $user_name = auth()->user()->name;

        $vendor = Vendor::where("id", auth()->user()->user_ref_id)->first();
        $vendor_name = @$vendor->name;

        $aggregation = array(
            "un" => Crypt::encryptString($user_name),
            "vn" => Crypt::encryptString($vendor_name),
            "uid" => Crypt::encryptString(auth()->user()->id),
            "vid" => Crypt::encryptString(auth()->user()->user_ref_id),
        );

        Cookie::queue(Cookie::make("aggregation", json_encode($aggregation), 120));

        $payload = array(
            "agregation_name" => "LoggedInMerchant",
            "event_desc" => "Pengguna/Penyedia atas nama ".$user_name." Login ke Merchant ".$vendor_name,
            "vendor_id" => auth()->user()->user_ref_id,
            "vendor_user_id" => auth()->user()->id,
            "data" => json_encode($data),
            "session_id" => $session_id
        );
        BroadcastHelper::send($request, $payload);
        return $request->wantsJson()
            ? response()->json(['two_factor' => false])
            : redirect()->intended(config('fortify.home'));
    }
}
