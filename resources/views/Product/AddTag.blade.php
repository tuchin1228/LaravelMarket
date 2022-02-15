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
                <li class="breadcrumb-item"><a href="{{route('ProductTag')}}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">新增商品標籤</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <form class="w-3/5 mx-auto" method="POST" action="{{route('ProductTagAdd')}}">
        @csrf
        <div class="my-2">
            <label for="">名稱</label> <input type="text" name="title" required class="form-control my-2"
                value="{{old('title')}}">
        </div>
        <div class="my-2">
            <label for="">背景色</label> <input style="vertical-align: middle" type="color" name="bgColor" class=" my-2"
                value="{{old('bgColor')}}">
        </div>
        <div class="my-2">
            <label for="">字體色</label> <input style="vertical-align: middle" type="color" name="textColor" class=" my-2"
                value="{{old('textColor')}}">
        </div>
        @if ($errors->has('AddError'))
        <h6 class="text-danger text-center mb-2">{{$errors->first('AddError')}}</h6>
        @endif
        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary px-5">確認新增</button>
        </div>
    </form>

</div>
@endsection
