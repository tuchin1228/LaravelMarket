<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    /**************** 商品標籤 ***************/

    // 顯示所有標籤頁面
    public function product_tag()
    {
        $data['tags'] = DB::select("SELECT * FROM product_tag");
        return view('Product.Tag', $data);

    }

    //新增商品標籤頁面
    public function product_tag_add_page()
    {
        return view('Product.AddTag');
    }

    //新增商品標籤
    public function product_tag_add(Request $req)
    {
        // return $req;
        if (empty($req->title)) {
            return redirect()->route('ProductTagAddPage')->withInput()->withErrors(['AddError' => '未輸入標籤名稱']);
        }
        $data['tagName'] = $req->title;
        $data['bgColor'] = $req->bgColor;
        $data['textColor'] = $req->textColor;

        DB::table('product_tag')
            ->insert($data);

        return redirect()->route('ProductTagAddPage');

    }

    //編輯商品標籤
    public function product_tag_edit(Request $req)
    {
        // return $req;
        if (empty($req->title)) {
            return redirect()->route('ProductTag')->withInput()->withErrors(['updateError' => '未輸入分類名稱', 'editId' => $req->editId]);
        }
        DB::table('product_tag')
            ->where('id', $req->editId)
            ->update([
                'tagName' => $req->title,
                'bgColor' => $req->bgColor,
                'textColor' => $req->textColor,
            ]);
        return redirect()->route('ProductTag');

    }

    //刪除商品標籤
    public function product_tag_delete(Request $req)
    {
        if (empty($req->deleteId)) {
            return redirect()->route('ProductTag');
        }

        $deleteId = $req->deleteId;

        // 先將有此標籤的商品標籤清空
        DB::table("product")
            ->where('productTag', $deleteId)
            ->update([
                'productTag' => null,
            ]);
        DB::table('product_tag')
            ->where('id', $deleteId)
            ->delete();
        return redirect()->route('ProductTag');

    }

    /**************** 商品 ***************/

    public function product($category = null)
    {
        $data['categories'] = DB::select("SELECT * FROM product_category");

        if (empty($category)) {
            $data['selectCategory'] = 'all';
            $data['products'] = DB::table("product")
                ->leftJoin('product_category', 'product.productCateId', '=', 'product_category.id')
                ->select("product.*", "product_category.productCateName")
                ->orderBY('product.sort', 'desc')->orderBy('product.id', 'desc')
                ->paginate(5);
        } else {
            $data['selectCategory'] = $category;
            $data['products'] = DB::table("product")->where('productCateId', $category)
                ->leftJoin('product_category', 'product.productCateId', '=', 'product_category.id')
                ->select("product.*", "product_category.productCateName")
                ->orderBY('product.sort', 'desc')->orderBy('product.id', 'desc')
                ->paginate(5);

        }

        return view('Product.Product', $data);
    }

    public function search_product($keyword)
    {
        $data['categories'] = DB::select("SELECT * FROM product_category");

        $data['selectCategory'] = 'all';

        $data['products'] = DB::table("product")
            ->leftJoin('product_category', 'product.productCateId', '=', 'product_category.id')
            ->select("product.*", "product_category.productCateName")
            ->orderBY('product.sort', 'desc')->orderBy('product.id', 'desc')
            ->where('product.productName', 'LIKE', '%' . $keyword . '%')

            ->paginate(20);

        $data['keyword'] = $keyword;

        return view('Product.Product', $data);

    }

    public function product_delete(Request $req)
    {
        if (empty($req->deleteId)) {
            return redirect()->back();
        }

        $deleteId = $req->deleteId;
        DB::table('image_list')
            ->where('product_id', $deleteId)
            ->delete();

        Storage::deleteDirectory("/public/product/$deleteId");

        DB::table('product_detail')
            ->where('productId', $deleteId)
            ->delete();
        DB::table('product')
            ->where('productId', $deleteId)
            ->delete();

        return redirect()->back();

    }

    public function product_create_page()
    {

        $data['tags'] = DB::select("SELECT * FROM product_tag");

        $data['categories'] = DB::select("SELECT * FROM product_category");

        return view('Product.CreateProduct', $data);
    }

    public function product_create(Request $req)
    {
        // return $req;
        $data['productId'] = $req->productId;
        $data['productName'] = $req->productName;
        $data['productCateId'] = $req->productCateId;
        $data['productTag'] = $req->productTag;
        $data['productIntro'] = $req->productIntro;
        $data['sort'] = $req->sort;
        $data['enable'] = $req->enable;
        $data['description'] = $req->description;
        $data['composition'] = $req->composition;
        $data['buyflow'] = $req->buyflow;

        DB::table('product')
            ->insert($data);

        return redirect()->route('AllProduct');

    }

    //新增商品主檔內容圖片
    public function uploadimage(Request $req, $product_id, $type)
    {
        if ($req->hasFile('file')) {
            $image = $req->file('file');
            $file_path = $image->store("public/product/$product_id/$type");
            $filename = $image->hashName();
            DB::table('image_list')->insert([
                'filename' => $filename,
                'product_id' => $product_id,
                'product_type' => $type,
                'date' => now(),
            ]);

            return ['location' => request()->getSchemeAndHttpHost() . "/" . env('PROJECT_NAME') . "/public/storage/product/$product_id/$type/$filename"];

        }
    }

    //新增商品主檔廢棄圖片
    public function imagenone()
    {
        //上傳後沒發布的圖片
        $notitle_images = DB::select("SELECT image_list.* from image_list
                            LEFT JOIN product
                              ON image_list.product_id = product.productId
                            WHERE image_list.product_id
                            NOT IN(SELECT productId FROM product)");

        //更新傳圖未上傳 || 上傳後刪除 (文章內無顯示的圖)
        $hastitle_images = DB::select("SELECT image_list.*,product.productName,product.productId,product.buyflow,product.description,product.composition ,product.created_at  FROM image_list
                              LEFT JOIN product
                              ON image_list.product_id = product.productId
                              WHERE image_list.product_id IS NOT NULL
							  AND (product.description NOT LIKE CONCAT('%', image_list.filename, '%') OR product.description IS NULL)
                              AND (product.composition NOT LIKE CONCAT('%', image_list.filename, '%') OR product.composition IS NULL)
                              AND (product.buyflow NOT LIKE CONCAT('%', image_list.filename, '%') OR product.buyflow IS NULL)");
        return view('Product.Imagenone', ['notitle_images' => $notitle_images, 'hastitle_images' => $hastitle_images]);

    }

    public function delete_product_image_notuse(Request $req)
    {
        if (!isset($req->type)) {
            return redirect()->route('ProductImageNone');
        }

        if ($req->type == 1) {
            $notitle_images = DB::select("SELECT image_list.* from image_list
                            LEFT JOIN product
                              ON image_list.product_id = product.productId
                            WHERE image_list.product_id
                            NOT IN(SELECT productId FROM product)");

            foreach ($notitle_images as $image) {
                Storage::delete("/public/product/$image->product_id/$image->filename");
                $fileInFolder = Storage::allFiles("/public/product/$image->product_id/$image->filename");
                if (empty($fileInFolder)) {
                    Storage::deleteDirectory("/public/product/$image->product_id");
                }

                DB::table('image_list')
                    ->where('product_id', $image->product_id)
                    ->where('filename', $image->filename)
                    ->delete();
            }
            return redirect()->route('ProductImageNone');

        } else {
            $hastitle_images = DB::select("SELECT image_list.*,product.productName,product.productId,product.buyflow,product.description,product.composition ,product.created_at  FROM image_list
                              LEFT JOIN product
                              ON image_list.product_id = product.productId
                              WHERE image_list.product_id IS NOT NULL
							  AND (product.description NOT LIKE CONCAT('%', image_list.filename, '%') OR product.description IS NULL)
                              AND (product.composition NOT LIKE CONCAT('%', image_list.filename, '%') OR product.composition IS NULL)
                              AND (product.buyflow NOT LIKE CONCAT('%', image_list.filename, '%') OR product.buyflow IS NULL)");

            foreach ($hastitle_images as $image) {
                Storage::delete("/public/product/$image->product_id/$image->product_type/$image->filename");
                $fileInFolder = Storage::allFiles("/public/product/$image->product_id/$image->product_type");
                // return $fileInFolder;
                if (empty($fileInFolder)) {
                    Storage::deleteDirectory("/public/product/$image->product_id/$image->product_type");
                }

                $fileInRootFolder = Storage::allFiles("/public/product/$image->product_id");
                // return $fileInRootFolder;
                if (empty($fileInRootFolder)) {
                    Storage::deleteDirectory("/public/product/$image->product_id");
                }

                DB::table('image_list')
                    ->where('product_id', $image->product_id)
                    ->where('filename', $image->filename)
                    ->delete();
            }
            return redirect()->route('ProductImageNone');

        }
    }
}
