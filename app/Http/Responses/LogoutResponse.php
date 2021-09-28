<?php

namespace App\Http\Responses;

use App\Helpers\BroadcastHelper;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Crypt;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;

class LogoutResponse implements LogoutResponseContract
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
            "occurredAt" => date("Y-m-d\TH:i:s\Z"),
            "sentAt" => date("Y-m-d\TH:i:s\Z")
        );

        $aggregation = json_decode($request->cookie("aggregation"));

        $user_name = Crypt::decryptString($aggregation->un);
        $vendor_name = Crypt::decryptString($aggregation->vn);

        $payload = array(
            "agregation_name" => "LoggedOutMerchant",
            "event_desc" => "Pengguna/Penyedia atas nama ".$user_name." Logout dari Merchant ".$vendor_name,
            "vendor_user_id" => Crypt::decryptString($aggregation->uid),
            "vendor_id" => Crypt::decryptString($aggregation->vid),
            "data" => json_encode($data)
        );
        BroadcastHelper::send($request, $payload);
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect('/');
    }
}
