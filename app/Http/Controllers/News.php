<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class News extends Controller
{
    public function index()
    {
        $data['articles'] = DB::table('articles')
            ->orderBy('created_at', 'desc')->paginate(5);
        return view('News.News', $data);
    }

    public function add_index()
    {
        return view('News.AddNews');
    }

    public function edit_news($article_id)
    {
        $article = DB::select("SELECT * FROM articles
                    WHERE article_id = '$article_id'");
        if (empty($article)) {
            return redirect()->route('News');
        }
        $data['article'] = $article[0];
        $data['article_id'] = $article_id;
        return view('News.EditNews', $data);
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

    //新增最新消息
    public function add_news(Request $req)
    {
        // return $req;
        // if (!$req->title) {
        //     return redirect()->back()->withInput()->withErrors(['msg' => '標題或內容為空!']);
        // }
        if ($req->hasFile('formFile')) {
            $image = $req->file('formFile');
            $file_path = $image->store('public/news/' . $req->article_id);
            $data['banner'] = $image->hashname();
        }

        $data['title'] = $req->title;
        $data['content'] = $req->content;
        $data['article_id'] = $req->article_id;
        $data['created_at'] = date('Y-m-d h:i:s', time());

        DB::table("articles")
            ->insert($data);

        return redirect()->back();

    }

    //編輯最新消息
    public function update_news(Request $req)
    {

        $data['title'] = $req->title;
        $data['content'] = $req->content;

        if ($req->hasFile('formFile')) {

            Storage::delete('/public/news/' . $req->editId . '/' . $req->oldbanner);

            $image = $req->file('formFile');
            $file_path = $image->store('public/news/' . $req->editId);
            $data['banner'] = $image->hashname();
            DB::table("articles")
                ->where("article_id", $req->editId)
                ->update([
                    'title' => $data['title'],
                    'content' => $data['content'],
                    'banner' => $data['banner'],
                ]);

            return redirect()->route('News');
        } else {
            DB::table("articles")
                ->where("article_id", $req->editId)
                ->update([
                    'title' => $data['title'],
                    'content' => $data['content'],
                ]);

            return redirect()->route('News');

        }
    }

    public function delete_news(Request $req)
    {
        if (empty($req->deleteId)) {
            return redirect()->route('News');
        }
        // return $req->deleteId;
        $deleteId = $req->deleteId;
        Storage::deleteDirectory("/public/news/$deleteId");

        $result = DB::table('articles')->where('article_id', $req->deleteId)->delete();

        return redirect()->route('News');

    }
}
