@extends('dashboard_layout')

@section('head')
<title>會員管理</title>

<style>
    ul {
        padding: 0;
    }

    ul li img {
        max-width: 200px;
    }

    #imgModal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.9);
    }

    #imgModal .modalContent {
        margin: auto;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    #imgModal .modalContent {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    .content div {
        white-space: normal !important;
    }

    .content div p span {
        white-space: normal !important;
    }

    .inner a {
        border-bottom: 1px solid transparent;
        color: rgb(16, 80, 255);
    }

    .inner a:hover {
        border-bottom: 1px solid rgb(16, 80, 255);
    }

    @-webkit-keyframes zoom {
        from {
            -webkit-transform: translate(-50%, -50%) scale(0)
        }

        to {
            -webkit-transform: translate(-50%, -50%) scale(1)
        }
    }

    @keyframes zoom {
        from {
            transform: translate(-50%, -50%) scale(0)
        }

        to {
            transform: translate(-50%, -50%) scale(1)
        }
    }

    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    @media only screen and (max-width: 700px) {
        #imgModal .modalContent {
            width: 100%;
        }
    }

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
    <div class="breadcrumb-title pe-3">會員系統</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">會員管理</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    {{-- <div class="d-flex justify-content-end align-items-center border-bottom py-1">
        <a href="{{route('AddNews')}}" class="btn btn-primary">新增會員</a>
</div> --}}
<div class="d-flex align-items-center">
    <input type="text" name="keyword" class="form-control" id="keyword" style="max-width:250px;" value=""
        placeholder="姓名、電話、信箱關鍵字搜尋">
    <button type="button" onclick="GoSearch()" class="btn btn-primary mx-1">搜尋</button>
    <a href="{{route('User')}}" type="button" class="btn btn-primary mx-1">顯示所有</a>

</div>
<div class="d-flex flex-wrap">
    @foreach ($Users as $User)
    <div style="width: 300px;" class="p-2 border m-2 rounded">
        @if ($User->avator)
        <div class=" rounded-circle p-1 border"
            style="width:60px;height:60px;margin:0 auto;background:url({{ $User->avator}});background-position:center;background-size:cover;">
        </div>
        @else
        <div class=" rounded-circle p-1 border" style="background:white; width:60px;height:60px;margin:0 auto;">
        </div>
        @endif
        <h5 class="text-center fw-normal fs-6 mt-2">{{$User->name}}</h5>
        <h5 class="text-center fw-normal fs-6">{{$User->phone}}</h5>
        <h5 class="text-center fw-normal fs-6">{{$User->email}}</h5>
        <div class="text-center">
            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                data-bs-target="#detailModal{{$User->id}}">詳情</button>
            <a href="{{route('EditUser',['userId'=>$User->id])}}" type="button" class="btn btn-warning">編輯</a>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                data-bs-target="#deleteModal{{$User->id}}">刪除</button>
        </div>
        <!-- 詳情 -->
        <div class="modal fade" id="detailModal{{$User->id}}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">會員詳情</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="fw-bold text-start">會員名稱</h5>
                        <h6 class="text-start fw-normal ">{{$User->name}}</h6>
                        <hr />
                        <h5 class="fw-bold text-start">會員電話</h5>
                        <h6 class="text-start fw-normal ">{{$User->phone}}</h6>
                        <hr />
                        <h5 class="fw-bold text-start">會員信箱</h5>
                        <h6 class="text-start fw-normal ">{{$User->email}}</h6>
                        <hr />
                        <h5 class="fw-bold text-start">會員地址</h5>
                        <h6 class="text-start fw-normal ">{{$User->county.$User->area.$User->address}}</h6>
                        <hr />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- 刪除 -->
        <div class="modal fade" id="deleteModal{{$User->id}}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">確認刪除</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">刪除後將無法復原
                    </div>
                    <form action="{{route('DeleteUser')}}" method="POST">
                        @csrf
                        <input type="text" hidden name="deleteId" value="{{$User->id}}">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                            <button type="submit" class="btn btn-danger">刪除</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>




{{-- <table class=" table table-bordered my-2 table-hover">
    <tr>
        <th width="20%" class="text-center ">頭像</th>
        <th width="20%" class="text-center ">姓名</th>
        <th width="20%" class="text-center ">電話</th>
        <th width="20%" class="text-center ">信箱</th>
        <th width="20%" class="text-center ">操作</th>
    </tr>
    @foreach ($Users as $User)
    <tr>
        <td class="text-center">
            @if ($User->avator)
            <div class=" rounded-circle p-1 border"
                style="width:60px;height:60px;margin:0 auto;background:url({{ $User->avator}});background-position:center;background-size:cover;">
</div>
@else
<div class=" rounded-circle p-1 border" style="background:white; width:60px;height:60px;margin:0 auto;">
</div>
@endif
</td>
<td class="text-center">{{$User->name}}</td>
<td class="text-center">{{$User->phone}}</td>
<td class="text-center">{{$User->email}}</td>
<td class="text-center">
    <button type="button" class="btn btn-success" data-bs-toggle="modal"
        data-bs-target="#detailModal{{$User->id}}">詳情</button>
    <a href="{{route('EditUser',['userId'=>$User->id])}}" type="button" class="btn btn-warning">編輯</a>
    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
        data-bs-target="#deleteModal{{$User->id}}">刪除</button>
    <!-- 詳情 -->
    <div class="modal fade" id="detailModal{{$User->id}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">會員詳情</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="fw-bold text-start">會員名稱</h5>
                    <h6 class="text-start">{{$User->name}}</h6>
                    <hr />
                    <h5 class="fw-bold text-start">會員電話</h5>
                    <h6 class="text-start">{{$User->phone}}</h6>
                    <hr />
                    <h5 class="fw-bold text-start">會員信箱</h5>
                    <h6 class="text-start">{{$User->email}}</h6>
                    <hr />
                    <h5 class="fw-bold text-start">會員地址</h5>
                    <h6 class="text-start">{{$User->county.$User->area.$User->address}}</h6>
                    <hr />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>
                </div>
            </div>
        </div>
    </div>
    <!-- 刪除 -->
    <div class="modal fade" id="deleteModal{{$User->id}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">確認刪除</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">刪除後將無法復原
                </div>
                <form action="{{route('DeleteUser')}}" method="POST">
                    @csrf
                    <input type="text" hidden name="deleteId" value="{{$User->id}}">
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
    {{ $Users->links('my-pagination') }}
</div>
</div>
@endsection

@section('script')
<script>
    const GoSearch = () => {
        let keyword = $('#keyword').val();
        let url = "{{route('SearchUser',['keyword'=>':keyword'])}}"
        url = url.replace(':keyword', keyword);
        // console.log(url);
        location.href = url;
    }

</script>
@endsection
