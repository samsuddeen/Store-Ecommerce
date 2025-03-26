<form action="{{ route('seller-product-edit.productAttribute', $product->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="row">
        @foreach ($attributes as $key => $attribute)
            <div class="col-md-3 mb-1">
                <label class="form-label" for="name">{{ $attribute->title }}</label>
                <input type="text" name="attribute[]" value="{{ $attribute->id }}" hidden>
                <small class="text-danger">*</small>
                @php
                    $values = explode(',', $attribute->value);
                @endphp
                <select name="value[]" id="value" class="form-control form-control-sm">
                    @foreach ($values as $value)
                        <option value="{{ $value }}"
                            {{ $product->attributes->count() > 0 ?? trim($product->attributes[$key]['value']) == trim($value) && $product->attributes[$key]['key'] == $attribute->id ? 'selected' : '' }}>
                            {{ ucfirst($value) }}</option>
                    @endforeach
                </select>
                <span class="text-danger">{{ $errors->first('attribute') }}</span>
                <span class="text-danger">{{ $errors->first('value') }}</span>
            </div>
        @endforeach
    </div>
    <div class="mb-5 me-1">
        <button type="submit" class="btn btn-primary btn-sm float-end">Update</button>
    </div>
</form>
