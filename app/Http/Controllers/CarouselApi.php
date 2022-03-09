<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CarouselApi extends Controller
{
    public function index()
    {
        $carousels = DB::table("carousels")
            ->where('enable', 1)
            ->orderBy('sort', 'desc')
            ->get();
        return ['success' => true, 'carousels' => $carousels];

    }
}
