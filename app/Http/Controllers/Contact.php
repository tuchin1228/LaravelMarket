<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Contact extends Controller
{

    public function contact_category()
    {

        $data['categories'] = DB::select("SELECT * FROM contact_category
                                          ORDER BY sort desc");
        return view('Contact.ContactCategory', $data);
    }

    public function category_add(Request $req)
    {
        // return $req;
        $title = $req->title;
        $sort = $req->sort;

        DB::table('contact_category')
            ->insert([
                'title' => $title,
                'sort' => $sort
            ]);
        return redirect()->back();
    }


    public function category_edit(Request $req)
    {
        // return $req;
        $title = $req->title;
        $categoryId = $req->categoryId;
        $sort = $req->sort;

        DB::table('contact_category')
            ->where('id', $categoryId)
            ->update([
                'title' => $title,
                'sort' => $sort
            ]);
        return redirect()->back();
    }
    public function category_delete(Request $req)
    {
        // return $req;
        $categoryId = $req->categoryId;
        $hasContact = DB::select("SELECT * FROM contact
                    WHERE contactCategoryId = '$categoryId'");
        if (!empty($hasContact)) {
            return redirect()->route('ContactCategory')->withInput()->withErrors(['deleteError' => '分類尚有訊息']);
        } else {
            DB::table('contact_category')->where('id', $categoryId)->delete();
            return redirect()->route('ContactCategory');
        }
    }

    /***************  所有聯絡我們  *****************/
    public function contact()
    {
        $data['contacts'] = DB::table('contact')
            ->leftJoin('contact_category', 'contact.contactCategoryId', '=', 'contact_category.id')
            ->select('contact.*', 'contact_category.title')
            ->orderBy('created_at', 'desc')->paginate(10);
        return view('Contact.Contact', $data);
    }

    public function contact_update(Request $req)
    {
        // return $req;
        $status = $req->status;
        $remark = $req->remark;
        $contactId = $req->contactId;
        DB::table('contact')
            ->where('id', $contactId)
            ->update([
                'status' => $status,
                'remark' => $remark
            ]);
        return redirect()->back();
    }

    public function  contact_delete(Request $req)
    {
        // return $req;
        $contactId = $req->contactId;
        DB::table('contact')
            ->where('id', $contactId)
            ->delete();
        return redirect()->back();
    }


    public function contact_status($status)
    {
        // return $status == 1;
        if ($status == 'all') {
            return redirect()->route('Contact');
        }

        if ($status == 1 || $status == 2  || $status == 3 || $status == 4) {

            $data['status'] = $status;
            $data['contacts'] = DB::table('contact')
                ->leftJoin('contact_category', 'contact.contactCategoryId', '=', 'contact_category.id')
                ->select('contact.*', 'contact_category.title')
                ->where('status', $status)
                ->orderBy('created_at', 'desc')->paginate(10);

            return view('Contact.Contact', $data);
        } else {
            return redirect()->route('Contact');
        }
    }

    public function contact_search($keyword)
    {
        $data['keyword'] = $keyword;
        $data['contacts'] = DB::table('contact')
            ->leftJoin('contact_category', 'contact.contactCategoryId', '=', 'contact_category.id')
            ->select('contact.*', 'contact_category.title')
            ->where('name', 'LIKE', '%' . $keyword . '%')
            ->orwhere('phone', 'LIKE', '%' . $keyword . '%')
            ->orwhere('email', 'LIKE', '%' . $keyword . '%')
            ->orderBy('created_at', 'desc')->paginate(10);

        return view('Contact.Contact', $data);
    }
}
