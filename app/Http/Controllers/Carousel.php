<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Carousel extends Controller
{
    public function index()
    {
        $data['carousels'] = DB::table("carousels")
            ->orderBy('sort', 'desc')
            ->get();
        return view('Carousel.Carousel', $data);
    }

    //新增輪播頁面
    public function add_index()
    {
        return view('Carousel.AddCarousel');
    }

    //編輯輪播頁面
    public function edit_index($editId)
    {
        $carousel = DB::select("SELECT * FROM carousels
                                        WHERE id = '$editId'");

        $data['carousel'] = $carousel[0];
        if (empty($data['carousel'])) {
            return redirect()->back();
        } else {
            return view('Carousel.EditCarousel', $data);

        }

    }

    public function add_carousel(Request $req)
    {
        // return $req;
        if (!$req->hasFile('formFile')) {
            return redirect()->back()->withInput()->withErrors(['msg' => '尚未上傳圖片']);
        }
        $image = $req->file('formFile');
        $file_path = $image->store('public/Carousel');

        $data['title'] = $req->title;
        $data['url'] = $req->url;
        $data['size'] = $req->size;
        $data['enable'] = $req->enable ? 1 : 0;
        $data['sort'] = $req->sort;
        $data['filename'] = $image->hashname();

        DB::table("carousels")
            ->insert($data);

        return redirect()->back();
    }

    public function update_carousel(Request $req)
    {

        if (!$req->hasFile('formFile')) {
            // return $req;
            $data['title'] = $req->title;
            $data['url'] = $req->url;
            $data['size'] = $req->size;
            $data['enable'] = $req->enable ? 1 : 0;
            $data['sort'] = $req->sort;
            DB::table("carousels")
                ->where('id', $req->editId)
                ->update([
                    'title' => $data['title'],
                    'url' => $data['url'],
                    'size' => $data['size'],
                    'enable' => $data['enable'],
                    'sort' => $data['sort'],
                ]);
        } else {

            Storage::delete('/public/Carousel/' . $req->filename);

            $image = $req->file('formFile');
            $file_path = $image->store('public/Carousel');
            $data['title'] = $req->title;
            $data['url'] = $req->url;
            $data['size'] = $req->size;
            $data['enable'] = $req->enable ? 1 : 0;
            $data['sort'] = $req->sort;
            $data['filename'] = $image->hashname();

            DB::table("carousels")
                ->where('id', $req->editId)
                ->update([
                    'title' => $data['title'],
                    'url' => $data['url'],
                    'size' => $data['size'],
                    'enable' => $data['enable'],
                    'sort' => $data['sort'],
                    'filename' => $data['filename'],
                ]);

        }

        return redirect()->route('Carousel');

    }

    public function delete_carousel(Request $req)
    {
        $deleteId = $req->deleteId;
        $filename = $req->filename;
        // return $deleteId;
        $result = DB::table('carousels')->where('id', $deleteId)->delete();
        Storage::delete('/public/Carousel/' . $filename);
        return redirect()->back();

    }
}
