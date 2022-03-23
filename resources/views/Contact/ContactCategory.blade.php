@extends('dashboard_layout')

@section('head')
    <title>問題分類管理</title>
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
        <div class="breadcrumb-title pe-3">聯絡管理</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">問題分類管理</li>
                </ol>
            </nav>
        </div>


    </div>
    <div class=" my-3 bg-white p-2">
        <div class="d-flex justify-content-end align-items-center border-bottom py-1">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">新增分類</button>
                <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">

                            <form action="{{ route('AddContactCategory') }}" method="POST">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">新增分類</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                    <div class="my-3">
                                        <label for="title" class="d-block" style="font-size:1.2rem">分類名稱</label>
                                        <input type="text" id="title" name="title" value="" class="form-control"
                                            placeholder="輸入分類名稱">
                                    </div>
                                    <div class="my-3">
                                        <label for="sort" class="d-block" style="font-size:1.2rem">排序</label>
                                        <input type="number" id="sort" name="sort" class="form-control" value=""
                                            placeholder="輸入排序號">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                    <button type="submit" class="btn btn-primary">新增</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
        </div>
        <table class="my-2 table table-hover table-bordered">
            <tr>
                <td width="25%" class="text-center">分類名稱</td>
                <td width="25%" class="text-center">排序</td>
                <td width="25%" class="text-center">操作</td>
            </tr>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->title }}</td>
                    <td class="text-center">{{ $category->sort }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editModal{{ $category->id }}">編輯</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $category->id }}">刪除</button>

                        <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">

                                    <form action="{{ route('EditContactCategory') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">編輯分類</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="my-3">
                                                <label for="title" class="d-block"
                                                    style="font-size:1.2rem">分類名稱</label>
                                                <input type="text" id="title" name="title" value="{{ $category->title }}"
                                                    class="form-control" placeholder="輸入分類名稱">
                                            </div>
                                            <div class="my-3">
                                                <input type="text" hidden name="categoryId" value="{{ $category->id }}" />
                                                <label for="sort" class="d-block" style="font-size:1.2rem">排序</label>
                                                <input type="number" id="sort" name="sort" class="form-control"
                                                    value="{{ $category->sort }}" placeholder="輸入排序號">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">取消</button>
                                            <button type="submit" class="btn btn-primary">更新</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <form action="{{ route('DeleteContactCategory') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">刪除分類</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <h5 class="text-center">確認刪除<span
                                                    class="fw-bold text-danger">{{ $category->title }}</span></h5>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="text" hidden name="categoryId" value="{{ $category->id }}" />
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
    
@endsection

@if ($errors->has('deleteError'))
    {
    <script>
        alert("{{ $errors->first('deleteError') }}")
    </script>
    }
@endif
@section('script')
@endsection
