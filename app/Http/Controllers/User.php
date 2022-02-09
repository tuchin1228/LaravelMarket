<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class User extends Controller
{
    public function index()
    {
        $data['Users'] = DB::table('User')
            ->orderBy('created_at', 'desc')->paginate(5);
        return view('User.User', $data);
    }
}
