<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class About extends Controller
{
    public function index()
    {
        $data['abouts'] = DB::select("SELECT * FROM about
                             ORDER BY sort desc ");

        return view('About.About', $data);
    }

    public function add_index()
    {

        return view('About.AddAbout');

    }

    //新增品牌介紹圖片
    public function uploadimage(Request $req, $about_id)
    {
        if ($req->hasFile('file')) {
            $image = $req->file('file');
            $file_path = $image->store("public/about/$about_id");
            $filename = $image->hashName();
            DB::table('image_list')->insert([
                'filename' => $filename,
                'about_type' => 1,
                'about_id' => $about_id,
                'date' => now(),
            ]);

            return ['location' => request()->getSchemeAndHttpHost() . "/" . env('PROJECT_NAME') . "/public/storage/about/$about_id/$filename"];

        }
    }

    public function add_About(Request $req)
    {
        // return $req;
        if (!$req->hasFile('formFile')) {
            return redirect()->back()->withInput()->withErrors(['noUploadImage' => '尚未上傳圖片']);
        }
        $data['title'] = $req->title;
        $data['subtitle'] = $req->subtitle;
        $data['intro'] = $req->intro;
        $data['content'] = $req->content;
        $data['linkName'] = $req->linkName;
        $data['link'] = $req->link;
        $data['about_id'] = $req->about_id;
        $data['enable'] = $req->enable;
        $data['showHome'] = $req->showHome;
        $data['sort'] = $req->sort;
        $data['created_at'] = now();
        $about_id = $data['about_id'];
        $image = $req->file('formFile');
        $file_path = $image->store("public/HomeAbout/$about_id");
        $data['filename'] = $image->hashName();

        DB::table('about')
            ->insert($data);
        return redirect()->route('About');
    }

    public function Edit_index($about_id)
    {
        $about = DB::select("SELECT * FROM about
                                    WHERE about_id = $about_id");
        $data['about'] = $about[0];
        return view('About.EditAbout', $data);

    }

    public function Edit_About(Request $req)
    {
        // return $req;

        $about_id = $req->about_id;

        if (!$req->hasFile('formFile')) {

            $data['title'] = $req->title;
            $data['subtitle'] = $req->subtitle;
            $data['intro'] = $req->intro;
            $data['content'] = $req->content;
            $data['linkName'] = $req->linkName;
            $data['link'] = $req->link;
            $data['sort'] = $req->sort;
            $data['enable'] = $req->enable;
            $data['showHome'] = $req->showHome;
            DB::table('about')
                ->where('about_id', $about_id)
                ->update($data);
            return redirect()->route('About');

        } else {

            Storage::delete('/public/HomeAbout/' . $about_id . '/' . $req->filename);
            $image = $req->file('formFile');
            $file_path = $image->store('public/HomeAbout/' . $about_id);
            $data['filename'] = $image->hashname();
            $data['title'] = $req->title;
            $data['subtitle'] = $req->subtitle;
            $data['intro'] = $req->intro;
            $data['content'] = $req->content;
            $data['linkName'] = $req->linkName;
            $data['link'] = $req->link;
            $data['sort'] = $req->sort;
            $data['enable'] = $req->enable;
            $data['showHome'] = $req->showHome;
            DB::table('about')
                ->where('about_id', $about_id)
                ->update($data);
            return redirect()->route('About');

        }
    }

    public function Delete_About(Request $req)
    {
        // return $req;
        $deleteId = $req->deleteId;

        Storage::deleteDirectory('/public/HomeAbout/' . $deleteId);

        DB::table('about')
            ->where('about_id', $deleteId)
            ->delete();

        return redirect()->route('About');

    }

    public function imagenone()
    {

    }

}
