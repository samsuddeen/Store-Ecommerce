<script>
    changeToAttribute();
    let color = "{{ $final_data['color'] }}"
    let new_keys = [];
    let values = [];
    let keys = {};
    let check = false;
    let selected = {
        'color': color,
        'product_id': "{{ $product->id }}",
        'type': '',
    }
    $('.product-color').on('change', function(e) {
        e.preventDefault();
        var color = $(this).find(":selected").data('color');
        selected.color = color;
        selected.type = 'change_color';
        check = true;
        changeAttribute();
    });

    function changeToAttribute() {
        $('.attribute').on('change', function(e) {
            e.preventDefault();
            let price = $(this).find(':selected').data('price');
            let sprice = $(this).find(':selected').data('sprice');
            let stock_qty = $(this).find(':selected').data('stockquantity');
            let varient_id = $(this).find(':selected').data('varientid');
            let mimquantity = $(this).find(':selected').data('mimquantity');
            let colorCode = $(this).find(':selected').data('colorcode');
            $('.varient_id_class').val(varient_id);
            let key_id = $(this).data('key_id');
            let final_value = $(this).val();
            let data_availibility = $(this).find(':selected').data('availability');
            data_availibility = JSON.parse(data_availibility);
            let first_avaiable = data_availibility[key_id];
            let index = 0;
            first_avaiable.forEach((element, i) => {
                if (element.value == final_value) {
                    index = i;
                }
            });
            console.log('sumit',final_value);
            if (check) {
                keys.forEach((element, i) => {
                    $('#key-id-' + element.id).val(data_availibility[element.id][index]['value']);
                });
            } else {
                @foreach ($final_data['keys'] as $key)
                    $('#key-id-' + {{ $key['id'] }}).val(data_availibility[{{ $key['id'] }}][index][
                        'value'
                    ]);
                @endforeach
            }

            if (sprice) {
                $('#stock_price').html('<del class="original_price">Rs.' + price +
                    '</del><span class="special_price">Rs.' + sprice + '</span>');
            } else {
                $('#stock_price').html('<span class="special_price">Rs. ' + price + '</span>');
            }

            $('.item_show').text(stock_qty);

            if(userDataValue=='1')
            {
                $('.qtyValue').val(mimquantity ?? 1);
                $('#wholesellerminQty').text(mimquantity ?? 1);
            }
            else
            {
                $('.qtyValue').val(1);
            }
           
            if (stock_qty <= 0) {
                $('.stock_out').text('/Out Of Stock');
            } else {
                $('.stock_out').text('');
            }
            $('#color_attribute_selected').css('background-color', colorCode);
            $('.stock_qty').val(item_show);
        })
    }

    function changeAttribute() {
        $.ajax({
            url: "/product/attribute",
            type: "get",
            contentType: false,
            processData: false,
            dataType: 'json',
            data: $.param(selected),
            success: function(response) {
                let image_string = "";
                $('#stock_price').html(response.stock_wise_price)
                $('#ul-relate-to-attribute').html(response.html_string);

                let all_image = '';
                if (response.color_image.length > 0) {
                    $.each(response.color_image, function(index, value) {
                        if (index == 0) {
                            image_string +=
                                '<li class="flex-active-slide" style="width: 80px; margin-right: 10px; float: left; display: block;"><img src="' +
                                value + '" alt="images"></li>';
                            all_image =
                                '<li class="flex-active-slide" style="width: 363.656px; margin-right: 0px; float: left; display: block;"><img src="' +
                                value + '" alt="images" class="images"></li>';
                        } else {
                            image_string += '<li class="flex-active-slide"><img src="' + value +
                                '" alt="images" ></li>';
                        }
                    });
                }
                let stock_qty = $(this).find(':selected').data('stockquantity');
                $('.slides').html(all_image);
                $('#my_slide').html(image_string);
                $('.item_show').text(response.stock_qty);
                if (response.stock_qty <= 0) {
                    $('.stock_out').text('/Out Of Stock');
                } else {
                    $('.stock_out').text('');
                }
                $('.qtyValue').val(1);
                $('.stock_qty').val(response.stock_qty);

                $('.varient_id_class').val(response.stock_varient_id);
                // reloadCoratia();
                if (response.offer_price != null) {
                    $('#price-relate-to-attribute').val(response.offer_price);
                } else {
                    $('#price-relate-to-attribute').val(response.price);
                }
                keys = response.keys;
                changeToAttribute();
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
</script>
