<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartApi extends Controller
{
    public function AddToCart(Request $req)
    {
        // return $req;
        $product = $req->product;
        $Additions = $req->productAddition;
        // return $product['productId'];

        $Cart = [];
        //商品檢查是否存在購物車直接改數量，需檢查是否超過最大量
        $ProductInCart =  DB::select("SELECT * FROM user_cart
                                      WHERE productId = '{$product['productId']}'
                                      AND productDetailId = '{$product['productDetailId']}'
                                      AND type = 0
                                      AND userId = '{$req['userId']}'");
        if (!empty($ProductInCart)) {
            if ($product['max_quantity'] && ($req['productCount'] + $ProductInCart[0]->count >  $product['max_quantity'])) {
                return ['success' => false, 'msg' => '超過商品最大量'];
            }
            DB::table('user_cart')
                ->where([
                    ['userId', $req['userId']],
                    ['productId', $product['productId']],
                    ['productDetailId', $product['productDetailId']],
                    ['type', 0]
                ])
                ->update([
                    'count' =>  $req['productCount'] + $ProductInCart[0]->count
                ]);
        } else {
            $cartProduct['productId'] = $product['productId'];
            $cartProduct['productDetailId'] = $product['productDetailId'];
            // $cartProduct['productAdditionId'] = $product['productAdditionId'];
            $cartProduct['type'] = 0;
            $cartProduct['count'] = $req['productCount'];
            $cartProduct['userId'] = $req['userId'];

            $Cart[] = $cartProduct;
            DB::table('user_cart')
                ->Insert($cartProduct);
        }


        //加購品檢查是否存在購物車直接改數量並且檢查是否超過最大數量
        $ProductAdditionInCart =  DB::select("SELECT * FROM user_cart
                                       WHERE type = 1
                                      AND userId = '{$req['userId']}'");
        if (!empty($ProductAdditionInCart)) {
            foreach ($Additions as $key => $addition) {
                $count = 0;
                foreach ($ProductAdditionInCart as $dbkey => $dbAddition) {
                    if (
                        // $product['productId'] == $dbAddition->productId &&
                        $addition['productAdditionId'] == $dbAddition->productAdditionId
                    ) {
                        $count++;
                        if ($dbAddition->count + $addition['tempCount'] <= $addition['max_quantity']) {
                            DB::table('user_cart')
                                ->where([
                                    ['userId', $req['userId']],
                                    ['productAdditionId', $addition['productAdditionId']],
                                    ['type', 1]
                                ])
                                ->update([
                                    'count' => $dbAddition->count + $addition['tempCount']
                                ]);
                        }
                    }
                }
                if ($count == 0) { //購物車沒有此商品
                    DB::table('user_cart')
                        ->Insert([
                            'productId' => $product['productId'],
                            'productAdditionId' => $addition['productAdditionId'],
                            'productDetailId' => $product['productDetailId'],
                            'type' => 1,
                            'userId' => $req['userId'],
                            'count' => $addition['tempCount'],
                        ]);
                }
            }
        } else {
            $ProductAdditin = [];
            foreach ($Additions as $key => $Addition) {
                $temp = [];
                $temp['productId'] = $product['productId'];
                $temp['productAdditionId'] = $Addition['productAdditionId'];
                $temp['productDetailId'] = $product['productDetailId'];
                $temp['type'] = 1;
                $temp['userId'] = $req['userId'];
                $temp['count'] = $Addition['tempCount'];
                $ProductAdditin[] = $temp;
            }

            DB::table('user_cart')
                ->Insert($ProductAdditin);
        }





        return ['success' => true, 'msg' => '成功加入購物車'];
    }


    public function getcart(Request $req)
    {
        // return $req;
        $userId = $req->userId;
        $cartProduct =  DB::select("SELECT * FROM user_cart AS A
                                    LEFT JOIN product AS B
                                    ON A.productId = B.productId
                                    LEFT JOIN product_detail AS C
                                    ON A.productDetailId = C.productDetailId
                                    LEFT JOIN image_list AS D
                                    ON A.productId = D.product_id
                                    WHERE A.type = 0
                                    AND A.userId = '$userId'
                                    AND (D.product_type = 'product' OR D.product_type IS NULL)
                                    GROUP BY A.productDetailId
                    ");
        $cartProductAddition =  DB::select("SELECT * FROM user_cart AS A
                                            LEFT JOIN (SELECT B.* ,C.addition_price FROM product_addtional AS B
                                                        LEFT JOIN product_addtional_detail AS C
                                                        ON B.productAdditionId = C.productAdditionId) AS B
                                            ON A.productAdditionId = B.productAdditionId
                                            WHERE A.type = 1
                                            AND A.userId = '$userId'");


        return ['success' => true, 'products' => $cartProduct, 'productAdditions' => $cartProductAddition];
    }

    public function SetCartProductCount(Request $req)
    {
        // return $req;
        $count = $req->count;
        $productId = $req->productId;
        $productDetailId = $req->productDetailId;
        $userId = $req->userId;
        if ($count > 0) {
            DB::table('user_cart')
                ->where([
                    ['productId', $productId],
                    ['productDetailId', $productDetailId],
                    ['type', 0],
                    ['userId', $userId]
                ])
                ->update([
                    'count' => $count
                ]);
        } else {
            DB::table('user_cart')
                ->where([
                    ['productId', $productId],
                    ['productDetailId', $productDetailId],
                    ['userId', $userId]
                ])
                ->delete();
        }


        return ['success' => true, 'msg' => '數量更新成功'];
    }

    public  function SetCartProductAdditionCount(Request $req)
    {
        // return $req;
        $count = $req->count;
        $productId = $req->productId;
        $productDetailId = $req->productDetailId;
        $productAdditionId = $req->productAdditionId;
        $userId = $req->userId;
        if ($count > 0) {
            DB::table('user_cart')
                ->where([
                    ['productId', $productId],
                    ['productDetailId', $productDetailId],
                    ['type', 1],
                    ['userId', $userId]
                ])
                ->update([
                    'count' => $count
                ]);
        } else {
            DB::table('user_cart')
                ->where([
                    ['productId', $productId],
                    ['productAdditionId', $productAdditionId],
                    ['type', 1],
                    ['userId', $userId]
                ])
                ->delete();
        }

        return ['success' => true, 'msg' => '數量更新成功'];
    }

    public function RemoveCartProduct(Request $req)
    {
        // return $req;
        $userId = $req->userId;
        $product = $req->product;
        if ($product['type'] == 0) { //商品
            DB::table('user_cart')
                ->where([
                    ['productId', $product['productId']],
                    ['productDetailId', $product['productDetailId']],
                    ['userId', $req->userId]
                ])
                ->delete();
        } else { //加購品
            DB::table('user_cart')
                ->where([
                    ['productId', $product['productId']],
                    ['productAdditionId', $product['productAdditionId']],
                    ['type', 1],
                    ['userId', $userId]
                ])
                ->delete();
        }
        
        return ['success' => true, 'msg' => '成功刪除'];
    }
}
