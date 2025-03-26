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
<form action="{{ route('seller-product-edit.priceAndStock', $product->id) }}" method="post">
     @csrf
     @method('PATCH')
     <div class="form-group">
        <button type="button" class="btn btn-info btn-sm float-end mb-1" id="addon-button">Add New Row</button>
     </div>
     <div id="addon-row">
     @foreach ($color_id as $key => $value)
            {{-- @dd($value) --}}
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
                         <th>color_text*</th>
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
     <div class="form-group float-end">
         <button type="submit" class="btn btn-primary btn-sm">Update</button>
     </div>
 </form>



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


 @push('script')
 <script>
    $(document).ready(function(){
        var count_row = "{{$count_row ?? 1}}";
        var count_tr=0;
        @if($product->stocks->count() > 0)
            count_tr = {{ $product->stocks->count()}};
        @endif
        console.log(count_row);
        changeElement();
        onChangeTableRow();
        onChangeTableEditButton();
        $('#addon-button').on('click', function(e){
            count_row ++;
            console.log(count_row);
            e.preventDefault();
            var addon = '<div class="form-group remove'+count_row+'"><label for="">Color Family:</label><button type="button" class="btn btn-danger btn-sm float-end me-2 mb-1 remove" data-remove="remove'+count_row+'">Remove</button><select name="image_color[]" id="color" class="form-control -control-sm">'

                @foreach ($colors as $color)

                addon += '<option value="{{ $color->id }}">{{ $color->title }}</option>';

                @endforeach
                
            addon += '</select></div>';
            
            addon += '<div class="mb-1 remove'+count_row+'"><label class="form-label" for="title">Image</label><div class="input-group"><input type="text" class="form-control" name="image_name[]" value="" placeholder="Image" id="thumbnail'+count_row+'" /> <a id="lfm'+count_row+'" data-input="thumbnail'+count_row+'" data-preview="holder'+count_row+'" class="lfm btn btn-outline-primary waves-effect" type="button">Go</a><div id="holder'+count_row+'" class="col-12 mt-2"><img src="" style="margin-top: 15px; max-height: 100px" /></div></div></div>'
            $('#addon-row').append(addon);
            loadUniSharp();
            changeElement();
        });
        $('#add_new_sku').on('click', function(){
            count_tr ++;
            var addon = '<tr class="removetr'+count_tr+'"><td><input type="checkbox" value="1" checked></td><td><select name="color[]" id=""  class="form-control form-control-sm">';
                @foreach ($colors as $color)

                    addon += '<option value="{{ $color->id }}">{{ $color->title }}</option>';

                @endforeach

                addon += '</select></td>';


                @foreach ($attributes as $attribute)
                addon += '<td><input type="text" name="key[]" hidden value="{{ $attribute->id }}">'
                                     @php
                                         $values = explode(',', $attribute->value);
                                     @endphp
                                     addon +='<select name="attributes[]" id="" class="form-control" style="width: 100px;">';
                                         @foreach ($values as $value)

                                             addon += '<option value="{{ trim($value) }}">{{ trim($value) }}</option>';

                                         @endforeach

                                     addon += '</select></td>';
                @endforeach

                    addon +='<td><input type="number" value="" class="form-control form-control-sm" name="price[]" required></td>';

                    
                    addon +='<td>';

                    addon +='<input type="text" name="special_price[]" style="display: none;" value="" id="special_price'+count_tr+'">';
                    addon += '<input type="text" name="special_from[]" value="" style="display: none;" id="special_from'+count_tr+'">';
                    addon+=' <label for="special_price" id="special_price_display'+count_tr+'"></label>';
                    addon+=' <a href="#" class="add_special_price" data-special_price="#special_price'+count_tr+'" data-special_form="#special_from'+count_tr+'" data-special_to="#special_to'+count_tr+'" data-special_price_display="#special_price_display'+count_tr+'"> <i class="fa fa-edit"></i>Edit</a>';
                    addon+='</td>';
                    
                   addon +='<td><input type="text" name="quantity[]" class="form-control form-control-sm" value=""></td>';

                   addon +='<td> <input type="text" name="sellersku[]" class="form-control form-control-sm" value=""> </td>';

                   addon +='<td><input type="text" name="free_items[]" class="form-control form-control-sm" value=""></td>';

                   addon +='<td><a href="#" class="remove-tr" id="removetr'+count_tr+'" data-remove="removetr'+count_tr+'"><i class="fa fa-trash"></i>Delete</a></td></tr>';

                   $('.table').append(addon);
                   onChangeTableRow();
                   onChangeTableEditButton(); 
        });

        $('.confirm_special').on('click', function(e){
            e.preventDefault();
            $($(this).data('special_price')).val($('#special_price').val());
            $($(this).data('special_from')).val($('#start-date').val());
            $($(this).data('special_to')).val($('#end-date').val());
            let setup = $(this).data('special_price_display');
            $(setup).text($('#special_price').val());
            $(this).removeAttr('data-special_price');
            $(this).removeAttr('data-special_form');
            $(this).removeAttr('data-special_to');
            $(this).removeAttr('data-special_price_display');
            $('.price-modal').css({"display":"none"});
        });
    });
    function changeElement(){
        $('.remove').on('click', function(e){
            var dataID = $(this).data('remove');
            console.log(dataID);
            // if(count_row >= 1){
                $('.'+dataID).remove();
            // }
        });
    }
    function onChangeTableRow(){
        $('.remove-tr').on('click', function(e){
            e.preventDefault();
            var data_remove = $(this).data('remove');
            // if(count_tr >= 1){
                $('.'+data_remove).remove();
            // }
        });
    }
    function onChangeTableEditButton(){
        $('.add_special_price').on('click', function(e){
            e.preventDefault();
            $('.confirm_special').attr('data-special_price', $(this).data('special_price'));
            $('.confirm_special').attr('data-special_form', $(this).data('special_form'));
            $('.confirm_special').attr('data-special_to', $(this).data('special_to'));
            $('.confirm_special').attr('data-special_price_display', $(this).data('special_price_display'));
            $('.price-modal').css({"display":"block"});
        });
    }

    $('.holder-remove').on('click', function(e){
        e.preventDefault();
        let remove_element = $(this).data('holder');
        let color_id = $(this).data('color_id');
        let product_id = $(this).data('product_id');
        let img = $(this).data('img');

        if(confirm("Are You Sure To delete this, would not be revertable")){
            $.ajax({
                url: "{{ route('seller-remove-color-image') }}",
                type: 'post',
                data: {
                    color_id: color_id,
                    product_id: product_id,
                    img: img,
                    _token:"{{csrf_token()}}"
                },
                success: function(response) {
                    $(remove_element).remove();
                    console.log("Success");
                },
                error: function(response) {
                    console.log("Something is wrong");
                }
            });
        }
    });
 </script>
 @endpush