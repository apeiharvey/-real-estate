<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\OrderCheckoutDetail;
use Illuminate\Support\Facades\Crypt;

class AuthOrder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id');
        if($id == null){
            $id = $request->input('id');
        }
        $order = OrderCheckoutDetail::where('id',Crypt::decryptString($id))->first();
        if($order->vendor_id == auth()->user()->user_ref_id){
            return $next($request);
        }

        return redirect('order')->with('middleware-info','Anda tidak dapat membuka order tersebut');
    }
}
