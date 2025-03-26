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
            <tr class="removetr">
                <td>
                    <input type="checkbox" value="1" checked>
                </td>
                <td>
                    <select name="color[]" id="" class="form-control form-control-sm">
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}">
                                {{ $color->title }}
                            </option>
                        @endforeach
                    </select>
                </td>
                @foreach ($attributes as $attribute)
                    <td>
                        <input type="text" name="key[]" hidden value="{{ $attribute->id }}">
                        @php
                            $values = explode(',', $attribute->value);
                        @endphp
                        <select name="attributes[]" id="" class="form-control" style="width: 100px;">
                            @foreach ($values as $value)
                                <option value="{{ trim($value) }}" >
                                    {{ trim($value) }}</option>
                            @endforeach
                        </select>
                    </td>
                @endforeach
                <td>
                    <input type="number" value=""
                        class="form-control form-control-sm" name="price[]" required style="width:auto">
                </td>
                <td>
                   <input type="number" value=""
                       class="form-control form-control-sm" name="wholesaleprice[]" required style="width:auto" min="1">
               </td>
                <td>
                    <input type="text" name="special_price[]" style="display: none;"
                        value="" id="special_price">
                        
                    <input type="text" name="special_from[]" value="{{ @$stock->special_form }}"
                        style="display: none;" id="special_from">
                    <input type="text" name="special_to[]" value="{{ @$stock->special_to }}"
                        style="display: none;" id="special_to">
                    <label for="special_price" id="special_price_display">{{ @$stock->special_price }}</label>
                    <p>%</p>
                    <a href="#" class="add_special_price" data-orgprice="{{@$stock->price}}" data-special_price="#special_price" data-special_form="#special_from" data-special_to="#special_to" data-special_price_display="#special_price_display"> <i class="fa fa-edit"></i>Edit</a>
                </td>
                <td>
                    <input type="text" name="quantity[]" class="form-control form-control-sm"
                        value="{{ @$stock->quantity }}">
                </td>
                <td>
                   <input type="text" name="mimquantity[]" class="form-control form-control-sm"
                       value="{{ @$stock->mimquantity }}">
               </td>
                <td>
                    <input type="text" name="sellersku[]" class="form-control form-control-sm"
                        value="{{ @$stock->sellersku }}">
                </td>
                <td>
                    <input type="text" name="free_items[]" class="form-control form-control-sm"
                        value="{{ @$stock->free_items }}">
                </td>
                <td>
                   <input type="number" name="additional_charge[]" class="form-control form-control-sm"
                       value="{{ @$stock->additional_charge }}">
               </td>
                <td>
                    <a href="#" id="removetr"><i class="fa fa-trash"></i>Delete</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>