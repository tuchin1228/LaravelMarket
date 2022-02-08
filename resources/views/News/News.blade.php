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
    <div>
        @foreach ($articles as $article)
        <article class="py-2 border-bottom d-flex align-items-start">
            <img style="max-width:200px;" onclick="LargeImage('{{$article->id}}')" class="img{{$article->id}} me-2"
                src="./storage/news/{{$article->article_id}}/{{$article->banner}}" alt="">
            <div class="article_content">
                <h2 class="fs-5 fw-bolder">{{$article->title}}</h2>
                <p class="text-secondary" style=" text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;-webkit-box-orient: vertical;
                overflow: hidden; "> {!! strip_tags($article->content,'') !!}</p>
            </div>
        </article>
        @endforeach
        <div class="my-2">
            {{ $articles->links('my-pagination') }}
        </div>
    </div>
</div>

<div id="imgModal" class="modal">
    <span class="close">&times;</span>
    <img class="modalContent" id="imgbox">
    <div id="caption"></div>
</div>
@endsection

@section('script')
<script>
    let modal = document.getElementById("imgModal");
    let modalImg = document.getElementById("imgbox");

    function LargeImage(id) {
        // let target = document.getElementsByClassName("img" + id);
        // console.log(target.getAttribute('src'));
        let atti = $('.img' + id).attr('src');
        console.log(atti);
        modal.style.display = "block";
        modalImg.src = atti;
    }

    $('.close').on('click', function () {
        modal.style.display = "none";
        modalImg.src = ''
    })
    $('.modal').on('click', function () {
        modal.style.display = "none";
        modalImg.src = ''
    })

</script>
@endsection
