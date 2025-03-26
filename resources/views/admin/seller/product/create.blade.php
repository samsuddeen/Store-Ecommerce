@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Product')
@section('content')
<section id="default-breadcrumb">
    <section id="multiple-column-form">
        @if ($product->id)
                <form action="{{ route('seller-product.update', $product->id) }}" class="form" enctype="multipart/form-data"
                    method="POST">
                    @method('PATCH')
                @else
                    <form action="{{ route('seller-product.store') }}" class="form" enctype="multipart/form-data"
                        method="POST">
            @endif
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                        data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                        aria-selected>Product Info</button>
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
                                                    <inputelement name="name" placeholder="Macbook Pro 2019"
                                                        value="{{ old('name', $product->name) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}" required>
                                                    </inputelement>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-1">
                                                        <label class="form-label">Brand</label>
                                                        <small class="text-danger">*</small>
                                                        <select class="form-control form-control-sm select2" name="brand_id">
                                                            <option value="">Please Select Brand</option>
                                                            @foreach ($brands as $brand)
                                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : ''}}>{{ $brand->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-1">
                                                        <label class="form-label">Tags</label>
                                                        <small class="text-danger">*</small>
                                                        <select name="tags[]" id="tags"
                                                            class="form-control form-control-sm select2" multiple="multiple">
                                                            @foreach ($tags as $tag)
                                                                <option value="{{ $tag->id }}"
                                                                    {{ in_array($tag->id, $selectedTags) ? 'selected' : '' }}>
                                                                    {{ $tag->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-6">
                                            <div class="mb-1">
                                                <label class="form-label" for="name">VAT: </label>&nbsp;&nbsp;
                                                 <input type="radio" value="1" name="vat_percent">Included
                                                 <input type="radio" value="0" name="vat_percent">Excluded
                                                 <input type="radio" value="3" name="vat_percent">None

                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <inputelement name="min_order" type="number" placeholder="fifteen(15)"
                                                        value="{{ old('min_order', $product->min_order) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}">
                                                    </inputelement>
                                                </div>
                                                <div class="col-md-4">
                                                    <inputelement name="returnable_time" type="number"
                                                        placeholder="10 days after purchase"
                                                        value="{{ old('returnable_time', $product->returnable_time) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}">
                                                    </inputelement>
                                                </div>
                                                <div class="col-md-4">
                                                    <inputelement name="delivery_time" type="number"
                                                        placeholder="maximum 15 days all over nepal"
                                                        value="{{ old('delivery_time', $product->delivery_time) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}">
                                                    </inputelement>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="policies-radio mt-2 mb-2">
                                                        <input type="radio" name="policy_data" value="0" id="policy" checked> Standard
                                                            <br>
                                                        <input type="radio" name="policy_data" value="1" id="policy"> Return Policy
                                                    </div>
                                                </div>

                                                <div class="col-md-12 hiddepolicy" hidden>
                                                    <div class="mb-1">
                                                        <label for="return_policy" class="form-label">Return Policy:</label>
                                                        <textarea name="return_policy" class="form-control editor" rows="3" id="return_policy">{{ old('return_policy') }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <Productform></Productform>

                                </div>
                                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <inputelement name="meta_title" placeholder="Macbook Pro 2019"
                                                    value="{{ old('meta_title', @$product->meta_title) }}"
                                                    :errors="{{ json_encode($errors->toArray()) }}">
                                                </inputelement>

                                                {{-- <x-filemanager :value="@$product->og_image" :name="'og_image'">
                                                </x-filemanager> --}}

                                                
                                            </div>
                                            <div class="col-md-6">
                                                <div class="col-12">
                                                    <label for="og_image"> OG Image</label>

                                                    <div class="input-group">
                                                        <span class="input-group-btn">
                                                            <a id="og_image" data-input="thumbnail1"
                                                                data-preview="holder" class="btn btn-primary">
                                                                <i class="fa fa-picture-o"></i> Choose
                                                            </a>
                                                        </span>
                                                        <input id="thumbnail1" class="form-control" type="text"
                                                            name="og_image"
                                                            value="{{ old('og_image', @$product->og_image) }}">

                                                    </div>

                                                    @if (isset($product->og_image))
                                                        <img src="{{ $product->og_image }}" alt="Img"
                                                            style="width: 100px; height: auto;">
                                                    @endif

                                                    @error('og_image')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Meta
                                                        Keywords:</label><small class="text-danger">*</small>
                                                    <textarea class="form-control" rows="4" name="meta_keywords">{{ old('meta_keywords', @$product->meta_keywords) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <label class="form-label" for="name">Meta
                                                        Description:</label><small class="text-danger">*</small>
                                                    <textarea class="form-control" rows="4" name="meta_description">{{ old('meta_description', @$product->meta_description) }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                <button type="reset" class="btn btn-secondary waves-effect waves-float waves-light">Reset</button>
            </div>
            </form>
        </section>
    </section>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('dashboard/vendors/css/file-uploaders/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/plugins/forms/form-file-uploader.css') }}">
@endpush
@push('script')
    <script src="{{ asset('dashboard/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.lfm').filemanager("image");
        });
    </script>

    <script>
        $('#og_image').filemanager("image", {
            prefix: "/laravel-filemanager"
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

<script>
     var options = {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/filemanager/upload?type=Images&_token=',
                filebrowserBrowseUrl: '/filemanager?type=Files',
                filebrowserUploadUrl: '/filemanager/upload?type=Files&_token='
            };
            CKEDITOR.replace('return_policy', options);
</script>

@endpush
