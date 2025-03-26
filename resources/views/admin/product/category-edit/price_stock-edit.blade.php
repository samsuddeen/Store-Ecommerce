@push('style')
<style>
.price-modal {
  transition: 1.05ms;
  position: fixed;
  top: 35%;
  left: 45%;
  z-index: 900;
  overflow: hidden;
  overflow-x: hidden;
  height: auto;
  width: 273px;
  background-color: aqua;
}

.card-body {
  background-color: aqua;
}

.filemanager-modal {
  transition: 1.05ms;
  position: fixed;
  top: 10%;
  left: 45%;
  z-index: 900;
  overflow: hidden;
  overflow-x: hidden;
  height: auto;
  width: 40%;
  background-color: aqua;
}
</style>
@endpush


     <div class="form-group">
        <button type="button" class="btn btn-info btn-sm float-end mb-1" id="addon-button">Add New Row</button>
     </div>
     <div id="addon-row">

     @foreach ($color_id as $key => $value)
            <div class="form-group remove{{$key}}">
                <label for="">Color Family:</label>
                <button type="button" class="btn btn-danger btn-sm float-end me-2 mb-1 remove" data-remove="remove{{$key}}">Remove</button>
                <select name="image_color[]" id="color" class="form-control form-control-sm">
                    @foreach ($colors as $color)
                        <option value="{{ $color->id }}" @if ($color->id == $value) selected @endif>
                            {{ $color->title }}</option>
                    @endforeach
                </select>
            </div>
            @php
                $images = App\Helpers\ProductFormHelper::getImages($product, $value);
            @endphp
            <div class="mb-1 remove{{$key}}">
                <label class="form-label" for="title">Image</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="image_name[]" value="{{ $images }}"
                        placeholder="Image" id="thumbnail{{ $key }}" value="" />
                    <a id="lfm{{ $key }}" data-input="thumbnail{{ $key }}"
                        data-preview="holder{{ $key }}" class="lfm btn btn-outline-primary waves-effect"
                        type="button">Go</a>
                    <div id="holder{{ $key }}" class="col-12 mt-2">
                        @foreach(explode(',', $images) as $index=>$image) 
                        @php
                            $rand_id = rand(0000, 9999);
                        @endphp
                        <div class="holder-img" id="holder-{{$rand_id}}">
                            <img src="{{$image}}" style="margin-top: 15px; max-height: 100px" />
                            <button type="button" class="holder-remove" data-holder="#holder-{{$rand_id}}" data-product_id="{{$product->id}}" data-color_id="{{$value}}" data-img="{{$image}}">Close</button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

         @php
           $count_row = $key;
         @endphp
     @endforeach
    </div>

     <div class="col-md-12">
         <label for="color"> *</label>
         <a href="#" id="add_new_sku">Add New Sku</a>
         <div>
             <table class="table">
                 <thead>
                     <tr>
                         <th>Availability</th>
                         <th>color_text</th>
                         @foreach ($attributes as $attribute)
                             <th>{{ $attribute->title }}</th>
                         @endforeach

                         <th>Price*</th>
                         <th>Special Price (Rs)</th>
                         <th>Quantity</th>
                         <th>SellerSKU</th>
                         <th>Free Items</th>
                         <th></th>
                     </tr>
                 </thead>
                 <tbody class="table-body">
                     @foreach ($product->stocks as $index => $stock)
                         <tr class="removetr{{$index}}">
                             <td>
                                 <input type="checkbox" value="1" checked>
                             </td>
                             <td>
                                 <select name="color[]" id="" class="form-control form-control-sm">
                                     @foreach ($colors as $color)
                                         <option value="{{ $color->id }}" {{($color->id == $stock->color_id) ? 'selected' : ''}}>
                                             {{ $color->title }}
                                         </option>
                                     @endforeach
                                 </select>
                             </td>
                             @foreach ($attributes as $attribute)
                                @php
                                    $test_status = App\Helpers\BackendHelper::attributeSelection($attribute, $stock);
                                @endphp
                                 <td>
                                     <input type="text" name="key[]" hidden value="{{ $attribute->id }}">
                                     @php
                                         $values = explode(',', $attribute->value);
                                     @endphp
                                     <select name="attributes[]" id="" class="form-control" style="width: 100px;">
                                         @foreach ($values as $value)
                                             <option value="{{ trim($value) }}" {{( trim($test_status->value ?? null) == trim($value)) ? 'selected' : ''}}>
                                                 {{ trim($value) }}</option>
                                         @endforeach
                                     </select>
                                 </td>
                             @endforeach
                             <td>
                                 <input type="number" value="{{ $stock->price }}"
                                     class="form-control form-control-sm" name="price[]" required>
                             </td>
                             <td>
                                 <input type="text" name="special_price[]" style="display: none;"
                                     value="{{ $stock->special_price }}" id="special_price{{$index}}">
                                 <input type="text" name="special_from[]" value="{{ $stock->special_form }}"
                                     style="display: none;" id="special_from{{$index}}">
                                 <input type="text" name="special_to[]" value="{{ $stock->special_to }}"
                                     style="display: none;" id="special_to{{$index}}">
                                 <label for="special_price" id="special_price_display{{$index}}">{{ $stock->special_price }}</label>
                                 <a href="#" class="add_special_price" data-special_price="#special_price{{$index}}" data-special_form="#special_from{{$index}}" data-special_to="#special_to{{$index}}" data-special_price_display="#special_price_display{{$index}}"> <i class="fa fa-edit"></i>Edit</a>
                             </td>
                             <td>
                                 <input type="text" name="quantity[]" class="form-control form-control-sm"
                                     value="{{ $stock->quantity }}">
                             </td>
                             <td>
                                 <input type="text" name="sellersku[]" class="form-control form-control-sm"
                                     value="{{ $stock->sellersku }}">
                             </td>
                             <td>
                                 <input type="text" name="free_items[]" class="form-control form-control-sm"
                                     value="{{ $stock->free_items }}">
                             </td>
                             <td>
                                 <a href="#" id="removetr{{$index}}"><i class="fa fa-trash"></i>Delete</a>
                             </td>
                         </tr>
                     @endforeach
                 </tbody>
             </table>
         </div>
     </div>
     <input type="hidden" name="category_id" value="{{ $product->category_id }}">

     
 <div class="price-modal" style="display: none">
    <div class="card card-body">
      <h6>Special Price</h6>
      <div class="form-group">
        <label for="">Choose Range (From)</label>
        <input type="date"  class="form-control form-control-sm" id="start-date">
      </div>
      <div class="form-group">
        <label for="">Choose Range (To)</label>
        <input type="date"  class="form-control form-control-sm" id="end-date">
      </div>
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number"  class="form-control form-control-sm" id="special_price">
      </div>
      <div class="form-group mt-2">
        <button type="button"  class="btn btn-info btn-sm confirm_special">Confirm</button>
      </div>
    </div>
</div>



