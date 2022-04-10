<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductApi extends Controller
{
    public function index()
    {
        $category =  DB::select("SELECT * FROM product_category
                    WHERE enable = 1
                    ORDER BY sort desc");

        // $product =  DB::table("product")
        //             ->leftJoin("product_detail","product.productId","=","product_detail.productId")
        //             ->select("product.*","product_detail.productDetailId","product_detail.productDetailName","product_detail.enable")
        //             ->selectRaw("MIN(product_detail.salePrice) AS salePrice")
        //             ->where([
        //                 ['product.enable','=',1],
        //                 ['product_detail.enable','=',1]
        //             ])
        //             ->get();

        // ,"B.productDetailId","B.productDetailName","MIN(B.salePrice) AS salePrice","B.enable"
        $product = DB::select("SELECT A.productId, A.productName, A.productCateId, A.productTag, 
                                A.productIntro, A.sort, A.enable, A.description, A.composition, A.buyflow, A.created_at, 
                                B.productDetailId,B.productDetailName,CONCAT(MIN(B.salePrice)) AS salePrice , B.originPrice , B.enable FROM product AS A
                                LEFT JOIN  product_detail  AS B
                                ON A.productId = B.productId
                                WHERE A.enable = 1
                                AND B.enable = 1
                                AND A.delete_at IS NULL
                                AND B.delete_at IS NULL
                                GROUP BY A.productId");

        $productImages = DB::select("SELECT * FROM image_list
                            where product_type = 'product'");
        // 
        // GROUP BY A.productId
        return ['success' => true, 'category' => $category, 'product' => $product, 'productImages' => $productImages];
    }


    public function GetProductByCategory($categoryId)
    {
        // return $category;
        $category =  DB::select("SELECT * FROM product_category
                                 WHERE enable = 1
                                 ORDER BY sort desc");

        $product = DB::select("SELECT A.productId, A.productName, A.productCateId, A.productTag, 
                                A.productIntro, A.sort, A.enable, A.description, A.composition, A.buyflow, A.created_at, 
                                B.productDetailId,B.productDetailName,CONCAT(MIN(B.salePrice)) AS salePrice , B.originPrice , B.enable FROM product AS A
                                LEFT JOIN  product_detail  AS B
                                ON A.productId = B.productId
                                WHERE A.enable = 1
                                AND A.productCateId = '$categoryId'
                                AND B.enable = 1
                                AND A.delete_at IS NULL
                                AND B.delete_at IS NULL
                                GROUP BY A.productId");


        $productImages = DB::select("SELECT * FROM image_list AS A
                                     LEFT JOIN product AS B
                                     ON A.product_id = B.productId
                                     where product_type = 'product'
                                     AND B.productCateId  = '$categoryId'");

        return ['success' => true, 'category' => $category, 'product' => $product, 'productImages' => $productImages];
    }

    public function GetProduct($productId)
    {

        $product = DB::select("SELECT * FROM product AS A
                                LEFT JOIN product_tag  AS B
                                ON A.productTag = B.id
                                WHERE enable = 1
                                AND A.delete_at IS NULL
                                AND productId = '$productId'");

        $productDetail = DB::select("SELECT * FROM product_detail
                                     WHERE enable = 1
                                     AND productId = '$productId'
                                     AND delete_at IS NULL
                                     ORDER BY sort desc ");

        $productAddition = DB::select("SELECT A.*,B.productId,B.addition_price FROM product_addtional AS A
                                       LEFT JOIN product_addtional_detail AS B
                                       ON A.productAdditionId = B.productAdditionId
                                       WHERE A.enable = 1
                                       AND now() >= A.startTime
                                       AND now() < A.endTime
                                       AND A.quantity > A.max_quantity
                                       AND (B.productId = '$productId' || A.forAll = 1)
                                       AND A.delete_at IS NULL
                                       AND B.delete_at IS NULL
                                       ORDER BY A.sort desc ");      

        $productImages = DB::select("SELECT * FROM image_list AS A
                                    LEFT JOIN product AS B
                                    ON A.product_id = B.productId
                                    where product_type = 'product'
                                    AND B.productId  = '$productId'");

        return ['success' => true, 'product' => !empty($product) ? $product[0] : null, 'productDetail' => $productDetail , 'productAddition'=>$productAddition , 'productImages' => $productImages];
    }
}
