<script>
    $(document).ready(function() {
        $(".ajax-add-to-cart").on('click', function(e) {
            e.preventDefault();
            let product_id = $(this).data('product_id');
            $.ajax({
                url: "{{ route('addSingleProductToCart') }}",
                type: 'post',
                data: {
                    product_id: product_id,
                },
                // dataType: 'JSON',
                success: function(response) {
                    if (response.error) {
                        allCartData();
                        removeProduct();
                        allCartCount();
                        alert('out of stock');
                    } else if (response.url) {
                        window.location = "{{ route('Clogin') }}";
                    }
                    $('.cart-remove').replaceWith(response);
                    removeProduct();
                    allCartCount();
                },

                error: function(response) {

                }
            });
        })

    });

    function singleProduct() {
        $(".ajax-add-to-cart").on('click', function(e) {
            e.preventDefault();
            let product_id = $(this).data('product_id');
            $.ajax({
                url: "{{ route('addSingleProductToCart') }}",
                type: 'post',
                data: {
                    product_id: product_id,
                },
                // dataType: 'JSON',
                success: function(response) {
                    if (response.error) {
                        allCartData();
                        removeProduct();
                        allCartCount();
                        alert('out of stock');
                    }
                    $('.cart-remove').replaceWith(response);
                    removeProduct();
                    allCartCount();
                },
                error: function(response) {

                }
            });
        });
    }
</script>
