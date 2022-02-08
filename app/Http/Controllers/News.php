<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class News extends Controller
{
    public function index()
    {
        return view('News.News');
    }

    public function add_index()
    {
        return view('News.AddNews');
    }

    //上傳最新消息內容圖片
    public function uploadimage(Request $req, $article_id, $date)
    {
        if ($req->hasFile('file')) {
            $image = $req->file('file');
            $file_path = $image->store('public/uploads');
            $filename = $image->hashName();
            DB::table('image_list')->insert([
                'filename' => $filename,
                'article_id' => $article_id,
                'date' => $date,
            ]);

            return ['location' => request()->getSchemeAndHttpHost() . "/storage/uploads/$filename"];

        }

    }
}
