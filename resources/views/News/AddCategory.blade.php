@extends('dashboard_layout')

@section('head')
<title>新增最新消息分類</title>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">最新消息</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{route('News')}}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">新增最新消息分類</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="">
        <form class="w-3/5 mx-auto" method="POST" action="{{route('AddCategory')}}">
            @csrf
            <div class="my-2">
                <label for="">名稱</label> <input type="text" name="title" required class="form-control my-2"
                    value="{{old('title')}}">
            </div>
            <div class="my-2">
                <label for="">排序</label> <input type="text" name="sort" class="form-control my-2"
                    value="{{old('sort')}}">
            </div>
            <div class="my-2">
                <label for="enable"><input type="radio" id="enable" name="enable" value="1" @if (old('enable')==1 ||
                        old('enable')==null ) checked @else @endif>
                    啟用</label>
                <label for="no_enable"><input type="radio" id="no_enable" name="enable" value="0"
                        @if(old('enable')!==null && old('enable')==0) checked @endif>
                    關閉</label>
            </div>
            @if ($errors->has('AddError'))
            <h6 class="text-danger text-center mb-2">{{$errors->first('AddError')}}</h6>
            @endif
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary px-5">確認新增</button>
            </div>
        </form>
    </div>
</div>
@endsection
