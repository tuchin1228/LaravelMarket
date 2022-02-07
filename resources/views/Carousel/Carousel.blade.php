@extends('dashboard_layout')

@section('head')
<title>輪播設定</title>

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
    <div class="breadcrumb-title pe-3">輪播設定</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">輪播設定</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="text-end border-bottom py-1">
        <a href="{{route('AddCarousel')}}" class="btn btn-primary">新增輪播</a>
    </div>
    <ul>
        @foreach ($carousels as $carousel)
        <li class="py-1 my-1 border-bottom d-flex align-items-start">
            <div class="me-2">
                <img src="./storage/Carousel/{{$carousel->filename}}" alt="">
            </div>
            <div style="width: 100%">
                <h5>{{$carousel->title}}<span class="fs-6 text-secondary">
                        (@if ($carousel->title == 0) 桌機 @else 手機 @endif)</span></h5>
                <h6><a href="{{$carousel->url}}" target="_blank">{{$carousel->url}}</a></h6>
                <h6 class="text-secondary">排序：{{$carousel->sort}}</h6>
                <h6>
                    @if ($carousel->enable)
                    <span class="fw-bold text-white p-1 bg-success rounded">啟用中</span>
                    @else
                    <span class="fw-bold text-white p-1 bg-danger rounded">未啟用</span>
                    @endif
                </h6>
                <div class="text-end">
                    <a href="{{route('EditCarousel',['editId'=>$carousel->id])}}" type="button"
                        class="btn btn-sm mx-1 btn-warning">編輯</a>
                    <button type="button" class="btn btn-sm mx-1 btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{$carousel->id}}">刪除</button>
                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal{{$carousel->id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">確認刪除</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">刪除後將無法復原
                                </div>
                                <form action="{{route('DeleteCarousel')}}" method="POST">
                                    @csrf
                                    <input type="text" hidden name="filename" value="{{$carousel->filename}}">
                                    <input type="text" hidden name="deleteId" value="{{$carousel->id}}">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">取消</button>
                                        <button type="submit" class="btn btn-danger">刪除</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
        @endforeach
        {{-- <li class="py-1 my-1 border-bottom">
            <img src="https://i.picsum.photos/id/866/2000/900.jpg?hmac=Q7Pce-qYliZDB6Ogym9t69FEl2HSz5153lZcv0qQ5ws"
                alt="">
        </li>
        <li class="py-1 my-1 border-bottom">
            <img src="https://i.picsum.photos/id/534/2000/900.jpg?hmac=WnaCi2wMOaF5NvnFDs-IzjXp0_f4WjH3geAVVSPLooA"
                alt="">
        </li>
        <li class="py-1 my-1 border-bottom">
            <img src="https://i.picsum.photos/id/326/2000/900.jpg?hmac=EQiq7_mGnGsnLnQbvwmdA6E040ShE7FeuBgg6627ZK8"
                alt="">
        </li> --}}
    </ul>
</div>


@endsection
