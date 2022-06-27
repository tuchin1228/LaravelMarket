<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Admin extends Controller
{
    public function AdminLogin(Request $req)
    {
        $account = $req->account;
        $password = $req->password;

        $admin = DB::table("role")
        ->where([
            ['account','=',$account],
            ['password','=',$password]
        ])->first();

        if(empty( $admin )){

            return redirect()->back();
        }else{

            $token = bin2hex(random_bytes(64));
            DB::table('role')
                ->where('account', '=', $account)
                ->update([
                    'token' => $token,
                ]);

            $req->session()->put('account', $account);
            $req->session()->put('token', $token);


            return redirect()->route('backend');
        }

    }

}
