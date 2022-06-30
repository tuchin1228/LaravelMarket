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
                    WHERE productCateId =  '$deleteId'
                    AND delete_at IS NULL");
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
                ->whereNull('product.delete_at')
                ->orderBY('product.sort', 'desc')->orderBy('product.id', 'desc')
                ->paginate(5);
        } else {
            $data['selectCategory'] = $category;
            $data['products'] = DB::table("product")
                ->where('productCateId', $category)
                ->whereNull('product.delete_at')
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
            ->whereNull('product.delete_at')
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
            ->update([
                'delete_at' => date('Y-m-d h:i:s', time())
            ]);
        // ->delete();
        DB::table('product')
            ->where('productId', $deleteId)
            ->update([
                'delete_at' => date('Y-m-d h:i:s', time())
            ]);
        // ->delete();

        return redirect()->back();
    }

    public function product_create_page()
    {

        $data['tags'] = DB::select("SELECT * FROM product_tag");

        $data['categories'] = DB::select("SELECT * FROM product_category");

        return view('Product.CreateProduct', $data);
    }

    public function product_edit_page($productId)
    {

        $data['tags'] = DB::select("SELECT * FROM product_tag");

        $data['categories'] = DB::select("SELECT * FROM product_category");

        $product = DB::select("SELECT * FROM product
                                WHERE productId = '$productId'
                                AND delete_at IS NULL");
        $data['product'] = $product[0];
        return view('Product.EditProduct', $data);
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

    public function product_edit(Request $req)
    {
        $productId = $req->productId;
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
            ->where('productId', $productId)
            ->update($data);

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
                            NOT IN(SELECT productId FROM product)
                            AND image_list.product_type != 'product'");

        //更新傳圖未上傳 || 上傳後刪除 (文章內無顯示的圖)
        // $hastitle_images = DB::select("SELECT image_list.*,product.productName,product.productId,product.buyflow,product.description,product.composition ,product.created_at  FROM image_list
        //                       LEFT JOIN product
        //                       ON image_list.product_id = product.productId
        //                       WHERE image_list.product_id IS NOT NULL
        //                       AND image_list.product_type != 'product'
        // 					  AND (product.description NOT LIKE CONCAT('%', image_list.filename, '%') OR product.description IS NULL)
        //                       AND (product.composition NOT LIKE CONCAT('%', image_list.filename, '%') OR product.composition IS NULL)
        //                       AND (product.buyflow NOT LIKE CONCAT('%', image_list.filename, '%') OR product.buyflow IS NULL)");
        $hastitle_images = DB::select("SELECT image_list.*,product.productName,product.productId,product.buyflow,product.description,product.composition ,product.created_at  FROM image_list
        LEFT JOIN product
        ON image_list.product_id = product.productId
        WHERE image_list.product_id IS NOT NULL
        AND image_list.product_type != 'product'
        AND (product.description NOT LIKE CONCAT('%', image_list.filename, '%') AND product.created_at IS NOT  NULL)
        AND (product.composition NOT LIKE CONCAT('%', image_list.filename, '%') AND product.created_at IS NOT  NULL)
        AND (product.buyflow NOT LIKE CONCAT('%', image_list.filename, '%') AND product.created_at IS NOT NULL)");

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

    public function upload_product_image(Request $req)
    {
        if ($req->hasFile('formFile')) {
            $image = $req->file('formFile');
            $productId = $req->productId;
            $file_path = $image->store("public/product/$productId/product");

            $filename = $image->hashName();
            DB::table('image_list')->insert([
                'filename' => $filename,
                'product_id' => $productId,
                'product_type' => 'product',
                'date' => now(),
            ]);
        }

        return redirect()->route('ProductDetailPage', ['productId' => $productId]);
    }

    public function delete_product_image(Request $req)
    {
        if (empty($req->productId) || empty($req->filename)) {
            return redirect()->back();
        }
        $productId = $req->productId;
        $filename = $req->filename;

        Storage::delete("/public/product/$productId/product/$filename");
        $fileInFolder = Storage::allFiles("/public/product/$productId/product");
        if (empty($fileInFolder)) {
            Storage::deleteDirectory("/public/product/$productId/product");
        }
        $fileInRootFolder = Storage::allFiles("/public/product/$productId");
        if (empty($fileInRootFolder)) {
            Storage::deleteDirectory("/public/product/$productId");
        }

        DB::table('image_list')
            ->where('product_id', $productId)
            ->where('filename', $filename)
            ->delete();

        return redirect()->route('ProductDetailPage', ['productId' => $productId]);
    }

    /******************************
    productDetail
     ******************************/

    public function product_detail_page($productId)
    {
        if (empty($productId)) {
            return redirect()->back();
        }

        $product = DB::select("SELECT A.* , B.productCateName ,C.tagName FROM product AS A
                               LEFT JOIN product_category AS B
                               ON A.productCateId = B.id
                               LEFT JOIN product_tag AS C
                               ON A.productTag = C.id
                               WHERE productId = '$productId'
                               AND A.delete_at IS NULL");
        $data['product'] = $product[0];

        $data['productDetails'] = DB::select("SELECT * FROM product_detail
                                     WHERE productId = '$productId'
                                     AND delete_at IS NULL
                                     ORDER BY sort desc");

        $data['productImages'] = DB::select("SELECT * FROM image_list
                                     WHERE product_id = '$productId'
                                     AND product_type = 'product'");

        return view('Product.ProductDetail', $data);
    }

    public function product_detail_add(Request $req)
    {
        // return $req;
        $data['productDetailName'] = $req->productDetailName;
        $data['quantity'] = $req->quantity;
        $data['originPrice'] = $req->originPrice;
        $data['salePrice'] = $req->salePrice;
        $data['cost'] = $req->cost;
        $data['sort'] = $req->sort;
        $data['enable'] = $req->enable;
        $data['productId'] = $req->productId;
        $data['max_quantity'] = empty($req->max_quantity) ? null : $req->max_quantity;
        $data['productDetailId'] = time();

        DB::table('product_detail')
            ->insert($data);

        return redirect()->route('ProductDetailPage', ['productId' => $req->productId]);
    }

    public function product_detail_edit(Request $req)
    {
        // return $req;
        if (empty($req->productId) || empty($req->productDetailId)) {
            return redirect()->back();
        }
        $data['productDetailName'] = $req->productDetailName;
        $data['quantity'] = $req->quantity;
        $data['originPrice'] = $req->originPrice;
        $data['salePrice'] = $req->salePrice;
        $data['cost'] = $req->cost;
        $data['sort'] = $req->sort;
        $data['enable'] = $req->enable;
        $data['max_quantity'] = empty($req->max_quantity) ? null : $req->max_quantity;
        $productId = $req->productId;
        $productDetailId = $req->productDetailId;
        DB::table('product_detail')
            ->where('productDetailId', $productDetailId)
            ->update($data);
        return redirect()->route('ProductDetailPage', ['productId' => $productId]);
    }

    public function product_detail_delete(Request $req)
    {
        if (empty($req->productId) || empty($req->deleteId)) {
            return redirect()->back();
        }
        $productId = $req->productId;
        $deleteId = $req->deleteId;
        DB::table('product_detail')
            ->where('productDetailId', $deleteId)
            ->update([
                'delete_at' => date('Y-m-d h:i:s', time())
            ]);
        // ->delete();
        return redirect()->route('ProductDetailPage', ['productId' => $productId]);
    }

    public function product_additional_page()
    {

        $data['productAdditionals'] = DB::table("product_addtional")
            ->whereNull('delete_at')
            ->orderBY('sort', 'desc')
            ->paginate(5);

        return view('Product.ProductAdditional', $data);
    }

    public function product_additional_detail($productAdditionId)
    {
        $productAdditional = DB::select("SELECT *,DATE_FORMAT(startTime, '%Y-%m-%dT%H:%i') AS startTime ,DATE_FORMAT(endTime, '%Y-%m-%dT%H:%i') AS endTime FROM product_addtional
                                                  WHERE productAdditionId = '$productAdditionId'
                                                  AND delete_at IS NULL");

        $productAdditionalDetail = DB::select("SELECT A.addition_price,A.productAdditionId ,B.*  FROM product_addtional_detail AS A
                                               LEFT JOIN product AS B
                                               ON  A.productId = B.productId
                                               WHERE A.productAdditionId = '$productAdditionId'
                                               AND A.delete_at IS NULL
                                               AND B.delete_at IS NULL");

        if (empty($productAdditional)) {
            return redirect()->back();
        } else {
            $data['productAdditional'] = $productAdditional[0];
            $data['productAdditionalDetail'] = $productAdditionalDetail;
            return view('Product.ProductAdditionalDetail', $data);
        }
    }

    public function product_additional_add()
    {
        return view('Product.AddProductAdditional');
    }

    public function product_additional_create(Request $req)
    {
        // return $req;

        if ($req->hasFile('formFile')) {
            // return 'ok';
            $data['productAdditionName'] = $req->productAdditionName;
            $data['quantity'] = $req->quantity;
            $data['startTime'] = $req->startTime;
            $data['endTime'] = $req->endTime;
            $data['addition_cost'] = $req->addition_cost;
            $data['max_quantity'] = $req->max_quantity;
            $data['sort'] = $req->sort;
            $data['enable'] = $req->enable;
            $data['forAll'] = $req->forAll;
            $data['forAllPrice'] = $req->forAllPrice ? $req->forAllPrice : 0;

            $productAdditionId = time();
            $data['productAdditionId'] = $productAdditionId;

            $image = $req->file('formFile');
            $filename = $image->hashName();

            $data['imageFilename'] = $filename;

            $file_path = $image->store("public/additional_product/$productAdditionId");

            DB::table('product_addtional')
                ->insert($data);

            return redirect()->route('ProductAdditionalDetail', ['productAdditionId' => $productAdditionId]);
        } else {
            return redirect()->route('ProductAdditionalAdd')->withInput()->withErrors(['CreateError' => '必須上傳圖片']);
        }
    }

    public function product_additional_edit(Request $req)
    {
        // return $req;
        if ($req->hasFile('formFile')) {

            // $productAddition = DB::select("SELECT * FROM product_addtional
            //             WHERE productAdditionId = '$req->productAdditionId'");
            // $oldimageFilename = $productAddition[0]->imageFilename;

            // return 'ok';
            $data['productAdditionName'] = $req->productAdditionName;
            $data['quantity'] = $req->quantity;
            $data['startTime'] = $req->startTime;
            $data['endTime'] = $req->endTime;
            $data['addition_cost'] = $req->addition_cost;
            $data['max_quantity'] = $req->max_quantity;
            $data['sort'] = $req->sort;
            $data['enable'] = $req->enable;
            $data['forAll'] = $req->forAll;
            $data['forAllPrice'] = $req->forAllPrice ? $req->forAllPrice : 0;

            $productAdditionId = $req->productAdditionId;

            Storage::deleteDirectory("/public/additional_product/$productAdditionId");

            $image = $req->file('formFile');
            $filename = $image->hashName();

            $data['imageFilename'] = $filename;

            $file_path = $image->store("public/additional_product/$productAdditionId");

            DB::table('product_addtional')
                ->where('productAdditionId', $productAdditionId)
                ->update($data);

            return redirect()->route('ProductAdditionalDetail', ['productAdditionId' => $productAdditionId]);
        } else {
            $data['productAdditionName'] = $req->productAdditionName;
            $data['quantity'] = $req->quantity;
            $data['startTime'] = $req->startTime;
            $data['endTime'] = $req->endTime;
            $data['addition_cost'] = $req->addition_cost;
            $data['max_quantity'] = $req->max_quantity;
            $data['sort'] = $req->sort;
            $data['enable'] = $req->enable;
            $data['forAll'] = $req->forAll;
            $data['forAllPrice'] = $req->forAllPrice ? $req->forAllPrice : 0;


            $productAdditionId = $req->productAdditionId;

            DB::table('product_addtional')
                ->where('productAdditionId', $productAdditionId)
                ->update($data);

            return redirect()->route('ProductAdditionalDetail', ['productAdditionId' => $productAdditionId]);
        }
    }

    public function product_additional_assign(Request $req)
    {
        // return $req;
        $data = [];
        $products = $req->products;
        $productAdditionId = $req->productAdditionId;
        foreach ($products as $key => $product) {
            $data[] = [
                'productId' => $product['productId'],
                'addition_price' => $product['addition_price'],
                'productAdditionId' => $productAdditionId,
            ];
        }
        // return $data;
        DB::table('product_addtional_detail')
            ->insert($data);

        return ['success' => true];
    }

    /**********************************
    api
     *********************************/

    public function KeywordSearchProduct(Request $req)
    {
        // return $req;
        if (!empty($req->keyword)) {
            $keyword = $req->keyword;
            $SearchProducts = DB::select("SELECT * FROM product AS A
                        LEFT JOIN articles_category AS B
                        ON A.productCateId = B.id
                        WHERE A.productName LIKE '%$keyword%'
                        AND A.delete_at IS NULL");
            return ['success' => true, 'SearchProducts' => $SearchProducts];
        } else {
            return ['success' => false];
        }
    }

    //需排除原先已在product_addtional_detail的資料避免重複新增
    public function KeywordSearchAdditionalProduct(Request $req)
    {
        $keyword = $req->keyword;
        $productAdditionId = $req->productAdditionId;

        if (!empty($keyword) && !empty($productAdditionId)) {
            $keyword = $req->keyword;
            $SearchProducts = DB::select("SELECT A.* , B.* ,C.addition_price,C.productAdditionId FROM product AS A
                                          LEFT JOIN product_category AS B
                                          ON A.productCateId = B.id
                                          LEFT JOIN product_addtional_detail AS C
                                          ON A.productId = C.productId
                                          WHERE A.productName LIKE '%$keyword%'
                                          AND (C.productAdditionId != '$productAdditionId'
										  OR C.productAdditionId IS NULL)
                                          AND A.enable = 1
                                          AND A.delete_at IS NULL");
            return ['success' => true, 'SearchProducts' => $SearchProducts];
        } else {
            return ['success' => false];
        }
    }

    public function product_additional_editAdditionalPrice(Request $req)
    {
        // return $req;
        $addition_price = $req->addition_price;
        $productId = $req->productId;
        $productAdditionId = $req->productAdditionId;
        DB::table('product_addtional_detail')
            ->where('productId', $productId)
            ->where('productAdditionId', $productAdditionId)
            ->update([
                'addition_price' => $addition_price,
            ]);
        return redirect()->back();
    }

    public function product_additional_deleteDetail(Request $req)
    {
        // return $req;
        $productId = $req->deleteId;
        $productAdditionId = $req->productAdditionId;
        DB::table('product_addtional_detail')
            ->where('productId', $productId)
            ->where('productAdditionId', $productAdditionId)
            ->update([
                'delete_at' => date('Y-m-d h:i:s', time())
            ]);
        // ->delete();
        return redirect()->back();
    }

    public function product_additional_consistentAdditionalPrice(Request $req)
    {
        // return $req;

        $productAdditionId = $req->productAdditionId;
        $addition_price = $req->addition_price;
        DB::table('product_addtional_detail')
            ->where('productAdditionId', $productAdditionId)
            ->update([
                'addition_price' => $addition_price,
            ]);

        return redirect()->back();
    }

    public function product_additional_deleteAllDetail(Request $req)
    {
        // return $req;
        $productAdditionId = $req->productAdditionId;

        DB::table('product_addtional_detail')
            ->where('productAdditionId', $productAdditionId)
            ->update([
                'delete_at' => date('Y-m-d h:i:s', time())
            ]);
        // ->delete();

        return redirect()->back();
    }

    public function product_additional_delete(Request $req)
    {
        // return $req;

        $productAdditionId = $req->productAdditionId;

        Storage::deleteDirectory("/public/additional_product/$productAdditionId");

        DB::table('product_addtional_detail')
            ->where('productAdditionId', $productAdditionId)
            ->update([
                'delete_at' => date('Y-m-d h:i:s', time())
            ]);
        // ->delete();
        DB::table('product_addtional')
            ->where('productAdditionId', $productAdditionId)
            ->update([
                'delete_at' => date('Y-m-d h:i:s', time())
            ]);
        // ->delete();

        return redirect()->back();
    }
}
