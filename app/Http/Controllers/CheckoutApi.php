<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutApi extends Controller
{
    public function CheckOut(Request $req)
    {
        // return $req;
        $userId = $req->userId;
        $order['orderId'] = $this->RandomString(20);
        $order['userId'] = $userId;
        $order['payAddress'] = $req->PayAddress;
        $order['payArea'] = $req->PayAreaName;
        $order['payCity'] = $req->PayCityName;
        $order['payName'] = $req->PayName;
        $order['payPhone'] = $req->PayPhone;
        if ($req->consistent) {
            $order['receiveAddress'] = $req->PayAddress;
            $order['receiveArea'] = $req->PayAreaName;
            $order['receiveCity'] = $req->PayCityName;
            $order['receiveName'] = $req->PayName;
            $order['receivePhone'] = $req->PayPhone;
        } else {
            $order['receiveAddress'] = $req->ReceiveAddress;
            $order['receiveArea'] = $req->ReceiveAreaName;
            $order['receiveCity'] = $req->ReceiveCityName;
            $order['receiveName'] = $req->ReceiveName;
            $order['receivePhone'] = $req->ReceivePhone;
        }

        $order['payStatus'] = 0;
        $order['remark'] = $req->remark;


        $cartProduct = $req->cartProduct;
        $cartProductAddition = $req->cartProductAddition;


        $dbCart =  DB::select("SELECT * FROM user_cart AS A
                                LEFT JOIN product AS B
                                ON A.productId = B.productId
                                LEFT JOIN product_detail AS C
                                ON A.productDetailId = C.productDetailId
                                LEFT JOIN product_addtional AS D
                                ON A.productAdditionId = D.productAdditionId
                                LEFT JOIN product_addtional_detail AS E
								ON (D.productAdditionId = E.productAdditionId)
                                WHERE A.userId = '$userId'
                                AND (( A.type = 0 AND B.`enable` = 1 AND C.`enable`=1 AND C.quantity > A.count ) 
                                OR ( A.type = 1 AND B.`enable` = 1 AND C.`enable`=1 AND C.quantity > A.count AND D.`enable` = 1 AND D.quantity > A.count AND now() >= D.startTime AND now() < D.endTime ))
                            ");
        if (count($dbCart) !== (count($cartProduct) + count($cartProductAddition))) {
            return ['success' => false, 'msg' => '購物車包含已下架或售完的商品'];
        }
        // return $dbCart;
        $total = 0;
        $orderDetail = [];
        foreach ($dbCart as $key => $product) {

            $temp = [];
            $temp['orderId'] =  $order['orderId'];
            $temp['productId'] = $product->productId;
            $temp['productDetailId'] = $product->productDetailId;
            $temp['productAdditionId'] = $product->productAdditionId;
            $temp['count'] = $product->count;
            $temp['type'] = $product->type;

            if ($product->type == 0) { //商品
                $total += $product->salePrice ? $product->count * $product->salePrice : $product->count * $product->originPrice;

                $temp['originPrice'] = $product->count * $product->originPrice;
                $temp['productPrice'] = $product->salePrice ? $product->count * $product->salePrice : $product->count * $product->originPrice;
                $temp['productAdditionPrice'] = null;
            } else { //加購品
                $total += $product->forAll ?  $product->count * $product->forAllPrice : $product->count * $product->addition_price;
                $temp['productAdditionPrice'] = $product->forAll ?  $product->count * $product->forAllPrice : $product->count * $product->addition_price;
                $temp['originPrice'] =  null;
                $temp['productPrice'] = null;
            }

            $orderDetail[] = $temp;
        }
        $order['Total'] = $total;
        // return  $orderDetail;
        //order主檔寫入
        DB::table('order')->insert($order);
        //order_detail寫入
        DB::table('order_detail')->insert($orderDetail);

        // return '寫入';


        //更新商品庫存量
        DB::select("UPDATE product_detail A
                    JOIN(
                    SELECT A.productDetailId, (C.quantity- A.count) As productQuantity FROM user_cart AS A
                    LEFT JOIN product AS B
                    ON A.productId = B.productId
                    LEFT JOIN product_detail AS C
                    ON A.productDetailId = C.productDetailId
                    WHERE A.userId = '$userId'
                    AND ( A.type = 0 AND B.`enable` = 1 AND C.`enable`=1 AND C.quantity > A.count ) 
                    ) vals 
                    ON A.productDetailId = vals.productDetailId
                    SET quantity = productQuantity;
                    ");

        //更新加購品庫存量    
        DB::select("UPDATE product_addtional A
                    Join(
                    SELECT A.count,A.type, C.quantity As productQuantity, D.productAdditionId,  (D.quantity- A.count)  As productAdditionQuantity FROM user_cart AS A
                    LEFT JOIN product AS B
                    ON A.productId = B.productId
                    LEFT JOIN product_detail AS C
                    ON A.productDetailId = C.productDetailId
                    LEFT JOIN product_addtional AS D
                    ON A.productAdditionId = D.productAdditionId
                    WHERE A.userId = '$userId'
                    AND ( A.type = 1 AND B.`enable` = 1 AND C.`enable`=1 AND C.quantity > A.count AND D.`enable` = 1 AND D.quantity > A.count AND now() >= D.startTime AND now() < D.endTime )
                    ) vals 
                    ON A.productAdditionId = vals.productAdditionId
                    SET quantity = productAdditionQuantity");

        //清除購物車
        DB::table('user_cart')->where('userId', $userId)->delete();

        return ['success' => true, 'msg' => '訂單成立'];
    }


    public function RandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
