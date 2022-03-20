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

    public function getnews()
    {
        $newsCategory = DB::select("SELECT * FROM articles_category
                                    WHERE enable = 1
                                    ORDER BY articles_cate_sort");

        $news = DB::select("SELECT A.*,B.articles_cate_title FROM articles AS A
                            LEFT JOIN articles_category AS B
                            ON A.cateId = B.id");

        return ['success' => true, 'news' => $news, 'category' => $newsCategory];
    }

    public function getSpecificNews($id)
    {
        $newsCategory = DB::select("SELECT * FROM articles_category
                                    WHERE enable = 1
                                    ORDER BY articles_cate_sort");

        $news = DB::select("SELECT A.*,B.articles_cate_title FROM articles AS A
                            LEFT JOIN articles_category AS B
                            ON A.cateId = B.id
                            WHERE B.id = '$id'");

        return ['success' => true, 'news' => $news, 'category' => $newsCategory];
    }

    public function getNewsDetail($id, $articleId)
    {
        $newsDetail = DB::select("SELECT A.*,B.articles_cate_title FROM articles AS A
                            LEFT JOIN articles_category AS B
                            ON A.cateId = B.id
                            WHERE B.id = '$id'
                            AND A.article_id = '$articleId'
                            AND B.enable = 1 ");
        if (empty($newsDetail)) {
            return ['success' => false, 'newsDetail' => $newsDetail];
        } else {
            return ['success' => true, 'newsDetail' => $newsDetail[0]];
        }
    }
}
