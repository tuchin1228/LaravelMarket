@extends('dashboard_layout')

@section('head')
    <title>
        聯絡管理</title>
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
                    <li class="breadcrumb-item active" aria-current="page">所有聯絡管理</li>
                </ol>
            </nav>
        </div>


    </div>
    <div class=" my-3 bg-white p-2">
        {{-- <div class="d-flex align-items-center"> --}}
        {{-- @if (empty($keyword))
                <select name="status" id="status" class="form-select " style="width: fit-content">
                    <option value="all" @if (empty($status)) selected @endif>全部</option>
                    <option value="0" @if (!empty($status) && $status == 0) selected @endif>尚未處理</option>
                    <option value="1" @if (!empty($status) && $status == 1) selected @endif>處理中</option>
                    <option value="2" @if (!empty($status) && $status == 2) selected @endif>已完成</option>
                    <option value="3" @if (!empty($status) && $status == 3) selected @endif>無回應</option>
                </select>
            @endif --}}
        {{-- <div class="d-flex align-items-center" style="width:fit-content"> --}}

        {{-- <input type="text" class="form-control m-1" id="searchInput" style="max-width:300px;" />
        @if (empty($keyword))
            <button class="btn btn-primary m-1">搜尋</button>
        @else
            <button class="btn btn-primary m-1 ">顯示全部</button>
        @endif --}}
        {{-- </div> --}}

        <div class="d-flex align-items-center">
            @if (empty($keyword))
                <select name="status" id="status" class="form-select mx-1" style="width: fit-content">
                    <option value="all" @if (empty($status)) selected @endif>全部</option>
                    <option value="0" @if (!empty($status) && $status == 0) selected @endif>尚未處理</option>
                    <option value="1" @if (!empty($status) && $status == 1) selected @endif>處理中</option>
                    <option value="2" @if (!empty($status) && $status == 2) selected @endif>已完成</option>
                    <option value="3" @if (!empty($status) && $status == 3) selected @endif>無回應</option>
                </select>
            @endif
            <input type="text" name="keyword" class="form-control" id="keyword" style="max-width:300px;" value=""
                placeholder="姓名、電話、信箱關鍵字搜尋">

            @if (empty($keyword))
                <button type="button" onclick="GoSearch()"  class="btn btn-primary mx-1">搜尋</button>
            @else
                <a href="{{route('Contact')}}"
                    class="btn btn-primary mx-1">顯示所有</a>
            @endif
        </div>
        {{-- </div> --}}
        <table class="my-2 table table-hover table-bordered">
            <tr>
                <td width="25%" class="text-center">狀態</td>
                <td width="25%" class="text-center">姓名</td>
                <td width="25%" class="text-center">操作</td>
            </tr>
            @foreach ($contacts as $contact)
                <tr>
                    <td>
                        @switch($contact->status)
                            @case(1)
                                <div class="text-center "><span class="text-white bg-danger py-1 px-2 fs-5 rounded">尚未處理</span>
                                </div>
                            @break

                            @case(2)
                                <div class="text-center "><span class="text-white bg-warning py-1 px-2 fs-5 rounded">處理中</span>
                                </div>
                            @break

                            @case(3)
                                <div class="text-center "><span class="text-white bg-success py-1 px-2 fs-5 rounded">已完成</span>
                                </div>
                            @break

                            @case(4)
                                <div class="text-center "><span class="text-white bg-secondary py-1 px-2 fs-5 rounded">無回應</span>
                                </div>
                            @break

                            @default
                        @endswitch

                    </td>
                    <td class="text-center fs-5">{{ $contact->name }}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                            data-bs-target="#detailModal{{ $contact->id }}">詳情</button>

                        <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#updateModal{{ $contact->id }}">狀態備註</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#deleteModal{{ $contact->id }}">刪除</button>

                        <div class="modal fade" id="detailModal{{ $contact->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">聯絡詳情</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start row">
                                        <div class="my-3 col-12 col-md-6">
                                            <h5 class="fw-bold text-start">姓名</h5>
                                            <h6 class="text-start fw-normal ">{{ $contact->name }}</h6>
                                        </div>
                                        <div class="my-3 col-12 col-md-6">
                                            <h5 class="fw-bold text-start">連絡電話</h5>
                                            <h6 class="text-start fw-normal ">{{ $contact->phone }}</h6>
                                        </div>
                                        <div class="my-3 col-12 col-md-6">
                                            <h5 class="fw-bold text-start">信箱</h5>
                                            <h6 class="text-start fw-normal ">{{ $contact->email }}</h6>
                                        </div>
                                        <div class="my-3 col-12 col-md-6">
                                            <h5 class="fw-bold text-start">問題分類</h5>
                                            <h6 class="text-start fw-normal ">{{ $contact->title }}</h6>
                                        </div>
                                        <div class="my-3 col-12 col-md-6">
                                            <h5 class="fw-bold text-start">建立時間</h5>
                                            <h6 class="text-start fw-normal ">{{ $contact->created_at }}</h6>
                                        </div>
                                        <div class="my-3 col-12 col-md-6">
                                            <h5 class="fw-bold text-start">狀態</h5>
                                            <h6 class="text-start fw-normal "> @switch($contact->status)
                                                    @case(1)
                                                        <span class="text-white bg-danger py-1 px-2 fs-5 rounded">尚未處理</span>
                                                    @break

                                                    @case(2)
                                                        <span class="text-white bg-warning py-1 px-2 fs-5 rounded">處理中</span>
                                                    @break

                                                    @case(3)
                                                        <span class="text-white bg-success py-1 px-2 fs-5 rounded">已完成</span>
                                                    @break

                                                    @case(4)
                                                        <span class="text-white bg-secondary py-1 px-2 fs-5 rounded">無回應</span>
                                                    @break

                                                    @default
                                                @endswitch
                                            </h6>
                                        </div>
                                        <div class="my-3 col-12 col-md-6">
                                            <h5 class="fw-bold text-start">需求說明</h5>
                                            <h6 class="text-start fw-normal ">{{ $contact->direction }}</h6>
                                        </div>
                                        <div class="my-3 col-12 col-md-6">
                                            <h5 class="fw-bold text-start">備註</h5>
                                            <h6 class="text-start fw-normal ">{{ $contact->remark }}</h6>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">關閉</button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="updateModal{{ $contact->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title fw-bold">更新狀態與備註</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-start row">
                                        <form action="{{ route('ContactUpdate') }}" method="POST">
                                            @csrf
                                            <div class="my-3">
                                                <h5 class="fw-bold text-start">狀態</h5>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="1" @if ($contact->status == 1) selected @endif>
                                                        尚未處理
                                                    </option>
                                                    <option value="2" @if ($contact->status == 2) selected @endif>處理中
                                                    </option>
                                                    <option value="3" @if ($contact->status == 3) selected @endif>已完成
                                                    </option>
                                                    <option value="4" @if ($contact->status == 4) selected @endif>無回應
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="my-3">
                                                <h5 class="fw-bold text-start">備註</h5>
                                                <textarea name="remark" id="remark" class="form-control" cols="30" rows="3">{{ $contact->remark }}</textarea>
                                            </div>
                                            <div class="modal-footer">
                                                <input type="text" hidden name="contactId" value="{{ $contact->id }}">
                                                <button type="submit" class="btn btn-primary">更新</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">關閉</button>
                                            </div>
                                        </form>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="deleteModal{{ $contact->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">

                                    <form action="{{ route('ContactDelete') }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title fw-bold">刪除聯繫資料</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <h5 class="text-center text-danger">確認刪除<span class="fw-bold "></span>
                                            </h5>
                                        </div>
                                        <div class="modal-footer">
                                            <input type="text" hidden name="contactId" value="{{ $contact->id }}" />
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
        <div class="my-2">
            {{ $contacts->links('my-pagination') }}
        </div>
    </div>
@endsection


@section('script')
    <script>
        $('#status').on('change', function() {
            let status = $('#status').val();
            let url = "{{ route('ContactStatus', ['status' => ':status']) }}";
            url = url.replace(':status', status);
            console.log(url);
            location.href = url;

        })

        const GoSearch = () =>{
            let keyword = $('#keyword').val();

            let url = " {{route('ContactSearch',['keyword' => ':keyword'])}}";
            url = url.replace(':keyword', keyword);
            // console.log(url);

            location.href = url;

        }

       
    </script>
@endsection
