@extends('dashboard_layout')

@section('head')
<title>商品類別管理</title>
<style>
    table {
        font-size: 16px;
        width: 100%;
    }

    table tr td {
        vertical-align: middle;
    }

</style>
@endsection

@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">商品管理</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{route('AllProduct')}}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">商品詳情</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <h4 class="fw-bold">主檔資訊</h4>
    <div class="row p-2 m-2 border rounded">
        <h5 class="col-3"><strong>商品名稱</strong>：{{$product->productName}}</h5>
        <h5 class="col-3"><strong>是否啟用</strong>： @if ($product->enable == 1) <span
                class="text-success fw-bold">啟用中</span>
            @else
            <span class="text-danger fw-bold">未啟用</span>
            @endif
        </h5>
        <h5 class="col-3"><strong>商品分類</strong>：{{$product->productCateName}}</h5>
        <h5 class="col-3"><strong>商品標籤</strong>：@if(empty($product->tagName))未設定標籤 @else {{$product->tagName}} @endif
        </h5>

        <div class="col-3">
            <h5><strong>商品簡介</strong>：<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#productIntro{{$product->productId}}">看內容</button>
            </h5>
            <div class="modal fade" id="productIntro{{$product->productId}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">商品簡介</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            {!! $product->productIntro !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <h5><strong>商品描述</strong>：<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#description{{$product->productId}}">看內容</button>
            </h5>
            <div class="modal fade" id="description{{$product->productId}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">商品描述</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            {!! $product->description !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <h5><strong>商品成分</strong>：<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#composition{{$product->productId}}">看內容</button>
            </h5>
            <div class="modal fade" id="composition{{$product->productId}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">商品成分</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            {!! $product->composition !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-3">
            <h5><strong>訂購流程</strong>：<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                    data-bs-target="#buyflow{{$product->productId}}">看內容</button>
            </h5>
            <div class="modal fade" id="buyflow{{$product->productId}}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">訂購流程</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                            {!! $product->buyflow !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <h5 class="col-3"><strong>排序號　</strong>：{{$product->sort}}</h5>
        <h5 class="col-3"><strong>建檔時間</strong>：{{$product->created_at}}</h5>
    </div>
    <hr />
    <div>
        <div class="text-end">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                data-bs-target="#addProductDetail">新增子商品</button>
        </div>
        <h4 class="fw-bold">子商品資訊</h4>
        @if (!empty($productDetails))


        @foreach ($productDetails as $productDetail)

        <div class="row p-2 m-2 border rounded">
            <h5 class="col-3"><strong>商品名稱</strong>：{{$productDetail->productDetailName}}</h5>
            <h5 class="col-3"><strong>　　原價</strong>：${{$productDetail->originPrice}}</h5>
            <h5 class="col-3"><strong>　　特價</strong>：${{$productDetail->salePrice}}</h5>
            <h5 class="col-3"><strong>　　成本</strong>：${{$productDetail->cost}}</h5>
            <h5 class="col-3"><strong>　　數量</strong>：{{$productDetail->quantity}}</h5>
            <h5 class="col-3"><strong>　排序號</strong>：{{$productDetail->sort}}</h5>
            <h5 class="col-3"><strong>　購買量</strong>：{{$productDetail->max_quantity}}</h5>
            <h5 class="col-3"><strong>是否啟用</strong>：
                @if ($productDetail->enable ==1) <span class="text-success fw-bold">啟用中</span>
                @else <span class="text-danger fw-bold">未啟用</span>
                @endif </h5>
            <div class="col-12 text-end">
                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                    data-bs-target="#editProductDetail{{$productDetail->productDetailId}}">編輯</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteModal{{$productDetail->productDetailId}}">刪除</button>
                {{-- 編輯子商品 --}}
                <div class="modal fade" id="editProductDetail{{$productDetail->productDetailId}}" tabindex="-1"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">編輯子商品</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-start">
                                <form action="{{route('EditProductDetail')}}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-6 my-2">
                                            <label for="">子商品名稱</label> <input type="text" name="productDetailName"
                                                required class="form-control my-2"
                                                value="{{old('productDetailName') ? old('productDetailName') :$productDetail->productDetailName}}">
                                        </div>
                                        <div class="col-6 my-2">
                                            <label for="">數量</label> <input type="number" name="quantity" required
                                                class="form-control my-2"
                                                value="{{old('quantity') ? old('quantity') : $productDetail->quantity}}">
                                        </div>
                                        <div class="col-6 my-2">
                                            <label for="">原價</label> <input type="number" name="originPrice" required
                                                class="form-control my-2"
                                                value="{{old('originPrice') ? old('originPrice') : $productDetail->originPrice }}">
                                        </div>
                                        <div class="col-6 my-2">
                                            <label for="">特價</label> <input type="number" name="salePrice" required
                                                class="form-control my-2"
                                                value="{{old('salePrice') ? old('salePrice') : $productDetail->salePrice }}">
                                        </div>
                                        <div class="col-6 my-2">
                                            <label for="">成本</label> <input type="text" name="cost"
                                                class="form-control my-2"
                                                value="{{old('cost') ? old('cost') : $productDetail->cost}}">
                                        </div>
                                        <div class="col-6 my-2">
                                            <label for="">排序號</label> <input type="number" name="sort"
                                                class="form-control my-2"
                                                value="{{old('sort') ? old('sort') : $productDetail->sort}}">
                                        </div>
                                        <div class="col-6 my-2">
                                            <label for="">每人最大購買量</label> <input type="number" name="max_quantity" 
                                                class="form-control my-2" value="{{old('max_quantity') ? old('max_quantity')  : $productDetail->max_quantity}}">
                                        </div>
                                        <div class="col-6 my-2">
                                            <label for="" class="d-block">是否啟用</label>
                                            <select class="form-select  my-2 " name="enable" id="">
                                                <option value="1" @if( empty(old('enable')) || (!empty(old('enable')) &&
                                                    old('enable')==1) || $productDetail->enable == 1 ) selected
                                                    @endif>啟用
                                                </option>
                                                <option value="0" @if(!empty(old('enable') && old('enable')==0) ||
                                                    $productDetail->enable == 0)
                                                    selected @endif>
                                                    未啟用
                                                </option>
                                            </select>
                                        </div>
                                        <input type="text" name="productId" value="{{$product->productId}}" hidden>
                                        <input type="text" name="productDetailId"
                                            value="{{$productDetail->productDetailId}}" hidden>
                                        <div class="col-12 my-2">
                                            <button type="submit" class="d-block w-100 btn btn-primary">確認新增</button>
                                        </div>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal fade" id="deleteModal{{$productDetail->productDetailId}}" tabindex="-1"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">確認刪除</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">
                                <h6>刪除後將無法復原</h6>
                                <h5>確認刪除<span class="fw-bold">{{$productDetail->productDetailName}}</span></h5>
                            </div>
                            <form action="{{route('DeleteProductDetail')}}" method="POST">
                                @csrf
                                <input type="text" name="productId" value="{{$product->productId}}" hidden>
                                <input type="text" hidden name="deleteId" value="{{$productDetail->productDetailId}}">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-danger">刪除</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        @endforeach
        @endif
    </div>
    {{-- 新增子商品 --}}
    <div class="modal fade" id="addProductDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">新增子商品</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <form action="{{route('AddProductDetail')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-6 my-2">
                                <label for="">子商品名稱</label> <input type="text" name="productDetailName" required
                                    class="form-control my-2" value="{{old('productDetailName')}}">
                            </div>
                            <div class="col-6 my-2">
                                <label for="">數量</label> <input type="number" name="quantity" required
                                    class="form-control my-2" value="{{old('quantity')}}">
                            </div>
                            <div class="col-6 my-2">
                                <label for="">原價</label> <input type="number" name="originPrice" required
                                    class="form-control my-2" value="{{old('originPrice')}}">
                            </div>
                            <div class="col-6 my-2">
                                <label for="">特價</label> <input type="number" name="salePrice" class="form-control my-2"
                                    value="{{old('salePrice')}}">
                            </div>
                            <div class="col-6 my-2">
                                <label for="">成本</label> <input type="text" name="cost" class="form-control my-2"
                                    value="{{old('cost')}}">
                            </div>
                            <div class="col-6 my-2">
                                <label for="">排序號</label> <input type="number" name="sort" class="form-control my-2"
                                    value="{{old('sort')}}">
                            </div>
                            <div class="col-6 my-2">
                                <label for="">每人最大購買量</label> <input type="number" name="max_quantity" 
                                    class="form-control my-2" value="{{old('max_quantity')}}">
                            </div>
                            <div class="col-6 my-2">
                                <label for="" class="d-block">是否啟用</label>
                                <select class="form-select  my-2 " name="enable" id="">
                                    <option value="1" @if( empty(old('enable')) || (!empty(old('enable')) &&
                                        old('enable')==1) ) selected @endif>啟用</option>
                                    <option value="0" @if(!empty(old('enable') && old('enable')==0) ) selected @endif>
                                        未啟用
                                    </option>
                                </select>
                            </div>
                            <input type="text" name="productId" value="{{$product->productId}}" hidden>
                            <div class="col-12 my-2">
                                <button type="submit" class="d-block w-100 btn btn-primary">確認新增</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
    <hr />
    <div>
        <h4 class="fw-bold">商品圖管理</h4>
        <form class="my-3" action="{{route('UploadProductImage')}}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="formFile" class="form-label">上傳商品圖</label>
            <input class="form-control" name="formFile" onchange="this.form.submit()" type="file" value=""
                id="formFile">
            <input type="text" name="productId" value="{{$product->productId}}" hidden>
        </form>
        <div class="d-flex flex-wrap align-items-start">
            @foreach ($productImages as $productImage)
            <div class="p-2 border rounded m-2" style="width: 300px;">
                <img class="w-100"
                    src="../../storage/product/{{$product->productId}}/product/{{$productImage->filename}}" alt="">
                <form action="{{route('DeleteProductImage')}}" method="post">
                    @csrf
                    <input type="text" hidden name="filename" value="{{$productImage->filename}}">
                    <input type="text" hidden name="productId" value="{{$product->productId}}">
                    <button type="submit" class="my-1 btn btn-outline-danger d-block w-100">刪除</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
