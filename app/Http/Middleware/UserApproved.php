<?php

namespace App\Http\Middleware;

use App\Models\Vendor;
use Closure;
use Illuminate\Http\Request;

class UserApproved
{
    /**
     *
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $vendor_data = Vendor::where('id', auth()->user()->user_ref_id)->first();
        if(@$vendor_data->status == "APPROVED"){
            return $next($request);
        }
        return redirect('vendor')->with('middleware-info','Mohon maaf, status vendor belum di Approve atau di Tolak, sehingga Anda belum dapat mengakses fitur ini.');
    }
}
