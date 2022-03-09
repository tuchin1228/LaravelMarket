@extends('dashboard_layout')

@section('head')
<title>品牌介紹</title>

<style>
    ul {
        padding: 0;
    }

    ul li img {
        max-width: 200px;
    }

    #imgModal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.9);
    }

    #imgModal .modalContent {
        margin: auto;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    #imgModal .modalContent {
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    .content div {
        white-space: normal !important;
    }

    .content div p span {
        white-space: normal !important;
    }

    .inner a {
        border-bottom: 1px solid transparent;
        color: rgb(16, 80, 255);
    }

    .inner a:hover {
        border-bottom: 1px solid rgb(16, 80, 255);
    }

    @-webkit-keyframes zoom {
        from {
            -webkit-transform: translate(-50%, -50%) scale(0)
        }

        to {
            -webkit-transform: translate(-50%, -50%) scale(1)
        }
    }

    @keyframes zoom {
        from {
            transform: translate(-50%, -50%) scale(0)
        }

        to {
            transform: translate(-50%, -50%) scale(1)
        }
    }

    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    @media only screen and (max-width: 700px) {
        #imgModal .modalContent {
            width: 100%;
        }
    }

    .modal-body img {
        width: 100%;
        height: auto;
    }

</style>

@endsection

@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">品牌介紹</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">所有品牌介紹</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="d-flex justify-content-end align-items-center border-bottom py-1">
        {{-- <select id="cateSelect" class="form-select mx-2 w-auto " aria-label="Default select example">
            <option value="{{ route('News') }}">全部</option>
        @foreach ($category as $cate)
        <option value="{{ route('CategoryOfNews',['cateId'=>$cate->id]) }}">
            {{$cate->articles_cate_title}}
        </option>
        @endforeach
        </select> --}}
        <a href="{{route('AddAbout')}}" class="btn btn-primary">新增品牌介紹</a>
    </div>
    <div class="d-flex align-items-start">

        @foreach ($abouts as $about)
        <article class="p-2 border m-2" style="width: 300px;">
            {{-- <img onclick="LargeImage('{{$about->about_id}}')" class="img{{$about->about_id}} w-full me-2"
            src="./storage/news/{{$about->about_id}}/{{$about->imageUrl}}" alt=""> --}}
            <img class="img{{$about->about_id}} w-100 me-2"
                src="./storage/HomeAbout/{{$about->about_id}}/{{$about->filename}}" alt="">
            <div class="article_content my-2" style="width: 100%">
                <div>
                    <h2 class="fs-5 fw-bolder">{{$about->title}}</h2>
                    {{-- <p class="text-secondary my-1">建立日期：{{$article->created_at}}</p> --}}

                </div>
                <div class="text-end">
                    <button class="btn btn-success" type="button" data-bs-toggle="modal"
                        data-bs-target="#aboutModal{{$about->about_id}}">查看</button>
                    <a href="{{route('EditAbout',['about_id'=>$about->about_id])}}" class="btn btn-warning"
                        type="button">編輯</a>
                    <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{$about->about_id}}">刪除</button>
                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal{{$about->about_id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">確認刪除</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">刪除後將無法復原
                                </div>
                                <form action="{{route('DeleteAbout')}}" method="POST">
                                    @csrf
                                    <input type="text" hidden name="deleteId" value="{{$about->about_id}}">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">取消</button>
                                        <button type="submit" class="btn btn-danger">刪除</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="aboutModal{{$about->about_id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="my-0">標題：</h5>
                                    <h5 class="modal-title fw-bold">{{$about->title}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body text-start">
                                    <div class="d-flex align-items-center">
                                        <h5 class="my-0">首頁標題：</h5>
                                        <h5 class="modal-title ">{{$about->subtitle}}</h5>
                                    </div>
                                    <hr />
                                    <div class="d-flex align-items-center">
                                        <h5 class="my-0">首頁簡介：</h5>
                                        <h5 class="modal-title ">{{$about->intro}}</h5>
                                    </div>
                                    <hr />
                                    <div class="">
                                        <h5 class="my-0">連結名稱：{{$about->linkName}}(<a href="{{$about->link}}">連結</a>)
                                        </h5>
                                    </div>
                                    <hr />
                                    <div class="">
                                        <h5 class="my-0">內容：</h5>
                                        {!! $about->content !!}
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
        {{--<div class="my-2">
            {{ $articles->links('my-pagination') }}
    </div> --}}
</div>
</div>

<div id="imgModal" style="z-index: 1200" class="modal">
    <span class="close">&times;</span>
    <img class="modalContent" id="imgbox">
    <div id="caption"></div>
</div>
@endsection

@section('script')
<script>
    let modal = document.getElementById("imgModal");
    let modalImg = document.getElementById("imgbox");
    const img = document.getElementsByTagName("img");

    // for (var a = 0; a < img.length; a++) {
    //     img[a].addEventListener("click", function (el) {
    //         modal.style.display = "block";
    //         modalImg.src = this.getAttribute('src');
    //     }, false)
    // }

    // function changeImage(el) {
    //     el = el.target;
    //     el.setAttribute("src", "someimage");
    // }
    // var span = document.getElementsByClassName("close")[0];
    // span.onclick = function () {
    //     modal.style.display = "none";
    //     modalImg.src = ''
    // }
    // modal.onclick = function () {
    //     modal.style.display = "none";
    //     modalImg.src = ''
    // }




    // $('#cateSelect').change(function () {
    //     // console.log($('#sizeSelect').val());
    //     location.href = $('#cateSelect').val()
    // })

</script>
@endsection
