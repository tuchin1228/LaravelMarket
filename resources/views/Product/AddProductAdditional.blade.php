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
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">新增加購品</li>
            </ol>
        </nav>
    </div>

</div>

<div class=" my-3 bg-white p-2">
    <form action="{{route('ProductAdditionalCreate')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary px-5">確認新增</button>
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">加購品名稱</label>
                <input type="text" name="productAdditionName" class="form-control my-2"
                    value="{{old('productAdditionName')}}" required id="productAdditionName" placeholder="加購品名稱">
                {{-- <select class="form-select  my-2 " name="productCateId" id="">
                @foreach ($categories as $category)
                <option value="{{$category->id}}">{{$category->productCateName}}</option>
                @endforeach
                </select> --}}
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">數量</label>
                <input type="text" name="quantity" class="form-control my-2" value="{{old('quantity')}}" required
                    id="quantity" placeholder="數量">

                {{-- <select class="form-select  my-2 " name="productTag" id="">
                <option value="" selected>無</option>
                @foreach ($tags as $tag)
                <option value="{{$tag->id}}">{{$tag->tagName}}</option>
                @endforeach
                </select> --}}
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">開始時間</label>
                <input type="datetime-local" name="startTime" class="form-control my-2" value="{{old('startTime')}}"
                    id="startTime" placeholder="開始時間">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">結束時間</label>
                <input type="datetime-local" name="endTime" class="form-control my-2" value="{{old('endTime')}}"
                    id="endTime" placeholder="結束時間">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">加購品成本</label>
                <input type="number" name="addition_cost" class="form-control my-2" value="{{old('addition_cost')}}"
                    id="addition_cost" placeholder="加購品成本">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">每次可加購量</label>
                <input type="number" name="max_quantity" class="form-control my-2" value="{{old('max_quantity')}}"
                    required id="max_quantity" placeholder="每次可加購量">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">排序號</label>
                <input type="number" name="sort" class="form-control my-2" value="{{old('sort')}}" id="sort"
                    placeholder="排序">
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">是否啟用</label>
                <select class="form-select  my-2 " name="enable" id="">
                    <option value="1" @if( empty(old('enable')) || (!empty(old('enable')) && old('enable')==1) )
                        selected @endif>啟用</option>
                    <option value="0" @if(!empty(old('enable') && old('enable')==0)) selected @endif>未啟用</option>
                </select>
            </div>

            <div class="col-6 ">
                <label for="formFile" class="form-label mt-4">上傳圖片</label>
                {{-- this.form.submit() --}}
                <input class="form-control" name="formFile" onchange="" type="file" value="" id="formFile">
                <div class="text-center">
                    <img src="" style="max-width: 500px" id="preview_image" alt="">
                </div>
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">是否套用所有商品</label>
                <select class="form-select  my-2 " name="forAll" id="">
                    <option value="1" @if( empty(old('forAll')) || (!empty(old('forAll')) && old('forAll')==1) )
                        selected @endif>是</option>
                    <option value="0" @if(!empty(old('forAll') && old('forAll')==0)) selected @endif>否</option>
                </select>
            </div>
            <div class="col-6">
                <label for="" class="d-block mt-4">全商品加購價(套用所有商品時才會生效)</label>
                <input type="number" name="forAllPrice" class="form-control my-2"
                value="{{old('forAllPrice') }}" id="forAllPrice" placeholder="全商品加購價">
            </div>
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
    {{-- <div class="modal fade" id="searchModal" tabindex="-1" aria-hidden="true">
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
                    <div class="searchResult ">
                    </div>
                </div>

            </div>
        </div>
    </div> --}}
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
        console.log('{{route("KeywordSearchProduct")}}');
        if (!keyword) {
            return null;
        }
        $.ajax({
            url: '{{route("KeywordSearchProduct")}}',
            type: 'POST',
            data: {
                keyword: keyword
            },
            success: function (res) {
                console.log(res);
                if (res.success) {
                    let html = ``;
                    res.SearchProducts.forEach(item => {
                        html += `
                         <div class="row align-items-center border-bottom">
                             <h5 class="col-4">${item.productName}</h5>
                             <h5 class="col-4 text-center">${item.productCateId ? item.articles_cate_title : ''}</h5>
                             <h5 class="py-1 text-center col-4"><button type="button" class="btn btn-primary"
                                     onclick="SelectProduct('${item.productName}','${item.productCateId ?
                                     item.articles_cate_title : ''}','${item.productId}')">選擇</button></h5>
                         </div>
                        `
                    });

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

</script>
@endsection
