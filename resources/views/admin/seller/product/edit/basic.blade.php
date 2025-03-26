<form action="{{ route('seller-products-edit.basicEdit', $product->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="mb-1">
        <label class="form-label" for="name">Name</label>
        <small class="text-danger">*</small>
        <input type="text" id="name" class="form-control form-control-sm" placeholder="Enter Product Name" name="name"
            value="{{ old('name', @$product->name) }}" required="required" />
        <span class="text-danger">{{ $errors->first('name') }}</span>
    </div>

    <div class="col-md-6">
        <div class="mb-1">
            <label class="form-label" for="name">Vat: </label>&nbsp;&nbsp;
             <input type="radio" value="1" name="vat_percent" {{(@$product->vat_percent==1) ? 'checked' : ''}}>Included
             <input type="radio" value="0" name="vat_percent" {{(@$product->vat_percent==0) ? 'checked' : ''}}>Excluded
             <input type="radio" value="3" name="vat_percent" {{(@$product->vat_percent==3) ? 'checked' : ''}}>None

        </div>
    </div>

    <div class="mb-1">
        <label class="form-label" for="min_order">Min Order</label>
        <input type="text" id="min_order" class="form-control form-control-sm" placeholder="Enter Minimum Order" name="min_order"
            value="{{ old('min_order', @$product->min_order) }}" required="required" />
        <span class="text-danger">{{ $errors->first('min_order') }}</span>
    </div>

    <div class="mb-1">
        <label class="form-label" for="delivery_time">Delivery Time</label>
        <input type="text" id="delivery_time" class="form-control form-control-sm" placeholder="Enter Maximum Delivery Time" name="delivery_time"
            value="{{ old('delivery_time', @$product->delivery_time) }}" required="required" />
        <span class="text-danger">{{ $errors->first('delivery_time') }}</span>
    </div>

    <div class="mb-1">
        <label class="form-label" for="title">Image</label>
        {{-- @dd($featured_images) --}}
        <div class="input-group">
            <input type="text" class="form-control" name="image_name[]" value="{{ $featured_images }}"
                placeholder="Image" id="thumbnail-1" />
            <a id="lfm-1" data-input="thumbnail-1" data-preview="holder-1"
                class="lfm btn btn-outline-primary waves-effect" type="button">Go</a>
            <div id="holder-1" class="col-12 mt-2">
                <img src="{{$featured_images}}" style="margin-top: 15px; max-height: 100px" />
            </div>

        </div>
    </div>
    <div class="mb-1">
        <label class="form-label" for="name">Video URL</label>
        <small class="text-danger">*</small>
        <input type="text" id="name" class="form-control form-control-sm" placeholder="Enter Video URL" name="url"
            value="{{ old('url', @$product->url) }}" />
        <span class="text-danger">{{ $errors->first('url') }}</span>
    </div>
{{-- @dd($product); --}}
    <div class="mb-1">
        <div class="form-group">
            <label for="">Brand:</label>
            <select name="brand_id" id="color" class="form-control form-control-sm select2">
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                        {{ $brand->name }}</option>
                @endforeach
            </select>
            <span class="text-danger">{{ $errors->first('brand_id') }}</span>
        </div>
    </div>
    <div class="mb-5 me-1">
        <button type="submit" class="btn btn-primary btn-sm float-end">Update</button>
    </div>
</form>
