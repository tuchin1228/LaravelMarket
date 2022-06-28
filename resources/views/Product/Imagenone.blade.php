@extends('dashboard_layout')

@section('head')
<title>圖片管理</title>
@endsection

@section('body')
<div class="container py-2 max-w-5xl bg-white mx-auto shadow-md p-10 " style="min-height: 50vh">
    {{-- {{print_r($images)}} --}}
    <div class="d-flex flex-wrap " style="border:1px solid #d8d8d8;border-radius:5px;padding:10px;">
        <div style="width: 100%;" class="d-flex justify-content-between align-items-center">
            <h2 style=" font-weight:600;font-size:1.8rem;">未發布圖片</h2>
            <form action="{{route('DeleteProductImageNotuse')}}" method="POST">
                @csrf
                <input type="text" name="type" value="1" hidden>
                <button type="submit"
                    class="btn btn-danger rounded px-3 py-2 text-base bg-red-500 hover:bg-red-400 text-white font-bold">全部刪除</button>
            </form>
        </div>
        @foreach ($notitle_images as $image)
        <div class="" style=" width: 95%;max-width:150px;margin:10px 15px;">
            <p class="text-center">{{$image->date}}</p>
            <img style="width:100%;"
                src="../../storage/product/{{$image->product_id}}/{{$image->product_type}}/{{$image->filename}}"
                alt="">
        </div>
        @endforeach
    </div>
    <div class="mt-5 px-2 rounded" style="border:1px solid #d8d8d8;">
        <div style="width: 100%;" class="py-3 d-flex justify-content-between align-items-center">
            <h2 style=" font-weight:600;font-size:1.8rem;">未使用圖片</h2>
            <form action="{{route('DeleteProductImageNotuse')}}" method="POST">
                @csrf
                <input type="text" name="type" value="2" hidden>
                <button type="submit" class="btn btn-danger rounded px-3 py-2 text-base bg-red-500 hover:bg-red-400 text-white
                font-bold">全部刪除</button>
            </form>
        </div>
        @foreach ($hastitle_images as $image)
        <div class="" style="border-bottom:1px solid #d8d8d8;border-radius:5px;padding:10px;margin:10px 0;">
            <p>{{$image->productName}} - {{$image->created_at}}</p>
            <img style="max-width: 200px"
                src="../../storage/product/{{$image->product_id}}/{{$image->product_type}}/{{$image->filename}}"
                alt="">
        </div>
        @endforeach
    </div>
</div>
@endsection


@section('script')
@endsection
