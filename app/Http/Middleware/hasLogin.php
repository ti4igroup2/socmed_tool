<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Socialite;

class isAdmin
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
        
        if(Auth::check() && auth()->user()["user_role"]!="admin"){
            return redirect('/notLogin');
        }
        return $next($request);
    }
}
