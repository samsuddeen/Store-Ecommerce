Service and Delivery
<form action="{{ route('seller-product-edit.serviceAndDelivery', $product->id) }}" method="post">
    @csrf
    @method('PATCH')
    <div class="form-group">
        <label for="">Warranty Type</label>
        <select name="warranty_type" id="warranty_type" class="form-control form-control-sm">
            <option value="">Please Select</option>
            <option value="No Warranty" {{ $product->warranty_type == 'No Warranty' ? 'selected' : '' }}>No Warenty
            </option>
            <option value="Local Seller Warranty"
                {{ $product->warranty_type == 'Local Seller Warranty' ? 'selected' : '' }}>Local Seller Warranty
            </option>
            <option value="International Warranty"
                {{ $product->warranty_type == 'International Warranty' ? 'selected' : '' }}>International Warranty
            </option>
            <option value="100 % Original Product"
                {{ $product->warranty_type == '100 % Original Product' ? 'selected' : '' }}>100 % Original Product
            </option>
            <option value="Brand Warranty" {{ $product->warranty_type == 'Brand Warranty' ? 'selected' : '' }}>Brand
                Warranty</option>
            <option value="Seller Warranty" {{ $product->warranty_type == 'Seller Warranty' ? 'selected' : '' }}>
                Seller
                Warranty</option>
        </select>
    </div>
    <div class="form-group">
        <label for="">Warranty Period</label>
        <select name="warranty_period" id="warranty_period" class="form-control form-control-sm">
            <option value="">Please Select</option>

            @for ($i = 1; $i <= 18; $i++)
                <option value=" {{ $i }} Months"
                    {{ $product->warranty_period == $i . ' Months' ? 'selected' : '' }}>{{ $i . ' Months' }}
                </option>
            @endfor

            @for ($i = 1; $i <= 18; $i++)
                <option value="{{ $i }} Years"
                    {{ $product->warranty_period == $i . ' Years' ? 'selected' : '' }}>{{ $i . ' Years' }}
                </option>
            @endfor
            <option :value="'Life Time'">Life Time</option>
        </select>
    </div>
    <div class="form-group">
        <label for="warranty_policy">Warranty Policy</label>
        <textarea name="warranty_policy" id="warranty_policy" rows="5" class="form-control form-control-sm">{{ $product->warranty_policy }}</textarea>
    </div>
    <h2>Delivery</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="package_weight">Delivery Time</label>
                <input type="text" name="delivery_time" class="form-control form-control-sm"
                    value="{{ $product->delivery_time }}">
            </div>
        </div>



        <div class="col-md-4">
            <div class="form-group">
                <label for="package_weight">Min Order:</label>
                <input type="text" name="min_order" class="form-control form-control-sm"
                    value="{{ $product->min_order }}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="package_weight">Returnable Time:</label>
                <input type="text" name="returnable_time" class="form-control form-control-sm"
                    value="{{ $product->returnable_time }}">
            </div>
        </div>

        <div class="col-md-6">
            Standard <input type="radio" name="policy_data" value="0" id="policy" {{(@$product->policy_data==0) ? 'checked' : ''}}>
            <br>
            Return Policy<input type="radio" name="policy_data" value="1" id="policy" {{(@$product->policy_data==1) ? 'checked' : ''}}>
        </div>

    </div>
    <div class="col-md-12 hiddepolicy" {{(@$product->policy_data==1) ? '' : 'hidden'}}>
        <div class="mb-1">
            <label class="form-label">Return Policy:</label>
            <textarea name="return_policy" class="form-control editor" rows="3">{{ @$product->return_policy}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="package_weight">Packaged Weight (In Number (gm))</label>
        <input type="number" name="package_weight" class="form-control form-control-sm"
            value="{{ $product->package_weight }}">
    </div>
    <div class="form-group">
        <label for="package_dimension">Package Dimension</label>
        <input type="text" class="" name="dimension_length" placeholder="Lenght (CM)"
            value="{{ $product->dimension_length }}">
        <input type="text" class="" name="dimension_width" placeholder="Width (CM)"
            value="{{ $product->dimension_width }}">
        <input type="text" class="" name="dimension_height" placeholder="Height (CM)"
            value="{{ $product->dimension_height }}">
    </div>
    <div class="form-group">
        <label for="dangerous_good">
            Dangerous:
        </label>
        <label for="">
            <input type="checkbox" value="Battery" name="dangerous_good[]"
                {{ in_array('Battery', $dangerous_goods) ? 'checked' : '' }}>Battery
        </label>
        <label for="">
            <input type="checkbox" value="Flammable" name="dangerous_good[]"
                {{ in_array('Flammable', $dangerous_goods) ? 'checked' : '' }}>Flammable
        </label>
        <label for="">
            <input type="checkbox" value="Liquid" name="dangerous_good[]"
                {{ in_array('Liquid', $dangerous_goods) ? 'checked' : '' }}>Liquid
        </label>
        <label for="">
            <input type="checkbox" value="None" name="dangerous_good[]"
                {{ in_array('None', $dangerous_goods) ? 'checked' : '' }}>None
        </label>
    </div>
    <div class="form-group">
        <button type="submit" class="bt btn-primary btn-sm float-end">Update</button>
    </div>

</form>



@push('script')
    <script>
        // ClassicEditor.create(document.querySelector('.editor'), {
        //         licenseKey: '',
        //     })
        //     .then(editor => {
        //         window.editor = editor;
        //     })
        //     .catch(error => {
        //         console.error('Oops, something went wrong!');
        //         console.error(
        //             'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:'
        //         );
        //         console.warn('Build id: wizt6zz8gcop-10bq0f55gpit');
        //         console.error(error);
        //     });
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
