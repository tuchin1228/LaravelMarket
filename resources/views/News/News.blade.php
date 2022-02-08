@extends('dashboard_layout')

@section('head')
<title>最新消息</title>

<style>
    ul {
        padding: 0;
    }

    ul li img {
        max-width: 200px;
    }

</style>

@endsection

@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">最新消息</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">所有最新消息</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="d-flex justify-content-end align-items-center border-bottom py-1">
        <a href="{{route('AddNews')}}" class="btn btn-primary">新增消息</a>
    </div>
</div>
@endsection
