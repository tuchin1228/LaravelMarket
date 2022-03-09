@extends('dashboard_layout')

@section('head')
<title>編輯品牌介紹</title>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">品牌介紹</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{route('About')}}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">編輯品牌介紹</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="">
        {{-- {!! Form::open(['route' => ['UploadNews'],'method' => 'post','enctype'=>'multipart/form-data','files' =>
        true])
        !!} --}}
        <form class="w-3/5 mx-auto" method="POST" action="{{route('UpdateAbout')}}" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="formFile" class="form-label mb-0 mt-2">標題</label><br>

                <input type="text" name="title" class="form-control my-2"
                    value="{{old('title') ? old('title') : $about->title}}" required id="title" placeholder="標題">
            </div>
            <div>
                <label for="formFile" class="form-label mb-0 mt-2">首頁標題</label><br>

                <input type="text" name="subtitle" class="form-control my-2"
                    value="{{old('subtitle') ? old('subtitle') : $about->subtitle}}" required id="subtitle"
                    placeholder="首頁標題">
            </div>
            <div>
                <label for="formFile" class="form-label mb-0 mt-2">首頁簡介</label><br>
                <input id="intro" class="form-control my-2" name="intro"
                    value="{{old('intro') ? old('intro') : $about->intro}}" />

            </div>
            <div>
                <label for="formFile" class="form-label mb-0 mt-3">內容</label><br>
                <textarea id="mytextarea"
                    name="content">{{old('content') ? old('content') :  $about->content}}</textarea>

            </div>
            <div>
                <label for="formFile" class="form-label mb-0 mt-2">連結名稱</label><br>

                <input type="text" name="linkName" class="form-control my-2"
                    value="{{old('linkName') ? old('linkName') : $about->linkName}}" required id="linkName"
                    placeholder="連結名稱">
            </div>
            <div>
                <label for="formFile" class="form-label mb-0 mt-2">連結</label><br>

                <input type="text" name="link" class="form-control my-2"
                    value="{{old('link') ? old('link') : $about->link}}" required id="link" placeholder="連結名稱">
            </div>
            <div>
                <label for="formFile" class="form-label mb-0 mt-2">排序</label><br>

                <input type="text" name="sort" class="form-control my-2"
                    value="{{old('sort') ? old('sort') : $about->sort}}" required id="sort" placeholder="排序">
            </div>
            <div class="my-3">
                <label for="formFile" class="form-label">圖片上傳</label>
                {{-- {!! Form::file('formFile', {{ old('formFile') }}, ['class'
                =>'form-control','id'=>'formFile','name'=>'formFile','accept'=>'image/*']) !!} --}}
                <input class="form-control" name="formFile" type="file" value="{{old('formFile')}}" id="formFile">

            </div>
            <div class=" my-2">
                <label for="formFile" class="form-label">圖片預覽</label><br>
                <img src="{{$about->filename ? '../../storage/HomeAbout/'.$about->about_id .'/'. $about->filename : '' }}"
                    style="max-width: 200px" id="preview_image" alt="">
            </div>
            <input type="text" hidden name="about_id" value="{{ $about->about_id}}">
            <input type="text" hidden name="filename" value="{{ $about->filename}}">


            <div class="col-12">
                <label for="" class="d-block mt-4">是否啟用</label>
                <select class="form-select  my-2 " name="enable" id="">
                    <option value="1" @if( empty(old('enable')) || (!empty(old('enable')) && old('enable')==1) ||
                        $about->enable == 1)
                        selected @endif>啟用</option>
                    <option value="0" @if(!empty(old('enable') && old('enable')==0) || $about->enable == 0) selected
                        @endif>未啟用</option>
                </select>
            </div>
            <div class="col-12">
                <label for="" class="d-block mt-4">是否顯示於首頁</label>
                <select class="form-select  my-2 " name="showHome" id="">
                    <option value="1" @if( empty(old('showHome')) || (!empty(old('showHome')) && old('showHome')==1) ||
                        $about->showHome == 1)
                        selected @endif>
                        是</option>
                    <option value="0" @if(!empty(old('showHome') && old('showHome')==0) || $about->showHome == 0)
                        selected @endif>否</option>
                </select>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary px-5">確認更新</button>
            </div>
        </form>
        {{-- {!! Form::close() !!} --}}
    </div>
</div>
@endsection


