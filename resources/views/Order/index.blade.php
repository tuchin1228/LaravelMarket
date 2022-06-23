@extends('dashboard_layout')

@section('head')
    <title>
        訂單管理</title>
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
        <div class="breadcrumb-title pe-3">訂單管理</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">所有訂單</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class=" my-3 bg-white p-2">
        <div>
            <table class="my-2 table table-hover table-bordered">
                <tr>
                    <th width="20%">訂單編號</th>
                    <th class="text-center" width="20%">購買人</th>
                    <th class="text-center" width="20%">訂單狀態</th>
                    <th class="text-center" width="20%">建立時間</th>
                    <th class="text-center" width="20%">操作</th>
                </tr>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->orderId }}</td>
                        <td class="text-center">{{ $order->payName }}</td>
                        <td class="text-center">
                            @switch($order->payStatus)
                                @case(0)
                                    <span class="bg-secondary p-1 text-white">未付款</span>
                                @break

                                @case(1)
                                    <span class="bg-success p-1 text-white">已付款</span>
                                @break

                                @case(2)
                                    <span class="bg-warning p-1 text-white">申請取消</span>
                                @break

                                @case(3)
                                    <span class="bg-danger p-1 text-white">已取消</span>
                                @break

                                @default
                            @endswitch
                        </td>
                        <td>{{ $order->created_at }}</td>
                        <td class="text-center">
                            <a href="{{route('GetOrderDetail',['orderId'=>$order->orderId])}}" class="btn btn-sm btn-success">詳情</a>
                        
                        </td>
                        {{-- <td class="text-center">{{ $product->sort }}</td>
                        <td class="text-center">
                            @if ($product->enable)
                                <span class="bg-success p-1 rounded text-white">啟用中</span>
                            @else
                                <span class="bg-danger p-1 rounded text-white">未啟用</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('ProductDetailPage', ['productId' => $product->productId]) }}"
                                class="btn btn-sm btn-success">詳情</a>
                            <a href="{{ route('EditProductPage', ['productId' => $product->productId]) }}"
                                class="btn btn-sm btn-warning">編輯</a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteModal{{ $product->productId }}">刪除</button>
                            <div class="modal fade" id="deleteModal{{ $product->productId }}" tabindex="-1"
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
                                            <h5>確認刪除<span class="fw-bold">{{ $product->productName }}</span></h5>
                                        </div>
                                        <form action="{{ route('DeleteProduct') }}" method="POST">
                                            @csrf
                                            <input type="text" hidden name="deleteId" value="{{ $product->productId }}">
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">取消</button>
                                                <button type="submit" class="btn btn-danger">刪除</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td> --}}
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="my-2">
            {{ $orders->links('my-pagination') }}
        </div>
    </div>
@endsection

@section('script')
@endsection
