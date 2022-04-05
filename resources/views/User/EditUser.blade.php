@extends('dashboard_layout')

@section('head')
<title>編輯會員</title>
@endsection

@section('body')
<div class="d-flex align-items-center">
    <div class="breadcrumb-title pe-3">會員系統</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">會員編輯</li>
            </ol>
        </nav>
    </div>
</div>

<div class=" my-3 bg-white p-2">
    <div class="col-xl-10 mx-auto">
        <div class="card shadow-none">
            <div class="card-body p-5">
                <form class="row g-3" method="POST" action="{{route('UpdateUser')}}">
                    @csrf
                    <div class="col-12">
                        <label for="title" class="form-label">會員姓名</label>
                        <input type="text" value="{{$User->name}}" disabled name="title" class="form-control" required
                            id="title" placeholder="會員姓名">
                    </div>


                    <div class="col-12">
                        <label for="url" class="form-label">會員電話</label>
                        <input type="text" name="url" disabled class="form-control" value="{{$User->phone}}" required
                            id="url" placeholder="會員電話">
                    </div>

                    <div class="col-12">
                        <label for="email" class="form-label">會員信箱</label>
                        <input type="text" name="email" class="form-control" value="{{$User->email}}" id="url"
                            placeholder="會員信箱">
                    </div>


                    <div class="col-12">
                        <label for="country" class="form-label">會員地址</label>
                        <select id="country" class="form-select my-1" value="" value="{{$User->country}}" name="country">
                        </select>
                        <select id="area" class="form-select my-1" value="" name="area" value="{{$User->area}}">
                        </select>
                        <input type="text" name="address" class="form-control" id="address" value="{{$User->address}}"
                            placeholder="地址">

                    </div>

                    @if ($errors->any())
                    @foreach ($errors->all() as $error)
                    <h6 class="text-danger text-center">{{$error}}</h6>
                    @endforeach
                    @endif
                    <input type="text" hidden name="oldcountry" value="{{$User->country}}">
                    <input type="text" hidden name="oldArea" value="{{$User->area}}">
                    <input type="text" hidden name="editId" value="{{$User->id}}">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-warning px-5">確認更新</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    let taianData = null;
    $.getJSON("{{asset('assets/js/taiwan_districts.json')}}", function (data) {
        console.log(data);
        taianData = data
        // let country =
        let htmlTemplate = ``;
        for (let i = 0; i < data.length; i++) {
            htmlTemplate += `
                <option value="${data[i].name}">${data[i].name}</option>
            `
        }
        $('#country').html(htmlTemplate)
        let areaTemplate = ``;
        for (let j = 0; j < data[0].districts.length; j++) {
            areaTemplate += `<option>${data[0].districts[j].name}</option>`
        }
        $('#area').html(areaTemplate)
        // console.log($('input[name="oldcountry"]').val(), $('input[name="oldArea"]').val());

        if ($('input[name="oldcountry"]').val()) {
            $('#country').val($('input[name="oldcountry"]').val())
            UpdateArea()
        }
        if ($('input[name="oldArea"]').val()) {
            console.log($('input[name="oldArea"]').val());
            $('#area').val($('input[name="oldArea"]').val())
        }
    })
    const UpdateArea = () => {
        let htmlTemplate = ``;
        for (let i = 0; i < taianData.length; i++) {
            if (taianData[i].name == $('#country').val()) {
                for (let j = 0; j < taianData[i].districts.length; j++) {
                    htmlTemplate += `<option value="${taianData[i].districts[j].name}">${taianData[i].districts[j].name}
                    </option>`
                }
                break;
            }

        }
        $('#area').html(htmlTemplate)
    }


    $('#country').change(function () {
        console.log('taianData', taianData);
        console.log($('#country').val());
        let htmlTemplate = ``;
        for (let i = 0; i < taianData.length; i++) {
            if (taianData[i].name == $('#country').val()) {
                for (let j = 0; j < taianData[i].districts.length; j++) {
                    htmlTemplate += `<option value="${taianData[i].districts[j].name}">${taianData[i].districts[j].name}
                    </option>`
                }
                break;
            }

        }
        $('#area').html(htmlTemplate)
    })

</script>
@endsection
