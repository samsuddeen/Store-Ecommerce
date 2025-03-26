<script>
    $(document).on('click', '.ajax-add-to-cart', function(e) {
        e.preventDefault();
        var totalQty=$('.qtyValue').val();
        var product_id = $(this).attr('data-productId');
        var varientId=$('.varient_id_class').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('directadd-to-cart') }}",
            type: "post",
            data: {
                product_id: product_id,
                qty:totalQty,
                varientId:varientId
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
                    toastr.error(response.msg);
                    return false;
                }
                else
                {
                    toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                    toastr.success("Successfully Added To The Cart !! ");
                }
                $('.cart-remove').replaceWith(response);
                @if (auth()->guard('customer')->user() != null)
                    removeProduct();
                    allCartCount();
                @else
                    $('#side-cart-update').html(response.cartHtml);
                    $('#total_quantity').text(response.total_qty);
                    $('.guesttotal_quantity').text(response.total_qty);
                    activeGuestDeleteCode();
                    // allGuestCartCount();
                    // deleteGuestCart();
                @endif      
            }
        });
    });

    $(document).on('click', '.guestdirectajax-add-to-cart', function(e) {
        e.preventDefault();
        var totalQty=1;
        var product_id = $(this).attr('data-productId');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url: "{{ route('directadd-to-cart') }}",
            type: "post",
            data: {
                product_id: product_id,
                qty:totalQty
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
                    toastr.error(response.msg);
                    return false;
                }
                else
                {
                    toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                    toastr.success("Successfully Added To The Cart !! ");
                }
                $('.cart-remove').replaceWith(response);
                @if (auth()->guard('customer')->user() != null)
                    removeProduct();
                    allCartCount();
                @else
                    $('#side-cart-update').html(response.cartHtml);
                    $('#total_quantity').text(response.total_qty);
                    $('.guesttotal_quantity').text(response.total_qty);
                    activeGuestDeleteCode();
                    // allGuestCartCount();
                    // deleteGuestCart();
                @endif      
            }
        });
    });

    $(document).ready(function() {
        activeGuestDeleteCode();
    });

    function activeGuestDeleteCode(){
        $('.deleteGuestItemData').on('click',function(){
            var itemId=$(this).attr('data-itemId');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{route('directguestdelete-to-cart')}}",
                type:"post",
                data:{
                    itemId:itemId
                },
                success:function(response){
                    if (response.error) {
                        toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                        toastr.error(response.msg);
                        return false;
                    }
                    $('#side-cart-update').html(response.cartHtml);
                    $('#total_quantity').text(response.total_qty);
                    $('.guesttotal_quantity').text(response.total_qty);
                    toastr.options =
                        {
                            "closeButton" : true,
                            "progressBar" : true
                        }
                    toastr.success(response.msg);
                    activeGuestDeleteCode();
                }
            });
        })
    }
</script>