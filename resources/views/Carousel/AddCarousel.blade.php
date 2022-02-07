@extends('dashboard_layout')
@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">輪播設定</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{route('Carousel')}}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">新增輪播</li>
            </ol>
        </nav>
    </div>
</div>
<div class=" my-3 bg-white p-2">



    {{-- <form class="row g-3">
        @csrf
        <div class="col-md-6">
            <label for="title" class="form-label">標題</label>
            <input type="email" class="form-control" id="title">
        </div>
        <div class="col-md-6">
            <label for="inputLastName" class="form-label">Last Name</label>
            <input type="password" class="form-control" id="inputLastName">
        </div>
        <div class="col-md-6">
            <label for="inputEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="inputEmail">
        </div>

    </form> --}}
    <div class="col-xl-10 mx-auto">
        <div class="card shadow-none">
            <div class="card-body p-5">

                <form class="row g-3" method="POST" action="{{route('UploadCarousel')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-12">
                        <label for="title" class="form-label">標題</label>
                        <input type="text" value="{{old('title')}}" name="title" class="form-control" required
                            id="title" placeholder="標題">
                    </div>


                    <div class="col-12">
                        <label for="url" class="form-label">連結</label>
                        <input type="text" name="url" class="form-control" value="{{old('url')}}" required id="url"
                            placeholder="標題">
                    </div>

                    <div class="col-12">
                        <label for="sort" class="form-label">排序值(數字越大越前面)</label>
                        <input type="text" name="sort" class="form-control" value="{{old('sort')}}" id="url"
                            placeholder="標題">
                    </div>


                    <div class="col-12">
                        <label for="size" class="form-label">尺寸</label>
                        <select id="size" class="form-select" value="{{old('size')}}" name="size">
                            <option value="0">桌機</option>
                            <option value="1">手機</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="formFile" class="form-label">圖片上傳</label>
                        <input class="form-control" name="formFile" type="file" value="{{old('formFile')}}"
                            id="formFile">
                    </div>
                    <div class="text-center">
                        <img src="" style="max-width: 500px" id="preview_image" alt="">
                    </div>

                    <div class="mb-3 d-flex align-items-center">
                        <label for="formFile" class="form-label  me-2">是否啟用</label>
                        <div class="form-check-danger form-check form-switch mx-2 mb-1">
                            <input class="form-check-input" type="checkbox" value="1" name="enable" id="enable" checked>
                            <span class="form-check-label enable" for="enable">啟用</span>
                        </div>
                    </div>
                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <h6 class="text-danger text-center">{{$error}}</h6>
                    @endforeach
                    @endif

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-5">確認新增</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $("input#formFile").change(function () {

        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#preview_image").attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });


    $('input#enable').change(function () {
        // console.log($('input#enable').val());
        if ($('input#enable').is(":checked")) {
            // $('label.enable').val('啟用')

            $('span.enable').html('啟用')
            // console.log($('label.enable'));
            // console.log('啟用');
        } else {
            // console.log($('label.enable'));

            // $('label.enable').val('未啟用')
            $('span.enable').html('未啟用')

            // console.log('未啟用');


        }
    })
    // function readURL(action, input) {
    //     console.log(input);
    //     console.log("#" + action + "_Preview_Img");
    //     if (input.files && input.files[0]) {
    //         var reader = new FileReader();
    //         reader.onload = function (e) {
    //             $("#" + action + "_Preview_Img").attr('src', e.target.result);
    //         }
    //         reader.readAsDataURL(input.files[0]);
    //     }
    // }

</script>
@endsection
