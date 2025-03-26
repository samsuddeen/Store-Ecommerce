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
                        <input type="number" value="{{ $stock->price }}"
                            class="form-control form-control-sm" name="price[]" required style="width:auto">
                    </td>
                    <td>
                       <input type="number" value="{{ $stock->wholesaleprice }}"
                           class="form-control form-control-sm" name="wholesaleprice[]" required style="width:auto" min="1">
                   </td>
                    <td>
                        <input type="text" name="special_price[]" style="display: none;"
                            value="{{ $stock->special_price }}" id="special_price{{$index}}">
                            
                        <input type="text" name="special_from[]" value="{{ $stock->special_form }}"
                            style="display: none;" id="special_from{{$index}}">
                        <input type="text" name="special_to[]" value="{{ $stock->special_to }}"
                            style="display: none;" id="special_to{{$index}}">
                        <label for="special_price" id="special_price_display{{$index}}">{{ $stock->special_price }}</label>
                        <p>{{calculatePercent($stock->price,$stock->special_price)}}%</p>
                        <a href="#" class="add_special_price" data-orgprice="{{$stock->price}}" data-special_price="#special_price{{$index}}" data-special_form="#special_from{{$index}}" data-special_to="#special_to{{$index}}" data-special_price_display="#special_price_display{{$index}}"> <i class="fa fa-edit"></i>Edit</a>
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