@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Slider')
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
                    <?php if (isset($slider)) {
                        echo 'Edit Slider';
                    } else {
                        echo 'Add Slider';
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
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Details</button>
                    </li>
                    {{-- <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">meta</button>
                    </li> --}}
                </ul>
                <?php
                if (isset($slider)) {
                    $action = route('slider.update', $slider->id);
                    $button = 'Update';
                    $method = 'post';
                } else {
                    $action = route('slider.store');
                    $button = 'Add';
                    $method = 'post';
                } ?>
                {{-- @dd($method) --}}
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="alert alert-danger">{{ $error }}</div>
                    @endforeach
                @endif
                <form action="{{ $action }}" id="upload_form" class="form-horizontal" method="{{ $method }}"
                    novalidate="novalidate" enctype="multipart/form-data">
                    @csrf
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Title</label>
                                        <input class="form-control" type="text" name="title" required
                                            placeholder="Enter the title" value="<?php if (isset($slider->title)) {
                                                echo $slider->title;
                                            } else {
                                                echo old('title');
                                            } ?>">
                                            <small class="alert alert-danger">{{ $errors->first('title') }}</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Mobile Show</label>
                                        <input type="checkbox" name="show_mob" value="1" {{ ((@$slider->show_mob==1) ? 'checked':'')}}>
                                    </div>

                                    <div class="form-group">
                                        <label for="description">
                                            Description
                                        </label>
                                        <br>
                                        <textarea name="body" rows="5" cols="70" placeholder=""><?php if (isset($slider->body)) {
                                            echo $slider->body;
                                        } else {
                                            echo old('body');
                                        } ?></textarea>
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label>External Link</label>
                                        <input class="form-control" type="text" name="link"
                                            placeholder="Enter the link" value="<?php if (isset($slider->link)) {
                                                echo $slider->link;
                                            } else {
                                                echo old('link');
                                            } ?>">
                                    </div>
                                    {{-- ---------------------------- --}}
                                    {{-- @dd(@$slider) --}}
                                    <div class="input-group">
                                        <label class="input-group-btn">Image
                                            <a id="lfm11" data-input="thumbnail" data-preview="holder"
                                                class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> Choose <span class="text-black">size (1920 *
                                                    475)</span>
                                            </a>
                                        </label>
                                        <input id="thumbnail" class="form-control" value="{{@$slider->image}}" type="text" name="image" required>

                                    </div>
                                    <img id="holder" style="margin-top:15px;max-height:100px;">

                                    @isset($slider)
                                        <div class="col-md-2">
                                            <img src="{{ asset(@$slider->image ?? '') }}" alt=""
                                                style="width:100%;height:auto">
                                        </div>
                                    @endisset

                                    {{-- ------------------- --}}

                                    <div class="input-group">
                                        <label class="input-group-btn">Mobile View Image
                                            <a id="lfm1alt" data-input="thumbnail1" data-preview="holder"
                                                class="btn btn-primary">
                                                <i class="fa fa-picture-o"></i> Choose <span class="text-black">size (1030 *
                                                    455)</span>
                                            </a>
                                        </label>
                                        <input id="thumbnail1" class="form-control" value="{{@$slider->alt_img}}" type="text" name="alt_img">

                                    </div>
                                    <img id="holder" style="margin-top:15px;max-height:100px;">

                                    @isset($slider)
                                        <div class="col-md-2">
                                            <img src="{{ asset(@$slider->alt_img ?? '') }}" alt=""
                                                style="width:100%;height:auto">
                                        </div>
                                    @endisset


                              

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="check-list">
                                                <label for="">Publish Status</label>
                                                <label class="ui-radio ui-radio-primary">
                                                    <input type="radio" name="publish_status" value="0"
                                                        <?php echo isset($slider->publish_status) ? (isset($slider->publish_status) && $slider->publish_status == 0 ? 'checked="checked"' : '') : 'checked="checked"'; ?>>
                                                    <span class="input-span"></span>
                                                    Banned
                                                </label>
                                                <label class="ui-radio ui-radio-primary">
                                                    <input type="radio" name="publish_status" value="1"
                                                        <?php echo isset($slider->publish_status) && $slider->publish_status == 1 ? 'checked="checked"' : ''; ?>>
                                                    <span class="input-span"></span>
                                                    Active
                                                </label>
                                            </div>

                                        </div>
                                        <div class="col-md-2">
                                            <div class="check-list">
                                                <label for="">Hide Text</label>
                                                <label class="ui-radio ui-radio-primary">
                                                    <input type="radio" name="hide_status" value="0"
                                                        <?php echo isset($slider->hide_status) ? (isset($slider->hide_status) && $slider->hide_status == 0 ? 'checked="checked"' : '') : 'checked="checked"'; ?>>
                                                    <span class="input-span"></span>
                                                    Off
                                                </label>
                                                <label class="ui-radio ui-radio-primary">
                                                    <input type="radio" name="hide_status" value="1"
                                                        <?php echo isset($slider->hide_status) && $slider->hide_status == 1 ? 'checked="checked"' : ''; ?>>
                                                    <span class="input-span"></span>
                                                    On
                                                </label>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="form-group">
                                <label>Meta Title</label>
                                <textarea class="form-control" name="meta_title" rows="4" placeholder="Enter Meta Title"><?php if (isset($slider->meta_title)) {
                                    echo $slider->meta_title;
                                } else {
                                    echo old('meta_title');
                                } ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Meta Keywords</label>
                                <textarea class="form-control" name="meta_keyword" rows="5" placeholder="Enter Meta Keyword"><?php if (isset($slider->meta_keyword)) {
                                    echo $slider->meta_keyword;
                                } else {
                                    echo old('meta_keyword');
                                } ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Meta Description</label>
                                <textarea class="form-control" name="meta_desc" rows="6" placeholder="Enter Meta Description"><?php if (isset($slider->meta_desc)) {
                                    echo $slider->meta_desc;
                                } else {
                                    echo old('meta_desc');
                                } ?></textarea>
                            </div>

                        </div>
                    </div><br>

                    <button class="btn btn-info" type="submit">Submit</button>
                    <a class="btn btn-warning" href="{{ route('slider.index') }}">Cancel</a>

                </form>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
@endsection
@push('script')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

    <script>
        CKEDITOR.replace('editor1', {
            filebrowserBrowseUrl: 'vendor/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
            filebrowserUploadUrl: 'vendor/responsive_filemanager/filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
            filebrowserImageBrowseUrl: 'vendor/responsive_filemanager/filemanager/dialog.php?type=1&editor=ckeditor&fldr='
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
            frame.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>

    <script>
        $('#lfm11').filemanager('image');
        $('#lfm1alt').filemanager('image');
    </script>
@endpush
