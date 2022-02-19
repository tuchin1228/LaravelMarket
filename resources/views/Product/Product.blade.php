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
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">所有商品管理</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="d-flex align-items-center">
        <div class="p-2">
            <label for="" class="mb-1">分類搜尋</label>
            <select name="cateSelect" id="cateSelect" class="form-select w-auto " id="">
                <option value="{{route('AllProduct')}}">全部</option>
                @foreach ($categories as $category)
                <option value="{{ route('CategoryProduct',['category'=>$category->id]) }}">
                    {{$category->productCateName}}
                    {{-- {{$selectCategory}}{{$category->id}} --}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="flex-grow-1">
            <label for="" class="mb-1">關鍵字搜尋</label>
            <div class="d-flex align-items-center  flex-grow-1 ">
                <input type="text" name="keyword" class="form-control" id="keyword" style="max-width:250px;" value=""
                    placeholder="姓名、電話、信箱關鍵字搜尋">
                <button type="button" onclick="GoSearch()" class="btn btn-primary mx-1">搜尋</button>
                @if (isset($keyword))
                <a href="{{route('AllProduct')}}" type="button" class="btn btn-primary mx-1">顯示所有</a>
                @endif
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-end align-items-center border-bottom py-1">
        <a href="{{route('CreateProductPage')}}" class="btn btn-primary">新增商品主檔</a>
    </div>
    <div>
        <table class="my-2 table table-hover table-bordered">
            <tr>
                <th width="25%">【分類】名稱</th>
                <th class="text-center" width="25%">排序</th>
                <th class="text-center" width="25%">啟用</th>
                <th class="text-center" width="25%">操作</th>
            </tr>
            @foreach ($products as $product)
            <tr>
                <td>【{{$product->productCateName}}】{{$product->productName}}</td>
                <td class="text-center">{{$product->sort}}</td>
                <td class="text-center">
                    @if ($product->enable)
                    <span class="bg-success p-1 rounded text-white">啟用中</span>
                    @else
                    <span class="bg-danger p-1 rounded text-white">未啟用</span>
                    @endif</td>
                <td class="text-center">
                    <a href="{{route('ProductDetailPage',['productId'=>$product->productId])}}"
                        class="btn btn-sm btn-success">詳情</a>
                    <a href="{{route('EditProductPage',['productId'=>$product->productId])}}"
                        class="btn btn-sm btn-warning">編輯</a>
                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{$product->productId}}">刪除</button>
                    <div class="modal fade" id="deleteModal{{$product->productId}}" tabindex="-1" aria-hidden="true">
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
                                <form action="{{route('DeleteProduct')}}" method="POST">
                                    @csrf
                                    <input type="text" hidden name="deleteId" value="{{$product->productId}}">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">取消</button>
                                        <button type="submit" class="btn btn-danger">刪除</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    {{-- <table class="my-2 table table-hover table-bordered">
        <tr>
            <td width="25%" class="text-center">分類名稱</td>
            <td width="25%" class="text-center">排序</td>
            <td width="25%" class="text-center">是否啟用</td>
            <td width="25%" class="text-center">操作</td>
        </tr>
        @foreach ($categories as $category)
        <tr>
            <td class="text-center">{{$category->productCateName}}</td>
    <td class="text-center">{{$category->sort}}</td>
    <td class="text-center">@if ($category->enable == 1)
        <p class="my-0 text-success">啟用中</p>
        @else
        <p class="my-0 text-danger">未啟用</p>
        @endif</td>
    <td class="text-center">
        <button type="button" class="btn btn-warning" onclick="OpenModal({{$category->id}})">編輯</button>

        <div class="cateControl categoryModal{{$category->id}} @if ($errors->has('updateError') && $category->id==$errors->first('editId')) @else d-none @endif"
            style="width:98%;max-width:500px;position:fixed;z-index: 1050;
                      top:50%;left:50%;transform:translate(-50%,-50%);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">分類編輯</h5>
                        <button type="button" class="btn-close" onclick="CloseModal({{$category->id}})"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{route('ProductCategoryEdit')}}" method="POST">
                        @csrf
                        <div class="modal-body text-center">
                            <div class="my-2">
                                <label for="">名稱</label> <input type="text" name="title" class="text-center"
                                    value="{{old('title') ? old('title') : $category->productCateName}}">
                            </div>
                            <div class="my-2">
                                <label for="">排序</label> <input type="text" name="sort" class="text-center"
                                    value="{{old('sort') ? old('sort') : $category->sort}}">
                            </div>
                            <div class="my-2">
                                <label for="enable{{$category->id}}"><input type="radio" id="enable{{$category->id}}"
                                        name="enable" @if($category->enable ==1)
                                    checked
                                    @elseif(empty(old('enable')) && $category->enable ==1)
                                    checked
                                    @endif
                                    value="1">
                                    啟用</label>
                                <label for="no_enable{{$category->id}}"><input type="radio"
                                        id="no_enable{{$category->id}}" name="enable" @if(empty($category->enable) ||
                                    (!empty(old('enable')) &&
                                    old('enable')==0))
                                    checked
                                    @endif
                                    value="0">
                                    關閉</label>
                            </div>
                        </div>
                        @if ($errors->has('updateError') &&
                        $category->id==$errors->first('editId'))
                        <h6 class="text-danger text-center mb-2">{{$errors->first('updateError')}}</h6>

                        @endif
                        <input type="text" hidden name="editId" value="{{$category->id}}">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="CloseModal({{$category->id}})"
                                data-bs-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-warning">更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
            data-bs-target="#deleteModal{{$category->id}}">刪除</button>
        <!-- 刪除 -->
        <div class="modal fade" id="deleteModal{{$category->id}}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">確認刪除</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">刪除後將無法復原
                    </div>
                    <form action="{{route('ProductCategoryDelete')}}" method="POST">
                        @csrf
                        <input type="text" hidden name="deleteId" value="{{$category->id}}">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-danger">刪除</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </td>
    </tr>
    @endforeach
    </table> --}}
    <div class="my-2">
        {{ $products->links('my-pagination') }}
    </div>
