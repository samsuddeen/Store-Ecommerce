@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Topcategories')
@section('content')

<!-- START PAGE CONTENT-->
<div class="page-heading">
    <h1 class="page-title">Sliders</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="index.html"><i class="la la-home font-20"></i></a>
        </li>
    </ol>
</div>
<div class="page-content fade-in-up">
    <div class="ibox">
        <div class="ibox-head">
            <div class="ibox-title">
                <?php if (isset($data)) {
                    echo "Edit Top Category";
                } else {
                    echo "Add Top Category";
                } ?>
            </div>
            <div class="ibox-tools">
                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
            </div>
        </div>
        <div class="ibox-body">
            {{-- @include('admin.includes.error') --}}
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Details</button>
                </li>
            </ul>
            <?php
            if (isset($data)) {
                $action = route('top-category.update' , $data->id);
                $button = 'Update';
                $method = 'post';
            } else {
                $action = route('top-category.store');
                $button = 'Add';
                $method = 'post';
            } ?>
            {{-- @dd($method) --}}
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{$error}}</div>
                @endforeach
            @endif
            <form action="{{$action}}" id="upload_form" class="form-horizontal" method="{{$method}}" novalidate="novalidate" enctype="multipart/form-data">
                @csrf
                <div class="tab-content">

                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Category</label>
                                    {{ Form::select('category_id',@$category->pluck('title','id'),@$data->category_id,['class'=>'form-group form-control ','required'=>true,'placeholder'=>'Select Category Here...'])}}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Select Status</label>
                                    {{ Form::select('status',[1=>'Active',0=>'In-Active'],@$data->status,['class'=>'form-group form-control ','required'=>true,'placeholder'=>'Select Status Here...'])}}
                                </div>
                                
                            </div>
                           
                        </div>

                    </div>

                   
                    </div><br>

                <button class="btn btn-info" type="submit">Submit</button>
                <a class="btn btn-warning" href="{{ route('top-categories') }}">Cancel</a>

            </form>
        </div>

    </div>
</div>
<!-- END PAGE CONTENT-->
@endsection
@push('script')


<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

<script>
    CKEDITOR.replace( 'editor1' ,{
        filebrowserBrowseUrl : 'vendor/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserUploadUrl : 'vendor/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
        filebrowserImageBrowseUrl : 'vendor/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr='
    });
</script>

<!-- FOR FORM VALIDATION -->
<script type="text/javascript">
    $("#form-sample-1").validate({
        rules: {
            name: {
                minlength: 2,
                required: !0
            },
            email: {
                required: !0,
                email: !0
            },
            url: {
                required: !0,
                url: !0
            },
            number: {
                required: !0,
                number: !0
            },
            min: {
                required: !0,
                minlength: 3
            },
            max: {
                required: !0,
                maxlength: 4
            },
            // password: {
                //     required: !0
                // },
                // password_confirmation: {
                    //     required: !0,
                    //     equalTo: "#password"
                    // }
                },
                errorClass: "help-block error",
                highlight: function(e) {
                    $(e).closest(".form-group.row").addClass("has-error")
                },
                unhighlight: function(e) {
                    $(e).closest(".form-group.row").removeClass("has-error")
                },
    });
</script>

@include('admin.includes.ckeditor')
<script>
    function preview() {
        frame.src=URL.createObjectURL(event.target.files[0]);
    }
</script>

<script>
    $('#lfm11').filemanager('image');
</script>
@endpush
