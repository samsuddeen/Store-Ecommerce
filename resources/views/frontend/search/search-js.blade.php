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

                    toastr.options =
                    {
                        "closeButton" : true,
                        "progressBar" : true
                    }
                    toastr.error( response.msg);
                   
                    return false;
                }
                toastr.options =
                    {
                        "closeButton" : true,
                        "progressBar" : true
                    }
                toastr.success("Successfully Added To The Cart !! ");
                $('.cart-remove').replaceWith(response);
                @if (auth()->guard('customer')->user() != null)
                    removeProduct();
                    allCartCount();
                @else
                    allGuestCartCount();
                @endif
            }
        });
    });

    $('.color_id,.brand_id,.submit').on('click', function(event) {
        
        var slug = $('#slug').val();
        var sort_by = $('#data_sort').val();
        var paginate = $('#paginate').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
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
            url: "{{ route('search.multiple-filter-data') }}",
            type: "get",
            data: {
                slug: slug,
                sort_by: sort_by,
                paginate: paginate,
                min_price: min_price,
                max_price: max_price,
                color_id: color_ids,
                brand_id: brand_ids
            },
            success: function(response) {
                $("#search-tabContent").replaceWith(response);
                $('.page-link').addClass('sumitTest');
                paginateData();
            }
        });
    });

    $('#data_sort,#paginate').change(function(event) {
        // alert('pp');
        // return false;
        var slug = $('#slug').val();
        var sort_by = $('#data_sort').val();
        var paginate = $('#paginate').val();
        var min_price = $('#min_price').val();
        var max_price = $('#max_price').val();
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
            url: "{{ route('search.multiple-filter-data') }}",
            type: "get",
            data: {
                slug: slug,
                sort_by: sort_by,
                paginate: paginate,
                min_price: min_price,
                max_price: max_price,
                color_id: color_ids,
                brand_id: brand_ids
            },
            success: function(response) {
                $("#search-tabContent").replaceWith(response);
                $('.page-link').addClass('sumitTest');
                paginateData();
            }
        });
    });

    function paginateData()
    {
        $('.sumitTest').click(function(event)
        {
            event.preventDefault();
            var url=$(this).attr('href');
            $.ajax({
                url:url,
                type:"get",
                data:{

                },success:function(response)
                {
                    $("#search-tabContent").replaceWith(response);
                    $('.page-link').addClass('sumitTest');
                    paginateData();
                }
            });
        })
    }
    
</script>
