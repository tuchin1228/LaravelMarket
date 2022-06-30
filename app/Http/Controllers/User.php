<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class User extends Controller
{
    public function index()
    {
        $data['Users'] = DB::table('user')
            ->orderBy('created_at', 'desc')->paginate(20);
        return view('User.User', $data);
    }

    public function edit_index($userId)
    {
        $User = DB::table('user')
            ->where('id', $userId)
            ->get();
        $data['User'] = $User[0];
        return view('User.EditUser', $data);

    }

    public function edit_user(Request $req)
    {
        if (empty($req->editId)) {
            return redirect()->back();
        }
        DB::table("user")
            ->where('id', $req->editId)
            ->update([
                'email' => $req->email ? $req->email : null,
                'country' => $req->country ? $req->country : null,
                'area' => $req->area ? $req->area : null,
                'address' => $req->address ? $req->address : null,
            ]);
        return redirect()->route('User');
    }

    public function delete_user(Request $req)
    {
        if (empty($req->deleteId)) {
            return redirect()->back();
        }
        DB::table("user")
            ->where('id', $req->deleteId)
            ->delete();
        return redirect()->route('User');

    }

    public function search_user($keyword)
    {
        if (empty($keyword)) {
            return redirect()->back();
        }
        $data['Users'] = DB::table("user")
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->orwhere('phone', 'LIKE', '%' . $keyword . '%')
            ->orwhere('email', 'LIKE', '%' . $keyword . '%')
            ->paginate(20);
        return view('User.User', $data);

    }
}
