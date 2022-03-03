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
    <div class="breadcrumb-title pe-3">商品管理</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">加購品管理</li>
            </ol>
        </nav>
    </div>

</div>

<div class=" my-3 bg-white p-2">
    <div class="d-flex justify-content-end align-items-center border-bottom py-1">
        <a href="{{route('ProductAdditionalAdd')}}" class="btn btn-primary">新增加購品</a>
    </div>
    <div>
        <table class="my-2 table table-hover table-bordered">
            <tr>
                <th width="25%">名稱</th>
                <th class="text-center" width="25%">排序</th>
                <th class="text-center" width="25%">啟用</th>
                <th class="text-center" width="25%">操作</th>
            </tr>
            @foreach ($productAdditionals as $productAdditional)
            <tr>
                <td>{{$productAdditional->productAdditionName}}</td>
                <td class="text-center">{{$productAdditional->sort}}</td>
                <td class="text-center">
                    @if ($productAdditional->enable)
                    <span class="bg-success p-1 rounded text-white">啟用中</span>
                    @else
                    <span class="bg-danger p-1 rounded text-white">未啟用</span>
                    @endif</td>
                <td class="text-center">
                    <a href="{{route('ProductAdditionalDetail',$productAdditional->productAdditionId)}}"
                        class="btn btn-sm btn-success">詳情</a>
                    <button type="button" class="btn btn-sm btn-danger " data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{$productAdditional->productAdditionId}}">刪除</button>
                    <div class="modal fade" id="deleteModal{{$productAdditional->productAdditionId}}" tabindex="-1"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">確認刪除</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <h5 class="fw-bold">即將刪除加購品與關聯商品主檔資訊</h5>
                                    <h6>刪除項目-<strong>{{$productAdditional->productAdditionName}}</strong></h6>
                                </div>
                                <form action="{{route('ProductAdditionalDelete')}}" method="POST">
                                    @csrf
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
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    <div class="my-2">
        {{ $productAdditionals->links('my-pagination') }}
    </div>
</div>
@endsection
