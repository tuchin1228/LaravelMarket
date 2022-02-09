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
    <div class="breadcrumb-title pe-3">會員系統</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">會員管理</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="d-flex justify-content-end align-items-center border-bottom py-1">
        <a href="{{route('AddNews')}}" class="btn btn-primary">新增會員</a>
    </div>
    <div>
        @foreach ($Users as $User)

        @endforeach
        <div class="my-2">
            {{ $Users->links('my-pagination') }}
        </div>
    </div>
</div>
@endsection

@section('script')
<script>


</script>
@endsection
