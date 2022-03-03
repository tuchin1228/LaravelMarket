@extends('dashboard_layout')

@section('head')
<title>加購品管理</title>
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
    <div class="breadcrumb-title pe-3">加購品管理</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{route('ProductAdditionalPage')}}"><i
                            class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">加購品詳情</li>
            </ol>
        </nav>
    </div>

</div>

<div class=" my-3 bg-white p-2">
    <form action="{{route('ProductAdditionalEdit')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-warning px-5">更新加購品</button>
            </div>
            <div class="col-12">
                <h4 class="fw-bold">加購品主檔</h4>
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">加購品名稱</label>
                <input type="text" name="productAdditionName" class="form-control my-2"
                    value="{{old('productAdditionName') ? old('productAdditionName') :$productAdditional->productAdditionName}}"
                    required id="productAdditionName" placeholder="加購品名稱">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">數量</label>
                <input type="text" name="quantity" class="form-control my-2"
                    value="{{old('quantity') ? old('quantity') : $productAdditional->quantity}}" required id="quantity"
                    placeholder="數量">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">開始時間</label>
                <input type="datetime-local" name="startTime" class="form-control my-2"
                    value="{{old('startTime') ? old('startTime') :  $productAdditional->startTime }}" id="startTime"
                    placeholder="開始時間">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">結束時間</label>
                <input type="datetime-local" name="endTime" class="form-control my-2"
                    value="{{old('endTime') ?  old('endTime')  : $productAdditional->endTime }}" id="endTime"
                    placeholder="結束時間">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">加購品成本</label>
                <input type="number" name="addition_cost" class="form-control my-2"
                    value="{{old('addition_cost') ? old('addition_cost') :  $productAdditional->addition_cost}}"
                    id="addition_cost" placeholder="加購品成本">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">每次可加購量</label>
                <input type="number" name="max_quantity" class="form-control my-2"
                    value="{{old('max_quantity') ? old('max_quantity') :  $productAdditional->max_quantity}}" required
                    id="max_quantity" placeholder="每次可加購量">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">排序號</label>
                <input type="number" name="sort" class="form-control my-2"
                    value="{{old('sort') ? old('sort') :  $productAdditional->sort}}" id="sort" placeholder="排序">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">是否啟用</label>
                <select class="form-select  my-2 " name="enable" id="">
                    <option value="1" @if( empty(old('enable')) || (!empty(old('enable')) && old('enable')==1) ||
                        $productAdditional->enable == 1 )
                        selected @endif>啟用</option>
                    <option value="0" @if((!empty(old('enable') && old('enable')==0)) || $productAdditional->enable ==
                        0) selected @endif>未啟用</option>
                </select>
            </div>

            <div class="col-6 ">
                <label for="formFile" class="form-label mt-4">上傳圖片</label>
                {{-- this.form.submit() --}}
                <input class="form-control" name="formFile" onchange="" type="file" value="" id="formFile">
                <div class="text-center my-1">
                    @if (!empty($productAdditional->imageFilename))
                    <img src="../../../storage/additional_product/{{$productAdditional->productAdditionId}}/{{$productAdditional->imageFilename}}"
                        style="max-width: 300px" id="preview_image" alt="">
                    @else
                    <img src="" style="max-width: 300px" id="preview_image" alt="">
                    @endif
                </div>
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">是否套用所有商品</label>
                <select class="form-select  my-2 " name="forAll" id="">
                    <option value="1" @if( empty(old('forAll')) || (!empty(old('forAll')) && old('forAll')==1) ||
                        $productAdditional->forAll == 1 )
                        selected @endif>是</option>
                    <option value="0" @if(!empty(old('forAll') && old('forAll')==0) || $productAdditional->forAll ==
                        0) selected @endif>否</option>
                </select>
            </div>
            <input type="text" hidden name="productAdditionId" value="{{$productAdditional->productAdditionId}}">
            {{-- <div class="col-12 mx-auto">
                <hr />
            </div> --}}
            {{-- <div class="col-12 ">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#searchModal">指定主商品</button>
                <div class="selectProduct"></div>
            </div> --}}
        </div>
    </form>
    <hr />
    <div class="col-12">
        <h4 class="fw-bold">指定商品</h4>
    </div>
    <div class="col-12 text-end py-2">
        <button type="button" class="btn btn-warning px-5" data-bs-toggle="modal"
            data-bs-target="#consistentModal">統一加購價</button>
        <button type="button" class="btn btn-primary px-5" data-bs-toggle="modal"
            data-bs-target="#assignProductModal">指定主商品</button>
        <button type="button" class="btn btn-danger px-5" data-bs-toggle="modal"
            data-bs-target="#deleteAllProductModal">刪除所有</button>



    </div>
    <div class="row g-2">
        @foreach ($productAdditionalDetail as $product)
        <div class="col-3 ">
            <div class="border p-2 rounded">
                <h5 class="fw-bold"><a
                        href="{{route('ProductDetailPage',['productId'=>$product->productId])}}">{{$product->productName}}</a>
                </h5>
                <h6>加購價：{{$product->addition_price}}</h6>
                <div class="row g-2">
                    <div class="col-6 "><button type="button" class="w-100 mx-auto my-2 btn btn-sm btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#editAddtionalPrice{{$product->productId}}">更新加購價</button>
                        <div class="modal fade" id="editAddtionalPrice{{$product->productId}}" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog  modal-dialog-centered">
                                <form action="{{route('ProductAdditionalPrice')}}" method="POST">
                                    @csrf
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">更新加購價</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body ">
                                            <div class="row align-items-center">
                                                <h5 class="fw-bold">{{$product->productName}}</h5>
                                                <h6>加購價：{{$product->addition_price}}</h6>
                                                <div class="col-12">
                                                    <input type="number" name="addition_price"
                                                        class=" form-control my-2" value="" id="addition_price"
                                                        placeholder="更新加購價">
                                                </div>
                                            </div>
                                            <input type="text" value="{{$product->productId}}" hidden name="productId">
                                            <input type="text" hidden name="productAdditionId"
                                                value="{{$productAdditional->productAdditionId}}">
                                        </div>
                                        <div class="modal-footer ">
                                            <button type="submit" class="btn btn-danger my-1">更新加購價</button>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">取消</button>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 "><button type="button" class="w-100 mx-auto my-2 btn btn-sm btn-danger"
                            data-bs-toggle="modal" data-bs-target="#deleteModal{{$product->productId}}">刪除</button>
                        <div class="modal fade" id="deleteModal{{$product->productId}}" tabindex="-1"
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
                                        <h5>確認刪除<span class="fw-bold">{{$product->productName}}</span></h5>
                                    </div>
                                    <form action="{{route('ProductAdditionaldeleteDetail')}}" method="POST">
                                        @csrf
                                        <input type="text" hidden name="deleteId" value="{{$product->productId}}">
                                        <input type="text" hidden name="productAdditionId"
                                            value="{{$productAdditional->productAdditionId}}">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">取消</button>
                                            <button type="submit" class="btn btn-danger">刪除</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="modal fade" id="assignProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">主商品搜尋</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <input type="text" name="keyword" class=" form-control my-2" value="" id="keyword"
                                placeholder="主商品關鍵字">
                        </div>
                        <button type="button" class="col-4 btn btn-primary" onclick="searchProduct()">搜尋</button>
                        <hr />
                    </div>
                    <div class="searchTitle">

                        <div class="row align-items-center border-bottom">
                            <h5 class="col-1">批量</h5>
                            <h5 class="col-3">產品名稱</h5>
                            <h5 class="col-3 text-center">產品分類</h5>
                            <h5 class="col-3 text-center">加購價</h5>
                            <h5 class="py-1 text-center col-2">
                            </h5>
                        </div>
                    </div>
                    <div class="searchResult ">
                    </div>
                    <div class="text-end">
                        <button type="button" onclick="SubmitMultipleProduct()"
                            class="btn btn-danger my-1">批量新增</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="consistentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">加購價一致化</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('ConsistentProductAdditionalPrice')}}" method="POST">
                    @csrf
                    <div class="modal-body ">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <input type="number" name="addition_price" class=" form-control my-2" value=""
                                    id="addition_price" placeholder="更新加購價">
                            </div>
                        </div>
                        <input type="text" hidden name="productAdditionId"
                            value="{{$productAdditional->productAdditionId}}">
                    </div>
                    <div class="modal-footer ">
                        <button type="submit" class="btn btn-danger my-1">確認更新</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteAllProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">確認刪除</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <h5 class="fw-bold">刪除後將無法復原</h5>
                </div>
                <form action="{{route('ProductAdditionaldeleteAllDetail')}}" method="POST">
                    @csrf
                    <input type="text" hidden name="productAdditionId"
                        value="{{$productAdditional->productAdditionId}}">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" class="btn btn-danger">刪除</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@if ($errors->has('CreateError')){

