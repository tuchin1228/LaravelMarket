<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Product extends Controller
{
    //顯示所有商品分類頁面
    public function product_category()
    {
        $data['categories'] = DB::select("SELECT * FROM product_category
                                          ORDER BY sort desc");
        return view('Product.Category', $data);
    }

    //新增商品分類頁面
    public function product_category_add_page()
    {
        return view('Product.AddCategory');
    }

    //新增商品邏輯
    public function product_category_add(Request $req)
    {
        if (empty($req->title)) {
            return redirect()->route('ProductCategoryAddPage')->withInput()->withErrors(['AddError' => '未輸入分類名稱']);
        }
        $data['productCateName'] = $req->title;
        $data['sort'] = $req->sort ? $req->sort : null;
        $data['enable'] = $req->enable;

        DB::table('product_category')
            ->insert($data);

        return redirect()->route('ProductCategoryAddPage');
    }

    //編輯商品邏輯
    public function product_category_edit(Request $req)
    {
        if (empty($req->title)) {
            return redirect()->route('ProductCategory')->withInput()->withErrors(['updateError' => '未輸入分類名稱', 'editId' => $req->editId]);
        }
        DB::table('product_category')
            ->where('id', $req->editId)
            ->update([
                'productCateName' => $req->title,
                'sort' => $req->sort,
                'enable' => $req->enable,
            ]);
        return redirect()->route('ProductCategory');

    }

    public function product_category_delete(Request $req)
    {
        if (empty($req->deleteId)) {
            return redirect()->route('ProductCategory');
        }
        $deleteId = $req->deleteId;
        $exitProduct = DB::select("SELECT * FROM product
                    WHERE productCateId =  '$deleteId'");
        if (!empty($exitProduct)) {
            //分類尚有商品不能刪
            return redirect()->route('ProductCategory')->withInput()->withErrors(['deleteError' => '分類尚有商品無法刪除']);
        } else {
            DB::table('product_category')
                ->where('id', $deleteId)
                ->delete();
        }
        return redirect()->route('ProductCategory');

    }

}
