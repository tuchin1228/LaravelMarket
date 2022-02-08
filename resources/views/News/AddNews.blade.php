@extends('dashboard_layout')

@section('head')
<title>發布最新消息</title>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">最新消息</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">新增最新消息</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="">
        <form class="w-3/5 mx-auto">
            @csrf
            <input type="text" name="title" class="form-control my-2" required id="title" placeholder="標題">
            <textarea id="mytextarea" name="content"></textarea>

            <div class="my-3">
                <label for="formFile" class="form-label">封面圖上傳</label>
                <input class="form-control" name="formFile" type="file" id="formFile">
            </div>
        </form>

    </div>
</div>
@endsection


@section('script')

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
                article_id: article_id,
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
