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
        <select id="cateSelect" class="form-select mx-2 w-auto " aria-label="Default select example">
            <option value="all" @if ($cateId=='all' ) selected @endif>全部</option>
            @foreach ($category as $cate)
            <option value="{{ route('CategoryOfNews',['cateId'=>$cate->id]) }}" @if ($cateId==$cate->id ) selected
                @endif>
                {{$cate->articles_cate_title}}
            </option>
            @endforeach
            {{-- <option value="{{ route('CategoryOfNews',['cateId'=>'desktop']) }}" @if ($type=='desktop' ) selected
            @endif>
            桌機
            </option>
            <option value="{{ route('CategoryOfNews',['cateId'=>'phone']) }}" @if ($type=='phone' ) selected @endif>手機
            </option> --}}
        </select>
        <a href="{{route('AddNews')}}" class="btn btn-primary">新增消息</a>
    </div>
    <div>
        @foreach ($articles as $article)
        <article class="py-2 border-bottom d-flex align-items-start">
            <img style="max-width:200px;" onclick="LargeImage('{{$article->article_id}}')"
                class="img{{$article->article_id}} me-2"
                src="./storage/news/{{$article->article_id}}/{{$article->banner}}" alt="">
            <div class="article_content" style="width: 100%">
                <div>
                    <h2 class="fs-5 fw-bolder">【{{$article->articles_cate_title}}】{{$article->title}}</h2>
                    <p class="text-secondary my-1">建立日期：{{$article->created_at}}</p>
                    <p class="text-secondary" style=" text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;-webkit-box-orient: vertical;
                overflow: hidden; "> {!! strip_tags($article->content,'') !!}</p>
                </div>
                <div class="text-end">
                    <button class="btn btn-success" type="button" data-bs-toggle="modal"
                        data-bs-target="#articleModal{{$article->article_id}}">查看</button>
                    <a href="{{route('EditNews',['article_id'=>$article->article_id])}}" class="btn btn-warning"
                        type="button">編輯</a>
                    <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                        data-bs-target="#deleteModal{{$article->article_id}}">刪除</button>
                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal{{$article->article_id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">確認刪除</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">刪除後將無法復原
                                </div>
                                <form action="{{route('DeleteNews')}}" method="POST">
                                    @csrf
                                    <input type="text" hidden name="banner" value="{{$article->banner}}">
                                    <input type="text" hidden name="deleteId" value="{{$article->article_id}}">
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">取消</button>
                                        <button type="submit" class="btn btn-danger">刪除</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="articleModal{{$article->article_id}}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title fw-bold">{{$article->title}}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-start">
                                    {!! $article->content !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
        @endforeach
        <div class="my-2">
            {{ $articles->links('my-pagination') }}
        </div>
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

    // function LargeImage(article_id) {
    //     // let target = document.getElementsByClassName("img" + article_id);
    //     // console.log(target.getAttribute('src'));
    //     let atti = $('.img' + article_id).attr('src');
    //     console.log(atti);
    //     modal.style.display = "block";
    //     modalImg.src = atti;
    // }

    // $('.close').on('click', function () {
    //     modal.style.display = "none";
    //     modalImg.src = ''
    // })
    // $('.modal').on('click', function () {
    //     modal.style.display = "none";
    //     modalImg.src = ''
    // })

    for (var a = 0; a < img.length; a++) {
        img[a].addEventListener("click", function (el) {
            modal.style.display = "block";
            modalImg.src = this.getAttribute('src');
        }, false)
    }

    function changeImage(el) {
        el = el.target;
        el.setAttribute("src", "someimage");
    }
    var span = document.getElementsByClassName("close")[0];
    span.onclick = function () {
        modal.style.display = "none";
        modalImg.src = ''
    }
    modal.onclick = function () {
        modal.style.display = "none";
        modalImg.src = ''
    }

    $('#cateSelect').change(function () {
        // console.log($('#sizeSelect').val());
        location.href = $('#cateSelect').val()
    })

</script>
@endsection