</div>
{{-- <div class="bg d-none" onclick="CloseModal()"
    style="z-index: 1000;position: fixed;top:0;left:0;width:100vw;height:100vh;background:rgb(55, 55 ,55 , 0.59)">
</div> --}}
@endsection


@section('script')
@if ($errors->has('deleteError')){

<script>
    alert("{{$errors->first('deleteError')}}")

</script>
}
@endif
<script>
    let currentId = ''

    $(document).ready(function () {
        let cateValue = '{{$selectCategory}}';
        if (cateValue == 'all') {
            $('#cateSelect').val("{{route('AllProduct')}}")
        } else {
            let url = "{{ route('CategoryProduct',['category'=>':cateValue']) }}"
            url = url.replace(':cateValue', cateValue);
            $('#cateSelect').val(url)
        }
    })


    function OpenModal(id) {
        currentId = id;
        $('.cateControl').addClass('d-none')
        $('.bg').removeClass('d-none')
        console.log('test');
        $(`.categoryModal${id}`).removeClass('d-none')
    }

    function CloseModal(id) {
        $('.bg').addClass('d-none')

        console.log('test');
        if (id) {
            $(`.categoryModal${id}`).addClass('d-none')
        } else {
            $(`.categoryModal${currentId}`).addClass('d-none')
        }
    }

    const GoSearch = () => {
        let keyword = $('#keyword').val();
        let url = "{{route('SearchProduct',['keyword'=>':keyword'])}}"
        url = url.replace(':keyword', keyword);
        // console.log(url);
        location.href = url;
    }


    $('#cateSelect').change(function () {
        location.href = $('#cateSelect').val()
    })

</script>
@endsection
