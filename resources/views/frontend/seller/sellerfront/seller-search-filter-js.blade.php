<script>
    var color_ids = [];
    var brand_ids = [];
    $(document).on('click', '.ajax-add-to-cart', function() {
        var product_id = $(this).data('product_id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('tag-to-cart') }}",
            type: "post",
            data: {
                product_id: product_id
            },
            success: function(response) {
                if (response.login) {
                    window.location.href = "/customer/login";
                }
                if (response.error) {
                    swal({
                        title: "Sorry!",
                        text: response.msg,
                        icon: "error",
                    });
                    return false;
                }
                swal({
                    title: "Thank You!",
                    text: "Successfully Added To The Cart !! ",
                    icon: "success",
                });
                $('.cart-remove').replaceWith(response);
                removeProduct();
                allCartCount();
            }
        });
    });

    $('.color_id,.brand_id,.submit').on('click', function(event) {
        var slug = $('#slug').val();
        var seller_id = $('#seller_id').val();
        var sort_by = $('#data_sort').val();
        var paginate = $('#paginate').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        var search_field=$('#search_field').val();
        if (parseInt(min_price) > parseInt(max_price)) {
            alert('Min Price Must Not Be Greater Than Max Price');
            return false;
        }
        let {
            value,
            id,
            checked
        } = event.target;
        if (id == "color") {
            if (checked) {
                color_ids.push(value);
            } else {
                color_ids = color_ids.filter(function(data) {
                    return data != value;
                });
            }
        }
        if (id == "brand") {
            if (checked) {
                brand_ids.push(value);
            } else {
                brand_ids = brand_ids.filter(function(data) {
                    return data != value;
                });
            }
        }
        $.ajax({
            url: "{{ route('seller-search-multiple-filter-data') }}",
            type: "get",
            data: {
                slug: slug,
                search_field:search_field,
                seller_id: seller_id,
                sort_by: sort_by,
                paginate: paginate,
                min_price: min_price,
                max_price: max_price,
                color_id: color_ids,
                brand_id: brand_ids
            },
            success: function(response) {
                if(response.error)
                {
                    alert(response.msg);
                    return false;
                }
                $("#seller-search-nav-tabContent").replaceWith(response);
            }
        });
    });

    $('#data_sort,#paginate').change(function(event) {
        
        var slug = $('#slug').val();
        var seller_id = $('#seller_id').val();
        var sort_by = $('#data_sort').val();
        var paginate = $('#paginate').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
        var search_field=$('#search_field').val();
        if (parseInt(min_price) > parseInt(max_price)) {
            alert('Min Price Must Not Be Greater Than Max Price');
            return false;
        }
        let {
            value,
            id,
            checked
        } = event.target;
        if (id == "color") {
            if (checked) {
                color_ids.push(value);
            } else {
                color_ids = color_ids.filter(function(data) {
                    return data != value;
                });
            }
        }
        if (id == "brand") {
            if (checked) {
                brand_ids.push(value);
            } else {
                brand_ids = brand_ids.filter(function(data) {
                    return data != value;
                });
            }
        }

        $.ajax({
            url: "{{ route('seller-search-multiple-filter-data') }}",
            type: "get",
            data: {
                slug: slug,
                search_field:search_field,
                seller_id: seller_id,
                sort_by: sort_by,
                paginate: paginate,
                min_price: min_price,
                max_price: max_price,
                color_id: color_ids,
                brand_id: brand_ids
            },
            success: function(response) {
                if(response.error)
                {
                    alert(response.msg);
                    return false;
                }
                $("#seller-search-nav-tabContent").replaceWith(response);
            }
        });
    });
</script>
    

<script>   
    $(document).on('click', '.sessionGuest-add-to-cart', function() {
        var product_id = $(this).data('product_id');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('addto.guest.cartFromCategory') }}",
            type: "post",
            data: {
                product_id: product_id
            },
            success: function(response) {
                if (response.login) {
                    window.location.href = "/customer/login";
                }
                if (response.error) {
                    swal({
                        title: "Sorry!",
                        text: response.msg,
                        icon: "error",
                    });
                    return false;
                }
                swal({
                    title: "Thank You!",
                    text: "Successfully Added To The Cart !! ",
                    icon: "success",
                });
                $('.cart-remove').replaceWith(response);
                removeProduct();
                allCartCount();

                reloadSite()

            }
        });
    });

    public function reloadsite()
    {
        window.addEventListener("pageshow", function(event) {
            var historyTraversal = event.persisted ||
                (typeof window.performance != "undefined" &&
                    window.performance.navigation.type === 2);
            if (historyTraversal) {
                window.location.reload();
            }
        });
    }
</script>
