<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $account = $request->session()->get('account');
        $token = $request->session()->get('token');
        
        if( empty($account) || empty($token )){
            return redirect()->route('AdminLoginView');
        }
        $admin =  DB::table("role")
        ->where([
            ['account','=',$account],
            ['token','=',$token]
        ])->first();

        if(isset($admin)){
            return $next($request);
        }

        return redirect()->route('AdminLoginView');


    }
}
