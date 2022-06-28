<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helper\LineFeature;

class OrderApi extends Controller
{    
    public function order(Request $req)
    {
        // return $req;
        $userId = $req->userId;
        $order =  DB::select("SELECT * FROM `order`
                              WHERE userId = '$userId'
                              ORDER BY created_at desc");

        $orderDetail = DB::select("SELECT * FROM `order` AS A
                                    LEFT JOIN order_detail AS B
                                    ON A.orderId = B.orderId
                                    LEFT JOIN product_detail AS C
                                    ON B.productDetailId = C.productDetailId
                                    LEFT JOIN product_detail AS D 
                                    ON B.productDetailId = D.productDetailId
                                    LEFT JOIN (SELECT * FROM image_list WHERE product_type = 'product' GROUP BY product_id) AS E
                                    ON B.productId = E.product_id
                                    WHERE A.userId = '$userId'
                                    AND E.product_type ='product' 
                                    OR E.product_type  IS NULL
                                    AND E.delete_at IS NULL
                                    AND B.type= 0
                                    GROUP BY A.orderId
        ");
        
        // return $orderDetail;
        return ['success' => true, 'order' => $order, 'orderDetail' => $orderDetail];
    }

    public function orderDetail(Request $req)
    {
        return $req;
    }

    public function GetOrderDetail(Request $req, $orderId)
    {
        // return $orderId;
        $userId = $req->userId;
        $order = DB::select("SELECT * FROM `order`
                                   WHERE userId = '$userId'
                                   AND orderId = '$orderId'");
        $orderDetail = DB::select("SELECT A.*,A.id AS Id,A.originPrice AS originSubTotal ,B.*,C.*,D.* FROM `order_detail` AS A
                                   LEFT JOIN (SELECT * FROM image_list WHERE product_type = 'product' GROUP BY product_id) AS B
                                   ON A.productId = B.product_id
                                   LEFT JOIN product_detail AS C
                                   ON A.productDetailId = C.productDetailId
                                   LEFT JOIN product_addtional AS D
                                   ON A.productAdditionId = D.productAdditionId
                                   WHERE A.orderId = '$orderId'
                                   AND A.type=0");

        $orderAdditionDetail =  DB::select("SELECT * FROM `order_detail`AS A
                                            LEFT JOIN (SELECT B.* ,C.addition_price FROM product_addtional AS B
                                                        LEFT JOIN product_addtional_detail AS C
                                                        ON B.productAdditionId = C.productAdditionId) AS B
                                            ON A.productAdditionId = B.productAdditionId
                                            WHERE A.type = 1
                                            AND A.orderId = '$orderId'");



        if (empty($order)) {
            return  ['success' => false, 'msg' => '查無訂單'];
        } else {
            return ['success' => true, 'order' => $order[0], 'orderDetail' => $orderDetail,'orderAdditionDetail'=>$orderAdditionDetail];
        }
    }

    public function pay(Request $req,$orderId){
        
        $orderDetail = DB::select("SELECT A.orderId, A.productId, A.count,B.filename as productImg,A.type, C.productDetailName,A.productPrice,A.productAdditionPrice ,D.imageFilename as productAdditionImg FROM `order_detail` AS A
                                   LEFT JOIN (SELECT * FROM image_list WHERE product_type = 'product' GROUP BY product_id) AS B
                                   ON A.productId = B.product_id
                                   LEFT JOIN product_detail AS C
                                   ON A.productDetailId = C.productDetailId
                                   LEFT JOIN product_addtional AS D
                                   ON A.productAdditionId = D.productAdditionId		
                                   WHERE A.orderId = '$orderId'
                                   ORDER BY A.type");
                                           
        $Suborder = [];
        
        // return $req->getSchemeAndHttpHost().'/'.env('PROJECT_NAME').'/public/storage/product/'.$orderDetail[0]->productId.'/product/'.$orderDetail[0]->productImg;
        foreach ($orderDetail as $key => $detail) {
            $Suborder[] = [
                'productName' => $detail->productDetailName,
                'quantity' => $detail->count,
                'price' => $detail->type == 1 ? round($detail->productAdditionPrice / $detail->count  ) :  round($detail->productPrice / $detail->count),
            ];
        }

        $LineFeature = new LineFeature();
        return $LineFeature->Linepay_online_submitorder(env('SHOP_NAME'),$orderId,$Suborder,$req->confirmUrl, "");
        // Linepay_online_submitorder($shopName, $orderId, $orderList, $confirmUrl, $cancelUrl)
        // return $feature->test;
    }

    public function ConfirmUrl(Request $req)
    {
        $transactionId = $req->transactionId;
        $orderId = $req->orderId;
        // return [$transactionId,$orderId];

        $order = DB::select("SELECT * FROM `order`
                             WHERE orderId = '$orderId'");
        // return $order;
        if(!empty($order)){

            $LineFeature = new LineFeature();
            $output = $LineFeature->Linepay_online_Confirm($transactionId, $order[0]->Total);
            $data = json_decode($output,true);
            if($data['returnCode'] == '0000'){
                // return '請款成功';
                DB::table('order')
                ->where('orderId',$orderId)
                ->update([
                    'payStatus'=>1,
                    'pay_at'=>  date('Y-m-d H:i:s', time())
                ]);

                return ['success'=>true,'msg'=>'付款成功','orderId'=>$orderId];
                
            }else{
                return ['success'=>false,'msg'=>'付款失敗'];
            }
            
        }else{
            return ['success'=>false,'msg'=>'訂單單號回傳錯誤'];
        }
        //利用訂單號查詢訂單金額
        // $amount = 180;


    }

    public function CancelOrder(Request $req,$orderId){
        // return $orderId;
        // DB::table('order')
        // ->where('orderId','12343123123423')
        // ->update([
        //     'orderId'=>3465456456,
        // ]);
        // return 123;
        $userId = $req->userId;
        $CancelReason = $req->CancelReason;
        $order = DB::select("SELECT * FROM `order`
                             WHERE orderId = '$orderId'
                             AND userId = '$userId'");
                             
        if(empty($order)){
            return ['success'=>false,'msg'=>'找不到此訂單'];
        }
        
        if($order[0]->payStatus == 0){
            $orderDetail = DB::select("SELECT type,productDetailId,productAdditionId,count FROM order_detail
                        WHERE orderId = '$orderId' ");
            foreach ($orderDetail as $key => $detail) {
                if($detail->type == 0){
                    $ProductCount = DB::table('product_detail')
                    ->where('productDetailId',$detail->productDetailId)
                    ->get();

                    DB::table('product_detail')
                    ->where('productDetailId',$detail->productDetailId)
                    ->update([
                        'quantity'=>$ProductCount[0]->quantity +  $detail->count
                    ]);
                }else{
                    $ProductAdditionCount =  DB::table('product_addtional')
                    ->where('productAdditionId',$detail->productAdditionId)
                    ->get();

                    DB::table('product_addtional')
                    ->where('productAdditionId',$detail->productAdditionId)
                    ->update([
                        'quantity'=>$ProductAdditionCount[0]->quantity + $detail->count
                    ]);
                }
            }

            DB::table('order')
            ->where('orderId',$orderId)
            ->update([
                'payStatus'=> 3, //已取消
                'cancel_at'=>date('Y-m-d H:i:s', time()) //取消申請時間
            ]);
            
            return ['success'=>true,'msg'=>'訂單取消成功'];

        }else{

            DB::table('order')
            ->where('orderId',$orderId)
            ->update([
                'payStatus'=> 2 , //申請取消
                'cancelReason'=>$CancelReason,
                'cancel_at'=>date('Y-m-d H:i:s', time()) //取消申請時間
            ]);

            return ['success'=>true,'msg'=>'訂單取消進入審核階段'];
        }
    }

}
