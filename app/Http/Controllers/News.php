<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class News extends Controller
{
    public function index()
    {
        $data['category'] = DB::table('articles_category')
            ->orderBy('articles_cate_sort', 'desc')->get();
        $data['articles'] = DB::table('articles')
            ->leftJoin('articles_category', 'articles.cateId', '=', 'articles_category.id')
            ->orderBy('created_at', 'desc')->paginate(5);
        $data['cateId'] = 'all';
        return view('News.News', $data);
    }

    //顯示某分類最新消息
    public function cate_index($cateId)
    {
        if ($cateId == 'all') {
            return redirect()->route('News');
        }
        $data['category'] = DB::table('articles_category')
            ->orderBy('articles_cate_sort', 'desc')->get();
        $data['articles'] = DB::table('articles')
            ->leftJoin('articles_category', 'articles.cateId', '=', 'articles_category.id')
            ->where('articles.cateId', $cateId)
            ->orderBy('created_at', 'desc')->paginate(5);
        $data['cateId'] = $cateId;
        return view('News.News', $data);
    }

    public function add_index()
    {
        $data['categories'] = DB::select("SELECT * FROM articles_category
                    WHERE enable = 1
                    ORDER BY articles_cate_sort desc");
        return view('News.AddNews', $data);
    }

    public function edit_news($article_id)
    {
        $data['categories'] = DB::select("SELECT * FROM articles_category
                    WHERE enable = 1
                    ORDER BY articles_cate_sort desc");

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
            $file_path = $image->store("public/uploads/$article_id");
            $filename = $image->hashName();
            DB::table('image_list')->insert([
                'filename' => $filename,
                'article_id' => $article_id,
                'date' => $date,
            ]);

            return ['location' => request()->getSchemeAndHttpHost() . "/" . env('PROJECT_NAME') . "/public/storage/uploads/$article_id/$filename"];

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
        $data['cateId'] = $req->cateId;
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

        DB::table('articles')->where('article_id', $req->deleteId)->delete();
        DB::table('image_list')->where('article_id', $req->deleteId)->delete();


        return redirect()->route('News');

    }

    public function imagenone()
    {
        //上傳後沒發布的圖片
        $notitle_images = DB::select("SELECT image_list.* from image_list
                            LEFT JOIN articles
                              ON image_list.article_id = articles.article_id
                            WHERE image_list.article_id
                            NOT IN(SELECT article_id FROM articles)");

        //更新傳圖未上傳 || 上傳後刪除 (文章內無顯示的圖)
        $hastitle_images = DB::select("SELECT image_list.*,articles.title,articles.created_at  FROM image_list
                              LEFT JOIN articles
                              ON image_list.article_id = articles.article_id
                              WHERE articles.content NOT LIKE CONCAT('%', image_list.filename, '%')");
        return view('News.Imagenone', ['notitle_images' => $notitle_images, 'hastitle_images' => $hastitle_images]);

    }

    public function deletenotuse(Request $req)
    {
        if (!isset($req->type)) {
            return redirect()->route('ImageNone');
        }

        if ($req->type == 1) {
            $notitle_images = DB::select("SELECT image_list.* from image_list
                            LEFT JOIN articles
                              ON image_list.article_id = articles.article_id
                            WHERE image_list.article_id
                            NOT IN(SELECT article_id FROM articles)");
            foreach ($notitle_images as $image) {
                Storage::delete("/public/uploads/$image->article_id/$image->filename");
                $fileInFolder = Storage::allFiles("/public/uploads/$image->article_id/$image->filename");
                if (empty($fileInFolder)) {
                    Storage::deleteDirectory("/public/uploads/$image->article_id");
                }

                DB::table('image_list')
                    ->where('article_id', $image->article_id)
                    ->where('filename', $image->filename)
                    ->delete();
            }
            return redirect()->route('ImageNone');

        } else {
            $hastitle_images = DB::select("SELECT image_list.*,articles.title,articles.created_at FROM image_list
                              LEFT JOIN articles
                              ON image_list.article_id = articles.article_id
                              WHERE articles.content NOT LIKE CONCAT('%', image_list.filename, '%')");
            foreach ($hastitle_images as $image) {
                Storage::delete("/public/uploads/$image->article_id/$image->filename");
                $fileInFolder = Storage::allFiles("/public/uploads/$image->article_id/$image->filename");
                if (empty($fileInFolder)) {
                    Storage::deleteDirectory("/public/uploads/$image->article_id");
                }

                DB::table('image_list')
                    ->where('article_id', $image->article_id)
                    ->where('filename', $image->filename)
                    ->delete();
            }
            return redirect()->route('ImageNone');

        }
    }

    public function category_news()
    {
        $data['categories'] = DB::select("SELECT * FROM articles_category
                                          ORDER BY articles_cate_sort desc");
        return view('News.Category', $data);
    }

    public function category_add()
    {
        return view('News.AddCategory');
    }

    public function add_category(Request $req)
    {
        // return $req;
        if (empty($req->title)) {
            return redirect()->route('CategoryAdd')->withInput()->withErrors(['AddError' => '未輸入分類名稱']);
        }

        DB::table('articles_category')
            ->insert([
                'articles_cate_title' => $req->title,
                'articles_cate_sort' => $req->sort,
                'enable' => $req->enable,
            ]);

        return redirect()->route('CategoryNews');

    }

    public function category_update(Request $req)
    {
        // return $req;
        if (empty($req->title)) {
            return redirect()->route('CategoryNews')->withInput()->withErrors(['updateError' => '未輸入分類名稱', 'editId' => $req->editId]);
        }
        DB::table('articles_category')
            ->where('id', $req->editId)
            ->update([
                'articles_cate_title' => $req->title,
                'articles_cate_sort' => $req->sort,
                'enable' => $req->enable,
            ]);
        return redirect()->route('CategoryNews');

    }

    public function category_delete(Request $req)
    {
        if (empty($req->deleteId)) {
            return redirect()->route('CategoryNews');
        }
        $deleteId = $req->deleteId;
        $exitNews = DB::select("SELECT * FROM articles
                    WHERE cateId =  '$deleteId'");
        if (!empty($exitNews)) {
            //分類尚有文章不能刪
            return redirect()->route('CategoryNews')->withInput()->withErrors(['deleteError' => '分類尚有貼文無法刪除']);
        } else {
            DB::table('articles_category')
                ->where('id', $deleteId)
                ->delete();
        }
        return redirect()->route('CategoryNews');

    }

}
