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
                    <li class="breadcrumb-item active" aria-current="page">訂單詳情</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class=" my-3 bg-white py-3 px-2">
        <div>
            <h6 class="my-3">訂單編號：{{ $order->orderId }}</h6>
            <h6 class="my-3">訂單狀態：
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
            </h6>
            <h6 class="my-3">建立時間編號：{{ $order->created_at }}</h6>
            <hr />
            <h5 class="mt-4 fw-bold">訂單資訊</h5>
            <div class="d-flex align-items-center my-2">
                <div style="width: 100px;"></div>
                <div class="row flex-grow-1 flex-nowrap">
                    <div class="col-3 text-center">
                        <h6 class="fw-bold">商品名稱</h6>
                    </div>
                    <div class="col-3 text-center">
                        <h6 class="fw-bold">數量</h6>
                    </div>
                    <div class="col-3 text-center">
                        <h6 class="fw-bold">單價
                        </h6>
                    </div>
                    <div class="col-3 text-center">
                        <h6 class="fw-bold">小計
                        </h6>
                    </div>

                </div>
            </div>
            @foreach ($orderDetails as $orderDetail)
                <div class="d-flex align-items-center my-2">
                    <div class="me-2" style="width:100px;">
                        <img src="{{ $orderDetail->type == 0 ? '/ChocolateMarket/public/storage/product/' . $orderDetail->productId . '/product/' . $orderDetail->filename : '/ChocolateMarket/public/storage/additional_product/' . $orderDetail->productAdditionId . '/' . $orderDetail->filename }}"
                            alt="" class="w-100">
                    </div>
                    <div class="row flex-grow-1">
                        <div class="col-3  text-center">
                            <h6>{{ $orderDetail->name }}</h6>
                        </div>
                        <div class="col-3  text-center">
                            <h6>x{{ $orderDetail->count }}</h6>
                        </div>
                        <div class="col-3  text-center">
                            <h6>{{ $orderDetail->type == 0 ? round($orderDetail->productPrice) : round($orderDetail->productAdditionPrice) }}
                            </h6>
                        </div>
                        <div class="col-3  text-center">
                            <h6>{{ $orderDetail->type == 0 ? round($orderDetail->productPrice * $orderDetail->count) : round($orderDetail->productAdditionPrice * $orderDetail->count) }}
                            </h6>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
        <hr />
        <div>
            <h5>訂單總額：NT$ {{ $order->Total }}</h5>
            <h5>備註：{{ empty($order->remark) ? '無' : $order->remark }}</h5>
        </div>
    </div>
@endsection

@section('script')
@endsection
