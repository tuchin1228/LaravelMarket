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
                <li class="breadcrumb-item active" aria-current="page">標籤管理</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="d-flex justify-content-end align-items-center border-bottom py-1">
        <a href="{{route('ProductTagAddPage')}}" class="btn btn-primary">新增標籤</a>
    </div>
    <table class="my-2 table table-hover table-bordered">
        <tr>
            <td width="33%" class="text-center">標籤名稱</td>
            <td width="33%" class="text-center">樣式</td>
            <td width="33%" class="text-center">操作</td>
        </tr>
        @foreach ($tags as $tag)
        <tr>
            <td class="text-center">{{$tag->tagName}}</td>
            <td class="text-center">
                <span class="p-2"
                    style="background:{{$tag->bgColor}};color:{{$tag->textColor}};">{{$tag->tagName}}</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-warning" onclick="OpenModal({{$tag->id}})">編輯</button>

                <div class="cateControl tagModal{{$tag->id}} @if ($errors->has('updateError') && $tag->id==$errors->first('editId')) @else d-none @endif"
                    style="width:98%;max-width:500px;position:fixed;z-index: 1050;
                      top:50%;left:50%;transform:translate(-50%,-50%);">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">分類編輯</h5>
                                <button type="button" class="btn-close" onclick="CloseModal({{$tag->id}})"
                                    data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>

                            <form action="{{route('ProductTagEdit')}}" method="POST">
                                @csrf
                                <div class="modal-body text-center">
                                    <div class="my-2">
                                        <label for="">名稱</label> <input style="border:1px solid black;" type="text"
                                            name="title" class="text-center"
                                            value="{{old('title') ? old('title') : $tag->tagName}}">
                                    </div>
                                    <div class="my-2">
                                        <label for="">背景色</label> <input type="color" style="vertical-align: middle"
                                            name="bgColor" class="text-center"
                                            value="{{old('bgColor') ? old('bgColor') : $tag->bgColor}}">
                                    </div>
                                    <div class="my-2">
                                        <label for="">字體色</label> <input type="color" style="vertical-align: middle"
                                            name="textColor" class="text-center"
                                            value="{{old('textColor') ? old('textColor') : $tag->textColor}}">
                                    </div>
                                </div>
                                @if ($errors->has('updateError') &&
                                $tag->id==$errors->first('editId'))
                                <h6 class="text-danger text-center mb-2">{{$errors->first('updateError')}}</h6>

                                @endif
                                <input type="text" hidden name="editId" value="{{$tag->id}}">
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" onclick="CloseModal({{$tag->id}})"
                                        data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-warning">更新</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteModal{{$tag->id}}">刪除</button>
                <!-- 刪除 -->
                <div class="modal fade" id="deleteModal{{$tag->id}}" tabindex="-1" aria-hidden="true"> --}}
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold">確認刪除</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center">刪除後將無法復原
                            </div>
                            <form action="{{route('ProductTagDelete')}}" method="POST">
                                @csrf
                                <input type="text" hidden name="deleteId" value="{{$tag->id}}">
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
    </table>
</div>
<div class="bg d-none" onclick="CloseModal()"
    style="z-index: 1000;position: fixed;top:0;left:0;width:100vw;height:100vh;background:rgb(55, 55 ,55 , 0.59)">
</div>
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

    function OpenModal(id) {
        currentId = id;
        $('.cateControl').addClass('d-none')
        $('.bg').removeClass('d-none')
        console.log('test');
        $(`.tagModal${id}`).removeClass('d-none')
    }

    function CloseModal(id) {
        $('.bg').addClass('d-none')

        console.log('test');
        if (id) {
            $(`.tagModal${id}`).addClass('d-none')
        } else {
            $(`.tagModal${currentId}`).addClass('d-none')
        }
    }

</script>
@endsection
