<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactApi extends Controller
{
    public function GetContactCategry()
    {
        $category = DB::select("SELECT * FROM contact_category");
        return ['success' => true, 'category' => $category];
    }

    public function submitContact(Request $req)
    {
        // return $req;
        $data['contactCategoryId'] = $req->contactCategoryId;
        $data['direction'] = $req->direction;
        $data['email'] = $req->email;
        $data['name'] = $req->name;
        $data['phone'] = $req->phone;
        $data['status'] = 1;
        DB::table('contact')
            ->insert($data);
        return ['success' => true];
    }
}