<script>
    alert("{{$errors->first('CreateError')}}")

</script>
}
@endif
<script>
    $("input#formFile").change(function () {

        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#preview_image").attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    const searchProduct = () => {
        let keyword = $('#keyword').val();
        console.log('{{route("KeywordSearchAdditionalProduct")}}');
        if (!keyword) {
            return null;
        }
        $.ajax({
            url: '{{route("KeywordSearchAdditionalProduct")}}',
            type: 'POST',
            data: {
                keyword: keyword,
                productAdditionId: '{{$productAdditional->productAdditionId}}'
            },
            success: function (res) {
                console.log(res);
                if (res.success) {
                    let html = ``;
                    res.SearchProducts.forEach(item => {
                        html += `
                         <div class="row align-items-center border-bottom">
                             <div class="col-1">
                                <input type="checkbox" name="assignProductCheckbox" value="${item.productId}"
                                    id="product${item.productId}" />
                            </div>
                             <h5 class="col-3"><label for="product${item.productId}">${item.productName}</label></h5>
                             <h5 class="col-3 text-center">${item.productCateId ? item.articles_cate_title : ''}</h5>
                             <h5 class="col-3">
                                <input type="number" class="form-control my-2 text-center" required
                                    name="addition_price${item.productId}" value="" /></h5>
                             <h5 class="py-1 text-center col-2">
                                    @csrf
                                    <input type="text" name="productId" hidden value="${item.productId}" />
                                    <button type="button" class="btn btn-primary"
                                        onclick="SubmitAssignProduct('${item.productId}')">選擇</button>
                            </h5>
                         </div>
                        `
                    });
                    //   <button type="button" class="btn btn-primary" onclick="SelectProduct('${item.productName}','${item.productCateId ?
                    //                  item.articles_cate_title : ''}','${item.productId}')">選擇</button>

                    $('.searchResult').html(html)
                }
            }
        })
    }

    // let selectProductArr = [];
    // const SelectProduct = (productName, Cate, productId) => {
    //     console.log(selectProductArr, productName, productId);
    //     selectProductArr.push({
    //         productName,
    //         Cate,
    //         productId
    //     })
    //     let html = ``;
    //     selectProductArr.forEach(item => {
    //         html += `
    //       <div class="row align-items-center border-bottom">
    //           <h5 class="col-4">${item.productName}</h5>
    //           <h5 class="col-4 text-center">${item.Cate ? item.Cate : ''}</h5>
    //           <h5 class="py-1 text-center col-4"><button type="button" class="btn btn-danger"
    //                   onclick="">刪除</button></h5>
    //       </div>
    //       `
    //     })
    //     $('.selectProduct').html(html)
    // }

    const SubmitMultipleProduct = async () => {

        let checkboxes = document.querySelectorAll('input[name="assignProductCheckbox"]:checked');
        let productArray = [];
        let checkPrice = 0;
        checkboxes.forEach((checkbox) => {
            // values.push(checkbox.value);
            console.log(checkbox.value);
            let price = $(`input[name="addition_price${checkbox.value}"`).val();
            productArray.push({
                productId: checkbox.value,
                addition_price: parseInt(price, 10)
            })
            if (!price || price.length == 0) {
                checkPrice++
            }

        });
        if (checkPrice > 0) {
            alert('加購價未確實填寫')
            return null;
        }

        // console.log(productArray);
        let res = await axios.post('{{route("ProductAdditionalAssign")}}', {
            products: productArray,
            productAdditionId: '{{$productAdditional->productAdditionId}}',
            _token: '{{ csrf_token() }}'
        })
        console.log('res', res);
        if (res.data.success) {
            $('.searchResult').html('')
            location.reload()
        }
        //打api ProductAdditionalAssign
    }

    const SubmitAssignProduct = async (productId) => {
        let productArray = [];
        let price = $(`input[name="addition_price${productId}"`).val();
        if (!price || price.length == 0) {
            alert('加購價未確實填寫')
            return null;
        }
        productArray.push({
            productId: productId,
            addition_price: parseInt(price, 10)
        })
        let res = await axios.post('{{route("ProductAdditionalAssign")}}', {
            products: productArray,
            productAdditionId: '{{$productAdditional->productAdditionId}}',
            _token: '{{ csrf_token() }}'
        })
        console.log('res', res);
        if (res.data.success) {
            $('.searchResult').html('')
            location.reload()
        }

        //打api ProductAdditionalAssign

    }

</script>
@endsection
