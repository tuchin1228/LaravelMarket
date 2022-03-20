<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class AboutApi extends Controller
{
    public function showHomeAbout()
    {
        $about = DB::select("SELECT * FROM about
                    WHERE enable = 1
                    AND showHome = 1
                    ORDER BY sort desc");
        return ['success' => true, 'about' => $about];
    }

    public function getabout()
    {
        $about = DB::select("SELECT * FROM about
        WHERE enable = 1
        ORDER BY sort desc");
        return ['success' => true, 'about' => $about];
    }
}
