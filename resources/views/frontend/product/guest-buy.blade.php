{{-- Changing Adddress With Ajax Call --}}

<script>
    $('.hide-form-storeAddress').click(function(){        
        $('.hide-modal-afterChange').attr({'style', 'back'});
    });
</script>

{{-- Buying Guest With Ajax --}}
<script>
    $(document).ready(function(){   
        $('.guest-buy-now').click(function(e){
            e.preventDefault();
           
            var myNewForm = document.getElementById('direct-checkout-form');                        
            var product_id = myNewForm['product_id'].value;
            if(myNewForm['color']){
                var color_id = myNewForm['color'].value;
            }
            var product_qty= myNewForm['qty'].value;
            var varient_id = myNewForm['varient_id'].value;
            console.log(myNewForm, product_id, product_qty, varient_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('direct.checkoutOrderInsession') }}",
                type: "post",
                data: {
                    product_id: product_id,
                    color_id: color_id ?? '',
                    product_qty: product_qty,
                    varient_id: varient_id,
                    guestOrder:true
                },
                success: function(response) {
                    if (response.login) {
                    window.location.href = "/customer/login";
                    }
                    if(response.min_order_message)
                    {
                        toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                        toastr.error(response.min_order_message);
                        return;
                    }
                    window.location.href = "{{ route('direct.Checkout') }}";
                }
            })     
        })
    })

    

    // direct buy from blade file after log in.
    $('.direct-checkout-btn').click(function(e){
            e.preventDefault();
            @if(!auth()->guard('customer')->user())
                window.location.href = "/customer/login";
            @endif
            var myNewForm = document.getElementById('direct-checkout-form');                        
            var product_id = myNewForm['product_id'].value;
            if(myNewForm['color']){
                var color_id = myNewForm['color'].value;
            }
            var product_qty= myNewForm['qty'].value;
            var varient_id = myNewForm['varient_id'].value;
            console.log(myNewForm, product_id, product_qty, varient_id);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('direct.checkoutOrderInsession') }}",
                type: "post",
                data: {
                    product_id: product_id,
                    color_id: color_id ?? '',
                    product_qty: product_qty,
                    varient_id: varient_id,
                    guestOrder:false
                },
                success: function(response) {
                    if (response.login) {
                    window.location.href = "/customer/login";
                    }
                    if(response.min_order_message)
                    {
                        toastr.options = {
                                "closeButton": true,
                                "progressBar": true
                            }
                        toastr.error(response.min_order_message);
                        return;
                    }
                    window.location.href = "{{ route('direct.Checkout') }}";
                }
            })      
        })


</script>

