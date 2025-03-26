@extends('layouts.app')
@section('content')
    <section id="default-breadcrumb">
        <section id="multiple-column-form">
            @if ($product->id)
                <form action="{{ route('product.update', $product->id) }}" class="form"
                    enctype="multipart/form-data" method="POST">
                    @method('PATCH')
                @else
                    <form action="{{ route('product.store') }}" class="form" enctype="multipart/form-data"
                        method="POST">
            @endif
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Information</h4>
                        </div>

                        <div class="card-body">

                            <div class="row">

                                <div class="col-12">
                                    <x-filemanager name="thumbnail" :value="old('thumbnail', $product->thumbnail)"></x-filemanager>
                                    <inputelement name="name" placeholder="Macbook Pro 2019"
                                        value="{{ old('name', $product->name) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}" required="true">
                                    </inputelement>
                                </div>
                                <div class=" col-12">
                                    <oldcategory cat="{{ old('category_id', $product->category_id) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}"></oldcategory>
                                </div>
                                <div class=" col-6">
                                    <brand value="{{ old('brand_id', $product->brand_id) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}"></brand>
                                </div>
                                <div class=" col-6">
                                    <country value="{{ old('country_id', $product->country_id) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}"> </country>
                                </div>
                                <div class="col-12">
                                    <inputelement name="sku" placeholder="B008135"
                                        value="{{ old('sku', $product->sku) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}" required="true">
                                    </inputelement>
                                </div>

                                <div class="col-12">
                                    <inputelement name="material" placeholder="Cotton Glod Plated"
                                        value="{{ old('material', $product->material) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}" required="true"></inputelement>
                                </div>



                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="">Color</label>
                                        <select class="form-control select2" name="colors[]" id="colors" multiple>
                                            @foreach ($allColor as $color)
                                                <option value="{{ $color->id }}"
                                                    {{ in_array($color->id, $colors) ? 'selected' : null }}>
                                                    {{ $color->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Description</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-floating mb-0">
                                        <textarea data-length="2000" class="form-control char-textarea" id="textarea-counter" rows="3" placeholder="Description"
                                            name="description" style="height: 200px"
                                            required>{{ old('description', $product->description) }}</textarea>
                                        <label for="textarea-counter">Product Description</label>
                                    </div>
                                    @error('description')
                                        <small class="textarea-counter-value float-start">{{ $message }}</small>
                                    @enderror
                                    <small class="textarea-counter-value float-end"><span class="char-count">0</span> /
                                        2000 </small>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Pricing</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <inputelement name="price" placeholder="$" type="number"
                                        value="{{ old('price', $product->price) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}" required='true'></inputelement>
                                </div>
                                <div class="col-6">
                                    <inputelement name="retailPrice" placeholder="$" type="number"
                                        value="{{ old('retailPrice', $product->retailPrice) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}"></inputelement>
                                    </inputelement>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Currency</label>
                                        <input type="text" class="form-control form-control-sm" maxlength="5" reqired
                                            name="currency" id="" aria-describedby="helpId" placeholder="$"
                                            value="{{ old('currency', $product->currency) }}">

                                        @error('currency')
                                            <small id="helpId" class="form-text text-error">{{ $message }}</small>
                                        @enderror

                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Packaging</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <inputelement name="packSize" placeholder="$" type="number"
                                        value="{{ old('packSize', $product->packSize) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}"></inputelement>


                                </div>
                                <div class="col-6 d-flex">
                                    <inputelement name="packSizeUnit" placeholder="$" type="number"
                                        value="{{ old('packSizeUnit', $product->packSizeUnit) }}"
                                        :errors="{{ json_encode($errors->toArray()) }}"></inputelement>
                                </div>
                                <div class="col-6">
                                    <inputelement name="packPerCarton" placeholder="$" type="number"
                                        value="{{ old('packPerCarton', $product->packPerCarton) }}"></inputelement>

                                </div>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Dimensions & weight</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">



                                <div class="row ">
                                    <div class="col-3">
                                        <inputelement name="length" type='number' placeholder="Package length in  CM"
                                            value="{{ old('length', $product->length) }}"
                                            :errors="{{ json_encode($errors->toArray()) }}">
                                        </inputelement>
                                    </div>
                                    <div class="col-3">
                                        <inputelement name="width" type='number' placeholder="Package width in CM"
                                            value="{{ old('width', $product->width) }}"
                                            :errors="{{ json_encode($errors->toArray()) }}">
                                        </inputelement>
                                    </div>
                                    <div class="col-3">
                                        <inputelement name="height" type='number' placeholder="Package height in CM"
                                            value="{{ old('height', $product->height) }}"
                                            :errors="{{ json_encode($errors->toArray()) }}">
                                        </inputelement>

                                    </div>
                                    <div class="col-3">
                                        <selectelement :lists="['CM', 'M']" name="lengthUnit"
                                            value={{ old('lengthUnit', $product->lengthUnit) }}></selectelement>
                                    </div>
                                </div>

                                <div class="col-6 row">
                                    <div class="col-9">
                                        <inputelement name="weight" type='number' placeholder="Package Weight in KG"
                                            value="{{ old('weight', $product->weight) }}"
                                            :errors="{{ json_encode($errors->toArray()) }}">
                                        </inputelement>
                                    </div>
                                    <div class="col-3">
                                        <selectelement :lists="['KG', 'GM']" name="weightUnit"
                                            value={{ old('weightUnit', $product->weightUnit) }}></selectelement>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <features :test="{{ json_encode($features) }}" :errors="{{ json_encode($errors->toArray()) }}">
                </features>


                <sizes :selectedsizes="{{ json_encode($sizes) }}" :errors="{{ json_encode($errors->toArray()) }}">
                </sizes>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Tags</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <select name="tags[]" id="tags" class="form-control form-control-sm select2"
                                    multiple="multiple">
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



                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Images</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <x-filemanager name="images" :value="$images"></x-filemanager>
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input type="checkbox" class="form-check-input" name="publishStatus"
                                            id="publishStatus" {{ $product->publishStatus ? 'checked' : null }} />
                                        <label class="form-check-label" for="publishStatus">Publish Status</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                @if ($errors->any())
                    @foreach ($errors->all() as $item)
                        {{ $item }}
                    @endforeach
                @endif
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
@endpush