@section('script')
@if ($errors->has('noUploadImage')){

<script>
    alert("{{$errors->first('noUploadImage')}}")

</script>
}
@endif
{{-- <script src="https://cdn.tiny.cloud/1/66eqi5hu6pnmybzh8uiexqi0bgd4ue0n01tro0ax0t0gh8yf/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>

<script>
    const article_id = Math.floor(Date.now() / 1000);
    const timestamp = new Date
    const date =
        `${timestamp.getFullYear()}-${(timestamp.getMonth()+1)>9?timestamp.getMonth()+1:'0'+ (timestamp.getMonth()+1)}-${timestamp.getDate()}`
    console.log(date);
    tinymce.init({
        selector: 'textarea',
        plugins: 'image code',
        min_height: 500,
        document_base_url: "storage/uploads/",
        toolbar: [{
                name: 'history',
                items: ['undo', 'redo']
            },
            {
                name: 'styles',
                items: ['styleselect']
            },
            {
                name: 'formatting',
                items: ['bold', 'italic']
            },
            {
                name: 'alignment',
                items: ['alignleft', 'aligncenter', 'alignright', 'alignjustify']
            },
            {
                name: 'indentation',
                items: ['outdent', 'indent']
            },
            {
                name: 'image',
                items: ['image']
            }
        ],
        // toolbar: 'undo redo | link image | code',
        image_title: true,
        // automatic_uploads: true,
        images_upload_url: `../api/uploadimage/${article_id}/${date}`,
        file_picker_types: 'image',
        file_picker_callback: function (cb, value, meta) {
            console.log('file_picker_callback');
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function () {
                var file = this.files[0];
                console.log('file', file);
                const formData = new FormData();
                formData.append('file', file)
                formData.append('article_id', article_id)
                formData.append('date', date)
                console.log(file);
                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                    console.log('打api');

                };

            };
            input.click();
        }
    });

</script> --}}

<script src="https://cdn.tiny.cloud/1/66eqi5hu6pnmybzh8uiexqi0bgd4ue0n01tro0ax0t0gh8yf/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>

<script>
    const about_id = "{{$about->about_id }}";
    // $("input[name='about_id']").val(about_id)
    // const timestamp = new Date
    // const date =
    //     `${timestamp.getFullYear()}-${(timestamp.getMonth()+1)>9?timestamp.getMonth()+1:'0'+ (timestamp.getMonth()+1)}-${timestamp.getDate()}`
    // console.log(date);

    $("input#formFile").change(function () {

        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#preview_image").attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    tinymce.init({
        selector: 'textarea',
        plugins: 'image code',
        min_height: 500,
        document_base_url: "storage/HomeAbout/",
        toolbar: [{
                name: 'history',
                items: ['undo', 'redo']
            },
            {
                name: 'styles',
                items: ['styleselect']
            },
            {
                name: 'formatting',
                items: ['bold', 'italic']
            },
            {
                name: 'alignment',
                items: ['alignleft', 'aligncenter', 'alignright', 'alignjustify']
            },
            {
                name: 'indentation',
                items: ['outdent', 'indent']
            },
            {
                name: 'image',
                items: ['image']
            }
        ],
        // toolbar: 'undo redo | link image | code',
        image_title: true,
        // automatic_uploads: true,
        images_upload_url: `../api/uploadAboutimage/${about_id}`,
        file_picker_types: 'image',
        file_picker_callback: function (cb, value, meta) {
            console.log('file_picker_callback');
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.onchange = function () {
                var file = this.files[0];
                console.log('file', file);
                const formData = new FormData();
                formData.append('file', file)
                formData.append('about_id', about_id)
                formData.append('date', date)

                var reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = function () {
                    var id = 'blobid' + (new Date()).getTime();
                    var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    var base64 = reader.result.split(',')[1];
                    var blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);
                    cb(blobInfo.blobUri(), {
                        title: file.name
                    });
                    console.log('打api');

                };

            };
            input.click();
        }
    });

    const submitArticle = () => {
        $.ajax({
            type: 'POST',
            url: '',
            data: {
                title: $('input[name="title"]').val(),
                content: tinyMCE.get('mytextarea').getContent(),
                tag: $('input[name="tag"]:checked').val(),
                about_id: about_id,
                account: `{{session()->get('account')}}`,
                token: `{{session()->get('token')}}`,
            },
            success: function (res) {
                console.log(res);
                if (res.success) {
                    alert('成功送出')
                    location.reload()
                }
            }
        })
    }

</script>

@endsection
