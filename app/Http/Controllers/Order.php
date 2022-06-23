<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Order extends Controller
{
    public function order(){
        // $data['orders'] = DB::select("SELECT * FROM `order`");
        $data['orders'] = DB::table("order")->orderBy('created_at', 'desc')->paginate(10);
        return view("Order.index",$data);
    }

    public function orderDetail($orderId){
        // return $orderId;

        $data['order'] = DB::table("order")->where('orderId',$orderId)->first();

        $tempOrderDetail =  DB::table("order_detail")->where("orderId",$orderId)->get();

        $data['orderDetails'] = $tempOrderDetail->map(function ($item) {
            if($item->type == 0){
                $productDetail =  DB::table("product_detail")->where('productDetailId',$item->productDetailId)->first();
                $productImage =  DB::table('image_list')->where([
                    ['product_id',$item->productId],
                    ['product_type','product']
                ])->first();
                $item->name = $productDetail->productDetailName;
                $item->filename  = $productImage->filename;
                
            }else{
                $productAddition =  DB::table("product_addtional")->where('productAdditionId',$item->productAdditionId)->first();
                $productImage =  DB::table('image_list')->where([
                    ['product_id',$item->productId]
                ])->first();

                $item->name = $productAddition->productAdditionName;
                $item->filename  = $productAddition->imageFilename;
                
            }
            return $item;
        });
        // return  $data['orderDetails'] ;
     
        // return $data;
        return view('Order.orderDetail',$data);
    }
}
