@extends('dashboard_layout')

@section('head')
<title>發布最新消息</title>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@endsection

@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">商品管理</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{route('AllProduct')}}"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">編輯商品主檔</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="">
        {{-- {!! Form::open(['route' => ['UploadNews'],'method' => 'post','enctype'=>'multipart/form-data','files' =>
        true])
        !!} --}}
        <form class="w-3/5 mx-auto" method="POST" action="{{route('EditProduct')}}" enctype="multipart/form-data">
            @csrf
            <label for="" class="d-block mt-4">商品名稱</label>
            <input type="text" name="productName" class="form-control my-2" value="{{$product->productName}}" required
                id="productName" placeholder="標題">
            <div class="row">
                <div class="col-6">
                    <label for="" class="d-block mt-4">商品類別</label>
                    <select class="form-select  my-2 " name="productCateId" id="">
                        @foreach ($categories as $category)
                        <option value="{{$category->id}}" @if($product->productCateId == $category->id) selected
                            @endif>{{$category->productCateName}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6">
                    <label for="" class="d-block mt-4">商品標籤</label>
                    <select class="form-select  my-2 " name="productTag" id="">
                        <option value="" selected>無</option>
                        @foreach ($tags as $tag)
                        <option value="{{$tag->id}}" @if($product->productTag == $tag->id) selected
                            @endif>{{$tag->tagName}}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <label for="" class="d-block mt-4">商品簡介</label>
            <textarea name="productIntro" id="productIntro" cols="30" rows="3"
                style=" width: 100%;border: 1px solid #d6d6d6;border-radius: 5px;">{{$product->productIntro}}</textarea>
            {{-- <div class="row">
                <div class="col-4">
                    <label for="" class="d-block mt-4">原價</label>
                    <input type="number" name="originPrice" class="form-control my-2" value="{{old('originPrice')}}"
            required id="originPrice" placeholder="原價">
    </div>
    <div class="col-4">
        <label for="" class="d-block mt-4">特價</label>
        <input type="number" name="salePrice" class="form-control my-2" value="{{old('salePrice')}}" id="salePrice"
            placeholder="特價">
    </div>
    <div class="col-4">
        <label for="" class="d-block mt-4">數量</label>
        <input type="number" name="quantity" class="form-control my-2" value="{{old('quantity')}}" required
            id="quantity" placeholder="數量">
    </div>
    <div class="col-4">
        <label for="" class="d-block mt-4">成本</label>
        <input type="number" name="cost" class="form-control my-2" value="{{old('cost')}}" id="cost" placeholder="成本">
    </div>
</div> --}}
<div class="row">
    <div class="col-6">
        <label for="" class="d-block mt-4">排序</label>
        <input type="number" name="sort" class="form-control my-2" value="{{$product->sort}}" id="sort"
            placeholder="排序">
    </div>
    <div class="col-6">
        <label for="" class="d-block mt-4">是否啟用</label>
        <select class="form-select  my-2 " name="enable" id="">
            <option value="1" @if ($product->enable)
                selected
                @endif>啟用</option>
            <option value="0" @if (!$product->enable)
                selected
                @endif>未啟用</option>
        </select>
    </div>
</div>

<label for="" class="d-block mt-4">商品描述</label>
<textarea id="description" name="description">{!! $product->description !!}</textarea>
<label for="" class="d-block mt-4">商品組成</label>
<textarea id="composition" name="composition">{!! $product->composition !!}</textarea>
<label for="" class="d-block mt-4">訂購流程</label>
<textarea id="buyflow" name="buyflow">{!! $product->buyflow !!}</textarea>
<input type="text" hidden value="{{$product->productId}}" name="productId">

<div class="col-12 my-3 text-center">
    <button type="submit" class="btn btn-warning px-5">更新</button>
</div>
</form>
{{-- {!! Form::close() !!} --}}
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
    const productId = '{{$product->productId}}';
    $("input[name='productId']").val(productId)

    const timestamp = new Date
    const date =
        `${timestamp.getFullYear()}-${(timestamp.getMonth()+1)>9?timestamp.getMonth()+1:'0'+ (timestamp.getMonth()+1)}-${timestamp.getDate()}`
    console.log(date);

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
        selector: 'textarea#description',
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
        images_upload_url: `../../api/uploadProductimage/${productId}/description`,
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
                formData.append('productId', productId)
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

    tinymce.init({
        selector: 'textarea#composition',
        plugins: 'image code',
        min_height: 500,
        document_base_url: "storage/product/",
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
        images_upload_url: `../api/uploadProductimage/${productId}/composition`,
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
                formData.append('productId', productId)
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

    tinymce.init({
        selector: 'textarea#buyflow',
        plugins: 'image code',
        min_height: 500,
        document_base_url: "storage/product/",
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
        images_upload_url: `../api/uploadProductimage/${productId}/buyflow`,
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
                formData.append('productId', productId)
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
