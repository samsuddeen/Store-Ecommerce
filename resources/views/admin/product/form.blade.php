@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')
@section('content')
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
    <section id="default-breadcrumb">
        <section id="multiple-column-form">
            @if ($product->id)
                <form action="{{ route('product.update', $product->id) }}" class="form" enctype="multipart/form-data"
                    method="POST">
                    @method('PATCH')
                @else
                    <form action="{{ route('product.store') }}" class="form" enctype="multipart/form-data" method="POST" id="productStoreForm">
            @endif
            @csrf

            <div class="row dashproduct-form">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                        data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                        aria-selected="true">Product Info</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                        type="button" role="tab" aria-controls="seo" aria-selected="false">SEO
                                        Info</button>
                                </li>
                            </ul>
                        </div>


                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="general" role="tabpanel"
                                    aria-labelledby="general-tab">
                                    <div class="row" id="nothing">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <inputelement name="name" placeholder="Enter Product Name...."
                                                        value="{{ old('name', $product->name) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}" required="true">
                                                    </inputelement>
                                                    <div id="autosearchData">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-1">
                                                        <label class="form-label">Brand</label>
                                                        <small class="text-danger">*</small>
                                                        <select class="form-control form-control-sm select2"
                                                            name="brand_id" id="brand_id" required>
                                                            <option value="">Please Select Brand</option>
                                                            @foreach ($brands as $brand)
                                                                <option value="{{ $brand->id }}" {{old('brand_id') == $brand->id ? 'selected' : ''}}>{{ $brand->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <a href="" class="modaladdnew-form" id="" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalbrand">Add New</a>
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                <div class="mb-1">
                                                    <label class="form-label">Tags <span class="text text-danger">*</span></label>
                                                    <select name="tags[]" id="tags_dd"
                                                        class="form-control form-control-sm select2" multiple="multiple" required>
                                                        @foreach ($tags as $tag)
                                                            <option value="{{ $tag->id }}"
                                                                {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                                                                {{ $tag->title }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <a href="" class="modaladdnew-form" data-bs-toggle="modal"
                                                    data-bs-target="#addtag">Add New</a>
                                                </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            
                                        </div>
                                        <div class="col-md-6" hidden>
                                            <div class="mb-1 dash-radio">
                                                <label class="form-label" for="name"><strong>VAT</strong>:</label>
                                                <input type="radio" value="1" name="vat_percent" checked>Included
                                                 {{-- <input type="radio" value="0" name="vat_percent">Excluded
                                                 <input type="radio" value="3" name="vat_percent">None --}}


                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="min_order">Min Order (Qty)</label>
                                                        <small class="text-danger">*</small>
                                                        <input name="min_order" id="min_order" class="form-control form-control-sm" placeholder="15" type="number"  value="{{ old('min_order', $product->min_order) }}" required>
                                                
                                                        @error('min_order')
                                                            {{$message}}
                                                        @enderror
                                                
                                                    </div>
                                                    {{-- <inputelement name="min_order" placeholder="15" type="number"
                                                        value="{{ old('min_order', $product->min_order) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}" required="false">
                                                    </inputelement> --}}
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="returnable_time">Returnable Time (Days.)</label>
                                                        <input name="returnable_time" id="returnable_time" class="form-control form-control-sm" placeholder="10" type="number"  value="{{ old('returnable_time', $product->returnable_time) }}">
                                                
                                                        @error('returnable_time')
                                                            {{$message}}
                                                        @enderror
                                                
                                                    </div>
                                                    {{-- <inputelement name="returnable_time" type="number"
                                                        placeholder="10 days after purchase"
                                                        value="{{ old('returnable_time', $product->returnable_time) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}" required="false">
                                                    </inputelement> --}}
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="delivery_time">Delivery Time (Days.)</label>
                                                        <input name="delivery_time" id="delivery_time" class="form-control form-control-sm" placeholder="maximum 15 days all over nepal" type="number"  value="{{ old('delivery_time', $product->delivery_time) }}">
                                                
                                                        @error('delivery_time')
                                                            {{$message}}
                                                        @enderror
                                                
                                                    </div>
                                                    {{-- <inputelement name="delivery_time" type="number"
                                                        placeholder="maximum 15 days all over nepal"
                                                        value="{{ old('delivery_time', $product->delivery_time) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}" required="false">
                                                    </inputelement> --}}
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <ul class="ploicies-radio">        
                                                            <li> <input type="radio" name="policy_data" value="0" id="policy" checked>Standard</li>  
                                                            <li><input type="radio" name="policy_data" value="1" id="policy">Return Policy</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                
                                            </div>
                                            <div class="col-md-12 hiddepolicy" hidden>
                                                <div class="mb-1">
                                                    <label class="form-label">Return Policy:</label>
                                                    <textarea name="return_policy" class="form-control editor" rows="3">{{ old('return_policy') }}</textarea>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-12">
                                                <label for="city">City <span class="text text-danger">*</span></label>
                                                <select name="city_id[]" id="city_id" class="form-control select2" multiple required>
                                                    @foreach ($cities as $city)
                                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->city_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div> --}}
                                        </div>

                                            <div class="product-imageform">
                                                <Productform class='row'></Productform>
                                                <small>{{ $errors->first('categories') }}</small>
                                            </div>
                                    </div>
                                   
                                </div>
                                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <inputelement name="meta_title" placeholder="Macbook Pro 2019"
                                                    value="{{ old('meta_title', @$product->meta_title) }}"
                                                    :errors="{{ json_encode($errors->toArray()) }}">
                                                </inputelement>
                                            </div>
                                            <div class="col-md-6 formimage-uploads">
                                                <x-filemanager :value="@$product->og_image" :name="'og_image'">
                                                </x-filemanager>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Meta
                                                        Keywords:</label><small class="text-danger">*</small>
                                                    <textarea class="form-control" rows="4" name="meta_keywords">Glass pipes , Smoking accessories , Nepali glassware , Handcrafted pipes , Unique smoking products , Online ,smoke shop , Premium smoking gear , Tobacco pipes , Himalayan glass pipes , Nepali craftsmanship</textarea>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Meta
                                                        Description:</label><small class="text-danger">*</small>
                                                    <textarea class="form-control" rows="4" name="meta_description">Explore a wide selection of intricately crafted glass pipes and accessories at Glass Pipe Nepal. Discover unique designs and high-quality materials perfect for any smoking enthusiast. Shop now for premium smoking experiences</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-group mt-1">
                                <button type="submit"
                                    class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                                <button type="reset"
                                    class="btn btn-secondary waves-effect waves-float waves-light">Reset</button>
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
                            <label class="input-group-btn form-control form-control-sm">Image
                                <a id="lfm1alt" data-input="thumbnail1" data-preview="holder" class="btn btn-primary">
                                    <i class="fa fa-picture-o"></i> Choose <span class="text-black">size (600 *
                                        600)</span>
                                </a>
                                <input id="thumbnail1" class="form-control" name="logo" 
                                    type="text">
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
    <div class="modal fade addTagModal" id="addtag" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <input type="text" id="tag_title" class="form-control form-control-sm"
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
                            <label for="inputPassword6" class="col-form-label" id="status">Status</label>
                        </div>
                        <div class="col-auto">
                            {{ Form::select('publishStatus', [1 => 'Active', 0 => 'In-Active'], [], ['class' => 'form-control form-control-sm select2 form-select', 'required' => true, 'placeholder' => '------------Select Any One-----------']) }}
                            <p class="form-control-static text-danger publishStatus" id=""></p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" >Reset</button>
                        <button type="button" class="btn btn-primary addTagDirect">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('style')
<style>
    .error{
        color: red !important;
    }
</style>
    {{-- <link rel="stylesheet" href="{{ asset('dashboard/vendors/css/file-uploaders/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/plugins/forms/form-file-uploader.css') }}"> --}}
@endpush
@push('script')
    <script src="{{ asset('frontend/js/sweetalert.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js" integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js" integrity="sha512-6S5LYNn3ZJCIm0f9L6BCerqFlQ4f5MwNKq+EthDXabtaJvg3TuFLhpno9pcm+5Ynm6jdA9xfpQoMz2fcjVMk9g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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
         var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
            };
            CKEDITOR.replace('return_policy', options);
    </script>
    {{-- <script src="{{ asset('dashboard/vendors/js/file-uploaders/dropzone.min.js') }}"></script> --}}
    <script>
        $(document).ready(function(){
            $('#productStoreForm').validate();
        });
    </script>

    <script>
        $('#lfm1alt').filemanager('image');
        $('#lfm1altagimage').filemanager('image');
        $('#lfm1alttagmobileimage').filemanager('image');
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

                    if(response.data !== null){
                    var option = $('<option>', {
                    value: response.data.id,
                    text: response.data.name
                    });
                $('#brand_id').append(option);
                }
                    $('.name').text('');
                    $('.image').text('');
                    $('.status').text('');
                    $('#exampleModalbrand').modal('hide');

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

                if(response.data !== null){
                    var option = $('<option>', {
                    value: response.data.id,
                    text: response.data.title
                    });
                $('#tags_dd').append(option);
                }

                
                $('.title').text('');
                $('.image').text('');
                $('.alt_img').text('');
                $('.publishStatus').text('');

                $('#addtag').modal('hide');
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
