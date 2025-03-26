@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')
@section('content')
    <section id="default-breadcrumb">
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>
                                {{ $message }}
                            </li>
                        @endforeach
                    </ul>

                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Information</h4>
                        </div>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Basic
                                    Information</button>
                            </li>

                            <li class="nav-item" role="presentation">
                                {{-- <button class="nav-link" id="#" type="button" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal" role="tab" aria-controls="profile"
                                    aria-selected="false">Category</button> --}}
                            </li>

                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Attributes</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="price-tab" data-bs-toggle="tab" data-bs-target="#price"
                                    type="button" role="tab" aria-controls="price" aria-selected="false">Price and
                                    Stock</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="service-tab" data-bs-toggle="tab" data-bs-target="#service"
                                    type="button" role="tab" aria-controls="service" aria-selected="false">Service and
                                    Delivery</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                    type="button" role="tab" aria-controls="seo" aria-selected="false">SEO</button>
                            </li>
                        </ul>
                        <div class="ms-1 me-1">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    @include('admin.product.edit.basic')
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    @include('admin.product.edit.attribute')
                                </div>
                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                    @include('admin.product.edit.description')
                                </div>
                                <div class="tab-pane fade" id="price" role="tabpanel" aria-labelledby="contact-tab">
                                    @include('admin.product.edit.price-stock')
                                </div>
                                <div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="contact-tab">
                                    @include('admin.product.edit.service-delivery')
                                </div>

                                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                    @include('admin.product.edit.seo-info')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </section>
    </section>




 <!-- Add Brand -->
 <div class="modal fade modalform-newadd" id="exampleModalbrand" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Brand</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:;" method="post" id="brandForm">
                <div class="modal-body">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Name</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="inputPassword6" class="form-control form-control-sm"
                            name="name" aria-describedby="passwordHelpInline">
                        <p class="form-control-static text-danger name" id=""></p>
                    </div>


                    <div class="col-auto">
                        <label class="input-group-btn form-control form-control-sm">
                           <label for=""> Image</label>
                           <div class="branduploads">
                            <a id="lfm1alt" data-input="thumbnail1" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose <span class="text-black">size (600 *
                                    600)</span>
                            </a>
                            <input id="thumbnail1" class="form-control" name="logo" value="{{ @$tag->alt_img }}"
                                type="text">
                            </div>
                        </label>
                        <p class="form-control-static text-danger image" id=""></p>
                        <img id="holder" style="margin-top:15px;max-height:100px;">
                    </div>

                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Status</label>
                    </div>
                    <div class="col-auto">
                        {{ Form::select('status', [1 => 'Active', 0 => 'In-Active'], [], ['class' => 'form-control form-control-sm select2 form-select', 'required' => true, 'placeholder' => '------------Select Any One-----------']) }}
                        <p class="form-control-static text-danger status" id=""></p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary addBrandDirect">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Tag -->
<div class="modal fade" id="addtag" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="javascript:;" method="post" id="tagForm">
                <div class="modal-body">
                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Title</label>
                    </div>
                    <div class="col-auto">
                        <input type="text" id="inputPassword6" class="form-control form-control-sm"
                            name="title" aria-describedby="passwordHelpInline">
                        <p class="form-control-static text-danger title" id=""></p>
                    </div>


                    <div class="col-auto">
                        <label class="input-group-btn form-control form-control-sm">Mobile View Image
                            <a id="lfm1altagimage" data-input="thumbnail13" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose <span class="text-black">size (600 *
                                    600)</span>
                            </a>
                            <input id="thumbnail13" class="form-control" name="alt_img"
                                type="text">
                        </label>
                        <p class="form-control-static text-danger alt_img" id=""></p>
                        <img id="holder" style="margin-top:15px;max-height:100px;">
                    </div>

                    <div class="col-auto">
                        <label class="input-group-btn form-control form-control-sm">Image
                            <a id="lfm1alttagmobileimage" data-input="thumbnail2" data-preview="holder" class="btn btn-primary">
                                <i class="fa fa-picture-o"></i> Choose <span class="text-black">size (600 *
                                    600)</span>
                            </a>
                            <input id="thumbnail2" class="form-control" name="image"
                                type="text">
                        </label>
                        <p class="form-control-static text-danger image" id=""></p>
                        <img id="holder" style="margin-top:15px;max-height:100px;">
                    </div>

                    <div class="col-auto">
                        <label for="inputPassword6" class="col-form-label">Status</label>
                    </div>
                    <div class="col-auto">
                        {{ Form::select('publishStatus', [1 => 'Active', 0 => 'In-Active'], [], ['class' => 'form-control form-control-sm select2 form-select', 'required' => true, 'placeholder' => '------------Select Any One-----------']) }}
                        <p class="form-control-static text-danger publishStatus" id=""></p>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-secondary" >Reset</button>
                    <button type="submit" class="btn btn-primary addTagDirect">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>
    @include('admin.product.category-edit.category-edit')
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('dashboard/css/plugins/forms/form-file-uploader.css') }}">
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            loadUniSharp();
        });
    </script>
 <script>
    $(document).on('keyup', '#name', function() {
        $('#autosearchData').html('');
        let query = $(this).val();
        if (query.length >= 3) {
            $.ajax({
                url: "{{ route('autoSearchTagAdmin') }}",
                data: {
                    search_query: query,
                },
                type: "get",
                success: function(response) {
                    let html='';
                    html+='<ul>';
                    $.each(response,function(value,index){
                        html+='<li>';
                        html+=index;
                        html+='</li>';
                    });
                    html+='</ul>';
                   $('#autosearchData').html(html);
                }
            });
        }
    });
</script>
<script>
    $(document).on('click', '.addBrandDirect', function() {
        var formData = document.getElementById('brandForm');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "{{ route('add-brand-direct') }}",
            type: "post",
            data: {
                name: formData['name'].value,
                image: formData['logo'].value,
                status: formData['status'].value
            },
            success: function(response) {
                if (response.validate) {
                    $.each(response.errors, function(index, value) {
                        $('.' + index).text(value);
                    });
                    return false;
                }
                if (response.error) {
                    // return false;
                    location.reload();
                }
                $('.name').text('');
                $('.image').text('');
                $('.status').text('');
                location.reload();
            }
        });
    });
</script>

<script>
$(document).on('click', '.addTagDirect', function() {
    var formData = document.getElementById('tagForm');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: "{{ route('add-tag-direct') }}",
        type: "post",
        data: {
            title: formData['title'].value,
            image: formData['image'].value,
            alt_img: formData['alt_img'].value,
            publishStatus: formData['publishStatus'].value
        },
        success: function(response) {
            if (response.validate) {
                $.each(response.errors, function(index, value) {
                    $('.' + index).text(value);
                });
                return false;
            }
            if (response.error) {
                // return false;
                location.reload();
            }
            $('.title').text('');
            $('.image').text('');
            $('.alt_img').text('');
            $('.publishStatus').text('');
            location.reload();
        }
    });
});
</script>

<script>
$(document).on('click','#policy',function()
{
    var data = $("input[name='policy_data']:checked").val();
    if(data=='0')
    {
        $('.hiddepolicy').attr('hidden',true);
    }
    else if(data=='1')
    {
        $('.hiddepolicy').removeAttr('hidden');
    }
})
</script>


@endpush
