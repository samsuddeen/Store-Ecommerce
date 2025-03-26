<script>
    $(document).ready(function() {
        $('.checkout-all').click(function() {
            var form = document.getElementById('myForm');
            var name = form['name'].value;
            var phone = form['phone'].value;
            var email = form['email'].value;
            var province = form['province'].value;
            var district = form['district'].value;
            var area = form['area'].value;
            var zip = form['zip'].value;
            var additional_address = form['additional_address'].value;
            var pid = form['pid_info'].value;
            if (!(name)) {
                $('.full_name_error').replaceWith(
                    '<div class="full_name_error"> <span class="text-danger">Name Field is required</span> </div>'
                );
            } else if (!(phone)) {
                $('.phone_num_error').replaceWith(
                    '<div class="phone_num_error"> <span class="text-danger">Phone Field is required</span> </div>'
                );
            } else if (!(email)) {
                $('.email_error').replaceWith(
                    '<div class="email_error"> <span class="text-danger">Email Field is required</span> </div>'
                );
            } else if (!(province)) {
                $('.province_error').replaceWith(
                    '<div class="province_error"> <span class="text-danger">PRovince Field is required</span> </div>'
                );
            } else if (!(district)) {
                $('.district_error').replaceWith(
                    '<div class="district_error"> <span class="text-danger">District Field is required</span> </div>'
                );
            } else if (!(area)) {
                $('.area_error').replaceWith(
                    '<div class="area_error"> <span class="text-danger">Area Field is required</span> </div>'
                );
            } else if (!(zip)) {
                $('.zip_error').replaceWith(
                    '<div class="zip_error"> <span class="text-danger">Zip Field is required</span> </div>'
                );
            } else if (!(additional_address)) {
                $('.additional_address_error').replaceWith(
                    '<div class="additional_address_error"> <span class="text-danger">Additional Address Field is required</span> </div>'
                );
            } else {
                let data = {
                    name: name,
                    phone: phone,
                    email: email,
                    province: province,
                    district: district,
                    area: area,
                    zip: zip,
                    pid: pid,
                    additional_address: additional_address,
                }

                var payment = $("input[name = 'payment']:checked").val();
                if (payment == 'khalti') {
                    $('#myForm').submit();
                    // guestPay(data);
                } else if (payment == 'esewa') {
                    esewaOrderInfoGuest();
                    // $('#exampleModal-esewa').modal('show');              
                    $('#esewaForm').submit();
                } else {
                    $('#myForm').submit();
                }
            }
        });

        // function guestPay(data) {
        //     var refId = "{{strtoupper(Str::random(6) . rand(100, 1000))}}";
        //     // var amount = $('#payAmount').val();
        //     var total = 20;
        //     var config = {
        //         // replace the publicKey with yours
        //         "publicKey": "test_public_key_44b86e960bc84f0c9376abfd4dd1e13f",
        //         "productIdentity": refId,
        //         "productName": "All-product-from-guest-cart",
        //         "productUrl": "http://127.0.0.1:8000/geust-all-checkout",
        //         "paymentPreference": [
        //             "KHALTI",
        //             "EBANKING",
        //             "MOBILE_BANKING",
        //             "CONNECT_IPS",
        //             "SCT",
        //         ],
        //         "eventHandler": {
        //             onSuccess(payload) {

        //                 if (payload) {
        //                     $.ajaxSetup({
        //                         headers: {
        //                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //                         }
        //                     });

        //                     $.ajax({
        //                         url: "{{ route('guest.khaltiAllProduct') }}",
        //                         type: "post",
        //                         data: {
        //                             token: payload.token,
        //                             payload: payload,
        //                             guest_info: data,
        //                             refId:refId
        //                         },

        //                         success: function(response) {
        //                             if(response.error)
        //                             {
        //                                 toastr.options =
        //                                     {
        //                                         "closeButton" : true,
        //                                         "progressBar" : true
        //                                     }
        //                                 toastr.error(response.msg);
        //                                 return false;
        //                             }
        //                             toastr.options =
        //                                 {
        //                                     "closeButton" : true,
        //                                     "progressBar" : true
        //                                 }
        //                             toastr.success(response.msg);
        //                             window.location.href = response.url;
        //                         },
        //                         error() {},
        //                         onClose() {
        //                             console.log('widget is closing');
        //                         }
        //                     })
        //                 }


        //             },
        //             onError(error) {
        //                 console.log(error);
        //             },
        //             onClose() {
        //                 console.log('widget is closing');
        //             }
        //         }
        //     };

        //     var checkout = new KhaltiCheckout(config);
        //     // minimum transaction amount must be 10, i.e 1000 in paisa.
        //     checkout.show({
        //         amount: total * 100
        //     });
        // }

        var strRandom = function(length) {
            var result = '';
            var characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            return result;
        }

    });
</script>
