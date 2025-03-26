<form action="{{ route('products-edit.basicEdit', $product->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="mb-1">
        <label class="form-label" for="name">Name</label>
        <small class="text-danger">*</small>
        <input type="text" id="name" class="form-control form-control-sm" placeholder="Enter Product Name"
            name="name" value="{{ old('name', @$product->name) }}" required="required" />
        <span class="text-danger">{{ $errors->first('name') }}</span>
        <div id="autosearchData">

        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-1 vat-basic" hidden>
            <li>  
                <label class="form-label" for="name">VAT: </label>&nbsp;&nbsp;
            </li>
            <li>
            <input type="radio" value="1" name="vat_percent" checked>Included
            </li>
            {{-- <li>
            <input type="radio" value="0" name="vat_percent" {{(@$product->vat_percent==0) ? 'checked' : ''}}>Excluded
            </li>
            <li>
            <input type="radio" value="3" name="vat_percent" {{(@$product->vat_percent==3) ? 'checked' : ''}}>None
            </li>    --}}
        </div>
    </div>

    <div class="mb-1">
        <label class="form-label" for="min_order">Min Order</label>
        <input type="text" id="min_order" class="form-control form-control-sm" placeholder="Enter Minimum Order"
            name="min_order" value="{{ old('min_order', @$product->min_order) }}" required="required" />
        <span class="text-danger">{{ $errors->first('min_order') }}</span>
    </div>

    <div class="mb-1">
        <label class="form-label" for="returnable_time">Returnable Time (Days.)</label>
        <input name="returnable_time" id="returnable_time" class="form-control form-control-sm"  type="number"  value="{{ old('returnable_time', $product->returnable_time) }}">
    </div>

    <div class="mb-1">
        <label class="form-label" for="delivery_time">Delivery Time</label>
        <input type="text" id="delivery_time" class="form-control form-control-sm"
            placeholder="Enter Maximum Delivery Time" name="delivery_time"
            value="{{ old('delivery_time', @$product->delivery_time) }}"/>
        <span class="text-danger">{{ $errors->first('delivery_time') }}</span>
    </div>

    <div class="mb-1">
        <label class="form-label" for="shipping_charge">Shipping Charge</label>
        <input type="text" id="shipping_charge" class="form-control form-control-sm"
            placeholder="Enter Shipping Charge" name="shipping_charge"
            value="{{ old('shipping_charge', @$product->shipping_charge) }}"/>
        <span class="text-danger">{{ $errors->first('shipping_charge') }}</span>
    </div>

    <div class="mb-1">
        <label class="form-label" for="title">Image</label>
        {{-- @dd($featured_images) --}}
        <div class="input-group">
            <input type="text" class="form-control" name="image_name[]" value="{{ $featured_images }}"
                placeholder="Image" id="thumbnail-1" />
            <a id="lfm-1" data-input="thumbnail-1" data-preview="holder-1"
                class="lfm btn btn-primary waves-effect" type="button">Go</a>
            <div id="holder-1" class="col-12 mt-2">
                <img src="{{ $featured_images }}" style="margin-top: 15px; max-height: 100px" />
            </div>
        </div>
    </div>
    <div class="mb-1">
        <label class="form-label" for="name">Video URL</label>
        <small class="text-danger">*</small>
        <input type="text" id="name" class="form-control form-control-sm" placeholder="Enter Video URL"
            name="url" value="{{ old('url', @$product->url) }}" />
        <span class="text-danger">{{ $errors->first('url') }}</span>
    </div>

    <div class="mb-1">
        <div class="form-group">
            <label for="">Brand:</label>
            <select name="brand_id" id="color" class="form-control form-control-sm">
                <option value="">---Please Select---</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                        {{ $brand->name }}</option>
                @endforeach
            </select>
            <span class="text-danger">{{ $errors->first('brand_id') }}</span>
            <a href="" id="" data-bs-toggle="modal" data-bs-target="#exampleModalbrand">Add New</a>
        </div>
    </div>

    {{-- <div class="col-md-12">
        <label for="city">City</label>
        <select name="city_id[]" id="city_id" class="form-control select2" multiple>
            @foreach ($cities as $city)
                <option value="{{ $city->id }}" {{ $current_cities->isNotEmpty() && in_array($city->id, $current_cities->pluck('city_id')->toArray()) ? 'selected' : '' }}>{{ $city->city_name }}</option>
            @endforeach
        </select>
    </div> --}}

    <div class="mb-5 me-1">
        <button type="submit" class="btn btn-primary btn-sm float-end">Update</button>
    </div>
</form>
