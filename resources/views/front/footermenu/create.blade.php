@section('title')

    Footer Menu | {{ env('APP_NAME') }}

@stop
@extends('layouts.app')
@section('blog','active')
{{-- @section('create_blog','active') --}}
@section('content')

    <div class="page-heading">
        <h1 class="page-title">Footer Menu</h1>
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
                    <?php if (isset($selectCategory)) {
                        echo 'Edit Product Category';
                    } else {
                        echo 'Add Footer Menu';
                    } ?>
                </div>
                <div class="ibox-tools">
                    <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                    <a class="fullscreen-link"><i class="fa fa-expand"></i></a>
                </div>
            </div>
            <div class="ibox-body">
                {{-- @include('admin.layouts.error') --}}

                <ul class="nav nav-tabs tabs-line">
                    <li class="nav-item">
                        <a class="nav-link active" href="#tab-7-1" data-toggle="tab"><i class="fa fa-line-chart"></i>
                            Details</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="#tab-7-2" data-toggle="tab"><i class="fa fa-heartbeat"></i> Meta</a>
                    </li> --}}
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="tab-7-1">


                        <div class="row">
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('footermenu.store') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="title"> Title </label>
                                        <input type="text" rows="5" name="title" class="form-control"
                                            class="@error('title') is-valid @enderror" placeholder="Enter  title" />


                                    </div>
                                    <div class="row">
                                        <div class="col-sm-2 form-group">
                                            <label class="product-form-label">Status</label>
                                        </div>

                                  <div class="col-sm-10 form-group">

                                            <div class="check-list">

                                                <label class="ui-radio ui-radio-primary">
                                                    <input type="radio" name="status" value="0" <?php echo (isset($datas->status) ? ((isset($datas->status) && ($datas->status == 0)) ? 'checked="checked"' : '') : 'checked="checked"'); ?>>
                                                    <span class="input-span"></span>
                                                    Banned
                                                </label>
                                                <label class="ui-radio ui-radio-primary">
                                                    <input type="radio" name="status" value="1" <?php echo (isset($datas->status) ? ((isset($datas->status) && ($datas->status == 1)) ? 'checked="checked"' : '') : 'checked="checked"'); ?>>
                                                    <span class="input-span"></span>
                                                    Active
                                                </label>

                                            </div>
                                        </div>
                                        </div>
                                        <button type="submit" class="btn btn-info">Add Footer Menu</button>
                                        <a class="btn btn-warning" href="{{ url('footermenu/') }}">Cancel</a>
                                        </form>
                            </div>
                        </div><br>



                    @stop

                    @section('footer')

                        <!-- FOR AUTO SLUG GENERATION -->
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $("#category_name").keyup(function() {
                                    var Text = $(this).val();
                                    Text = Text.toLowerCase();
                                    Text = Text.replace(/[^\w ]+/g, ''); //removes non-alphanumeric
                                    Text = Text.replace(/ +/g, '-'); // replaces space with hyphen
                                    $("#category_slug").val(Text);
                                });
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

                        <script>
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                        </script>

                        <!-- Delete(Hide) image -->
                        <!-- <script>
                            $(document).ready(function() {
                                $('a#delete').click(function() {
                                    $('img#category-image').hide();
                                    $('a#delete').hide();
                                })
                            });
                        </script> -->

                        <script src="https://cdn.ckeditor.com/ckeditor5/30.0.0/classic/ckeditor.js"></script>
                        <script src="https://code.jquery.com/jquery-3.6.1.slim.min.js" integrity="sha256-w8CvhFs7iHNVUtnSP0YKEg00p9Ih13rlL9zGqvLdePA=" crossorigin="anonymous"></script>
                        <script>
                            ClassicEditor
                                .create(document.querySelector('#description'))
                                .catch(error => {
                                    console.error(error);
                                });
                        </script>
                    @stop
