<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserReference
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
        if(!empty(auth()->user()->user_ref_id)){
            return $next($request);
        }
        return redirect('vendor/edit')->with('middleware-info','Anda harus melengkapi data vendor terlebih dahulu untuk dapat mengakses lebih banyak fitur yang ada.');
    }
}
