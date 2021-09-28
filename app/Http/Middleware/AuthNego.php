<?php

namespace App\Http\Middleware;

use App\Models\UserCart;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AuthNego
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
        $id = $request->input('id');
        $cart = UserCart::where('id',Crypt::decryptString($id))->with('product')->first();
        if($cart->product->vendor_id == auth()->user()->user_ref_id){
            return $next($request);
        }
        return redirect('nego')->with('middleware-info','Anda tidak dapat membuka nego tersebut');
    }
}
