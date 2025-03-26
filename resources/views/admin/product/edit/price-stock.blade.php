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
    <button class="nav-link" id="#" type="button" data-bs-toggle="modal"
        data-bs-target="#exampleModal" role="tab" aria-controls="profile"
        aria-selected="false">Category
    </button>
<form action="{{ route('product-edit.priceAndStock', $product->id) }}" method="post">
     @csrf
     @method('PATCH')
     <div class="form-group">
        <button type="button" class="btn btn-secondary btn-sm float-end mb-1" id="addon-button">Add New Row</button>
     </div>
     <div id="addon-row">
        {{-- @dd($color_id) --}}
     @foreach ($color_id as $key => $value)
            {{-- @dd($value) --}}
            <div class="form-group remove{{$key}}">
                <label for="">Color Family:</label>
                <button type="button" class="btn btn-danger btn-sm float-end me-1 mb-1 remove" data-remove="remove{{$key}}">Remove</button>
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
                        placeholder="Image" id="thumbnail{{ $key }}" value="" required/>
                    <a id="lfm{{ $key }}" data-input="thumbnail{{ $key }}"
                        data-preview="holder{{ $key }}" class="lfm btn btn-primary waves-effect"
                        type="button">Go</a>
                    <div id="holder{{ $key }}" class="col-12 mt-2">
                        @foreach(explode(',', $images) as $index=>$image) 
                        @php
                            $rand_id = rand(0000, 9999);
                        @endphp
                        <div class="holder-img" id="holder-{{$rand_id}}">
                            <img src="{{$image}}" style="margin-top: 15px; max-height: 100px" />
                            <button type="button" class="holder-remove" data-key="{{$key}}" data-currentImagePath="{{$image}}" data-imageValue="{{ $images }}" data-holder="#holder-{{$rand_id}}" data-product_id="{{$product->id}}" data-color_id="{{$value}}" data-img="{{$image}}"><i class="ficon"
                                data-feather="x-circle"></i></button>
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
    {{-- @dd($product) --}}
    <div class="col-md-6">
        <div class="mb-1 dash-radio">
            <label class="form-label" for="product_for"><strong>Product For</strong>:</label>
            <input type="radio" value="1" name="product_for" {{@$product->product_for=='1' ? 'checked' : ''}}>Customer
            <input type="radio" value="2" name="product_for" {{@$product->product_for=='2' ? 'checked' : ''}}>Retailer
            <input type="radio" value="3" name="product_for" {{@$product->product_for=='3' ? 'checked' : ''}}>Both
        </div>
    </div>
     <div class="col-md-12">
         <label for="color"> *</label>
         <a href="#" id="add_new_sku">Add New Sku</a>
         <div class="table-responsive tablesku" id="updateCatTable">
             <table class="table">
                 <thead>
                     <tr>
                         <th>Availability</th>
                         <th>color_text</th>
                         @foreach ($attributes as $attribute)
                             <th>{{ $attribute->title }}</th>
                         @endforeach

                         <th>Price</th>
                         <th>Wholesale Price</th>
                         <th>Special Price (Rs)</th>
                         <th>Quantity</th>
                         <th>Minimum Quantity(For WholeSale)</th>
                         <th>SellerSKU</th>
                         <th>Free Items</th>
                         <th>Additional Charge</th>
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
                                 <input type="text" value="{{ $stock->price }}"
                                     class="form-control form-control-sm" name="price[]" required style="width:auto">
                             </td>
                             <td>
                                <input type="text" value="{{ $stock->wholesaleprice }}"
                                    class="form-control form-control-sm" name="wholesaleprice[]" required style="width:auto" min="1">
                            </td>
                             <td class="finalCheckData">
                                 <input type="text" name="special_price[]" style="display: none;"
                                     value="{{ $stock->special_price }}" id="special_price{{$index}}">
                                 <input type="text" name="special_from[]" value="{{ $stock->special_from }}"
                                     style="display: none;" id="special_from{{$index}}">
                                 <input type="text" name="special_to[]" value="{{ $stock->special_to }}"
                                     style="display: none;" id="special_to{{$index}}">
                                 <label for="special_price" id="special_price_display{{$index}}">{{ $stock->special_price }}</label>
                                 <p>{{calculatePercent($stock->price,$stock->special_price)}}%</p>
                                 <a href="#" class="add_special_price" data-orgprice="{{$stock->price}}" data-indexValue="{{$index}}" data-special_price="#special_price{{$index}}" data-special_form="#special_from{{$index}}" data-special_to="#special_to{{$index}}" data-special_price_display="#special_price_display{{$index}}"> <i class="fa fa-edit"></i>Edit</a>
                             </td>
                             <td>
                                 <input type="text" name="quantity[]" class="form-control form-control-sm"
                                     value="{{ $stock->quantity }}">
                             </td>
                             <td>
                                <input type="text" name="mimquantity[]" class="form-control form-control-sm"
                                    value="{{ $stock->mimquantity }}">
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
                                <input type="number" name="additional_charge[]" class="form-control form-control-sm"
                                    value="{{ $stock->additional_charge }}">
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
     <input type="hidden" id="updateCatIdValue" name="category_id" value="{{ $product->category_id }}">
     <div class="form-group float-end">
         <button type="submit" class="btn btn-primary btn-sm">Update</button>
     </div>
 </form>



 <div class="price-modal" style="display: none">
    <div class="card card-body">
        <span class="closeSpecialPrice"><i>x</i></span>
      <h6>Special Price</h6>
      <div class="form-group">
        <label for="">Choose Range (From)</label>
        <input type="date"  name="from_date" class="form-control form-control-sm" id="start-date">
      </div>
      <div class="form-group">
        <label for="">Choose Range (To)</label>
        <input type="date"  name="to_date" class="form-control form-control-sm" id="end-date">
      </div>
      <div class="form-group">
        <label for="price">Price:</label>
        <input type="number" name="price_special" class="form-control form-control-sm" id="special_price" >
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
        // $('#add_new_sku').on('click', function(){
        //     count_tr ++;
        //     var addon = '<tr class="removetr'+count_tr+'"><td><input type="checkbox" value="1" checked></td><td><select name="color[]" id=""  class="form-control form-control-sm">';
        //         @foreach ($colors as $color)

        //             addon += '<option value="{{ $color->id }}">{{ $color->title }}</option>';

        //         @endforeach

        //         addon += '</select></td>';


        //         @foreach ($attributes as $attribute)
        //         addon += '<td><input type="text" name="key[]" hidden value="{{ $attribute->id }}">'
        //                              @php
        //                                  $values = explode(',', $attribute->value);
        //                              @endphp
        //                              addon +='<select name="attributes[]" id="" class="form-control" style="width: 100px;">';
        //                                  @foreach ($values as $value)

        //                                      addon += '<option value="{{ trim($value) }}">{{ trim($value) }}</option>';

        //                                  @endforeach

        //                              addon += '</select></td>';
        //         @endforeach

        //             addon +='<td><input type="number" value="" class="form-control form-control-sm" name="price[]" required></td>';
        //             addon +='<td><input type="number" value="" class="form-control form-control-sm" name="wholesaleprice[]" required style="width:auto" min="1"></td>';
                    
        //             addon +='<td>';

        //             addon +='<input type="text" name="special_price[]" style="display: none;" value="" id="special_price'+count_tr+'">';
        //             addon += '<input type="text" name="special_from[]" value="" style="display: none;" id="special_from'+count_tr+'">';
        //             addon+=' <label for="special_price" id="special_price_display'+count_tr+'"></label>';
        //             addon+=' <a href="#" class="add_special_price" data-special_price="#special_price'+count_tr+'" data-special_form="#special_from'+count_tr+'" data-special_to="#special_to'+count_tr+'" data-special_price_display="#special_price_display'+count_tr+'"> <i class="fa fa-edit"></i>Edit</a>';
        //             addon+='</td>';
                    
        //            addon +='<td><input type="text" name="quantity[]" class="form-control form-control-sm" value=""></td>';

        //            addon +='<td><input type="text" name="mimquantity[]" class="form-control form-control-sm" value=""></td>';

        //            addon +='<td> <input type="text" name="sellersku[]" class="form-control form-control-sm" value=""> </td>';

        //            addon +='<td><input type="text" name="free_items[]" class="form-control form-control-sm" value=""></td>';
        //            addon +='<td><input type="number" name="additional_charge[]" class="form-control form-control-sm" value=""></td>';

        //            addon +='<td><a href="#" class="remove-tr" id="removetr'+count_tr+'" data-remove="removetr'+count_tr+'"><i class="fa fa-trash"></i>Delete</a></td></tr>';

        //            $('.table').append(addon);
        //            onChangeTableRow();
        //            onChangeTableEditButton(); 
        // });
        const addRowDataValue=(attributes)=>{
            count_tr ++;
            var addon = '<tr class="removetr'+count_tr+'"><td><input type="checkbox" value="1" checked></td><td><select name="color[]" id=""  class="form-control form-control-sm">';
                @foreach ($colors as $color)

                    addon += '<option value="{{ $color->id }}">{{ $color->title }}</option>';

                @endforeach

                addon += '</select></td>';

                
                $.each(attributes, function(index, attribute) {
                    addon += '<td><input type="text" name="key[]" hidden value="' + attribute.id + '">';
                    
                    var values = attribute.value.split(',');
                    
                    addon += '<select name="attributes[]" id="" class="form-control" style="width: 100px;">';
                    
                    $.each(values, function(index, value) {
                        addon += '<option value="' + value.trim() + '">' + value.trim() + '</option>';
                    });
                    
                    addon += '</select></td>';
                });

                    addon +='<td><input type="text" value="" class="form-control form-control-sm" name="price[]" required></td>';
                    addon +='<td><input type="text" value="" class="form-control form-control-sm" name="wholesaleprice[]" required style="width:auto" min="1"></td>';
                    
                    addon +='<td>';

                    addon +='<input type="text" name="special_price[]" style="display: none;" value="" id="special_price'+count_tr+'">';
                    addon += '<input type="text" name="special_from[]" value="" style="display: none;" id="special_from'+count_tr+'">';
                    addon+=' <label for="special_price" id="special_price_display'+count_tr+'"></label>';
                    addon+=' <a href="#" class="add_special_price" data-special_price="#special_price'+count_tr+'" data-special_form="#special_from'+count_tr+'" data-special_to="#special_to'+count_tr+'" data-special_price_display="#special_price_display'+count_tr+'"> <i class="fa fa-edit"></i>Edit</a>';
                    addon+='</td>';
                    
                   addon +='<td><input type="text" name="quantity[]" class="form-control form-control-sm" value=""></td>';

                   addon +='<td><input type="text" name="mimquantity[]" class="form-control form-control-sm" value=""></td>';

                   addon +='<td> <input type="text" name="sellersku[]" class="form-control form-control-sm" value=""> </td>';

                   addon +='<td><input type="text" name="free_items[]" class="form-control form-control-sm" value=""></td>';
                   addon +='<td><input type="number" name="additional_charge[]" class="form-control form-control-sm" value=""></td>';

                   addon +='<td><a href="#" class="remove-tr" id="removetr'+count_tr+'" data-remove="removetr'+count_tr+'"><i class="fa fa-trash"></i>Delete</a></td></tr>';

                   $('.table').append(addon);
                   onChangeTableRow();
                   onChangeTableEditButton(); 
        }
        $(document).on('click','#add_new_sku',function(){
            let catIdValue=$('#category_id').val();
            $.ajax({
                url:"{{route('getLatestAttributeData')}}",
                type:"get",
                data:{
                    catIdValue:catIdValue
                },
                success:function(response){
                   addRowDataValue(response.data);
                }
            });
        });

        $('.confirm_special').on('click', function(e){
            e.preventDefault();
            let indexValue=$(this).attr('data-indexValue');
            let fromDate = $(this).closest('.price-modal').find('input[name="from_date"]').val();
            let toDate = $(this).closest('.price-modal').find('input[name="to_date"]').val();
            let sPrice = $(this).closest('.price-modal').find('input[name="price_special"]').val();
            $(`#special_price${indexValue}`).val(sPrice);
            $(`#special_from${indexValue}`).val(fromDate);
            $(`#special_to${indexValue}`).val(toDate);
            $(`#special_price_display${indexValue}`).text(sPrice);
            $(this).removeAttr('data-special_price');
            $(this).removeAttr('data-special_form');
            $(this).removeAttr('data-special_to');
            $(this).removeAttr('data-special_price_display');
            $('.price-modal').css({"display":"none"});
        });
        $('.closeSpecialPrice').on('click',function(){
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
            $('#start-date').val('');
            $('#end-date').val('');
            $('#special_price').val('');
            let indexValue=$(this).attr('data-indexValue');
            let fromData= $(`#special_from${indexValue}`).val();
            let toData= $(`#special_to${indexValue}`).val();
            console.log(toData);
            let spriceData= $(`#special_price${indexValue}`).val();
            if(fromData)
            {
                fromData= new Date(fromData + 'Z').toISOString().split('T')[0];
            }
            if(toData){
                toData = new Date(toData + 'Z').toISOString().split('T')[0];
            }
            $('#start-date').val(fromData);
            $('#end-date').val(toData);
            $('#special_price').val(spriceData);
            $('.confirm_special').attr('data-indexValue', indexValue);
            $('.price-modal').css({"display":"block"});
        });
    }

    $('.holder-remove').on('click', function(e){
        e.preventDefault();
        let key=$(this).attr('data-key');
        let remove_element = $(this).data('holder');
        let color_id = $(this).data('color_id');
        let product_id = $(this).data('product_id');
        let img = $(this).data('img');
        let imageArray=$(`#thumbnail${key}`).val();
        let splitImageArray=imageArray.split(',');
        let currentImagePath=$(this).attr('data-currentImagePath');
        if(confirm("Are You Sure To delete this, would not be revertable")){
            var filteredArray = splitImageArray.filter(function(imagePath) {
                return imagePath != currentImagePath;
            });
            filteredArray=filteredArray.join(',');
            $(`#thumbnail${key}`).val(filteredArray);
            $(remove_element).remove();
            // $.ajax({
            //     url: "{{ route('remove-color-image') }}",
            //     type: 'post',
            //     data: {
            //         color_id: color_id,
            //         product_id: product_id,
            //         img: img,
            //         _token:"{{csrf_token()}}"
            //     },
            //     success: function(response) {
            //         $(remove_element).remove();
            //         var filteredArray = splitImageArray.filter(function(imagePath) {
            //             return imagePath != currentImagePath;
            //         });
            //         filteredArray=filteredArray.join(',');
            //         $(`#thumbnail${key}`).val(filteredArray);
            //         $(remove_element).remove();
                    
            //     },
            //     error: function(response) {
            //         console.log("Something is wrong");
            //     }
            // });
        }
    });
 </script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listeners to all the "Delete" links
        var deleteLinks = document.querySelectorAll('[id^="removetr"]');
        
        deleteLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the link from navigating
                
                var row = this.closest('tr'); // Find the closest <tr> element
                
                if (confirm('Are you sure you want to delete this row?')) {
                    row.remove(); // Remove the row from the DOM
                }
            });
        });
    });
</script>

 @endpush





