<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NewsApi extends Controller
{
    public function twonews()
    {
        $news = DB::select("SELECT * FROM articles AS A
                                 LEFT JOIN articles_category AS B
                                 ON A.cateId = B.id
                                 WHERE B.enable = 1
                                 ORDER BY A.created_at desc
                                 LIMIT 2
                                 ");

        return ['success' => true, 'news' => $news];

    }
}
