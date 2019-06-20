<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserPriv
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(Auth::check() && (Auth::user()->user_priv == 1 || Auth::user()->user_priv == 7)){
            return $next($request);
        }
        return redirect('user/home');

    }
}
