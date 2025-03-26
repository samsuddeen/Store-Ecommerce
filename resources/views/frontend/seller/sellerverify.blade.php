@extends('frontend.layouts.app')
@section('title', 'Seller | Signup')
@section('content')


    <!-- Login Page  -->
    <section class="login-page pt pb" id="form-changes">
        <div class="container">
            <div class="register-wrap">
                <div class="register-left">
                    <ul>
                        <li>
                            <div class="register-icon">
                                <i class="las la-user-check"></i>
                            </div>
                            <div class="register-info">
                                <h3>Trusted by over 100 million Peoples</h3>
                            </div>
                        </li>
                        <li>
                            <div class="register-icon">
                                <i class="las la-credit-card"></i>
                            </div>
                            <div class="register-info">
                                <h3>Fast & Secure Payments with Online</h3>
                            </div>
                        </li>
                        <li>
                            <div class="register-icon">
                                <i class="las la-coins"></i>
                            </div>
                            <div class="register-info">
                                <h3>Saving on every purchase with Glass Pipe</h3>
                            </div>
                        </li>
                        <li>
                            <div class="register-icon">
                                <i class="lab la-dropbox"></i>
                            </div>
                            <div class="register-info">
                                <h3>Manage products, get fare alerts & predictions</h3>
                            </div>
                        </li>
                        <li>
                            <div class="register-icon">
                                <i class="las la-shopping-cart"></i>
                            </div>
                            <div class="register-info">
                                <h3>Best & Safest online shopping from Glass Pipe</h3>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="register-right" >
                    <div class="register-right-head" >
                        <h3>Create Your Account</h3>
                        <a href="{{ route('sellerLogin') }}">Login</a>
                    </div>
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="text-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <form action="javascript:;" method="post" id="sellerverifyToken">
                        @csrf
                        <div class="form-group">
                            <div class="input_field">
                                <input type="tel" value="{{ old('zip') }}" name="phone" placeholder="Enter Your Phone Number...." class="form-control"
                                    id="phone">
                            </div>
                            <span class="text-danger" id="error_msg"></span>
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="flexCheckDefault" required>
                                <label class="form-check-label" for="flexCheckDefault">
                                    By logging in, I understand & agree to terms of use and privacy policy
                                </label>
                                <span class="text-danger" style="font-size: 10px;text-align:start" id="error_msg1"></span>
                            </div>
                        </div>
                        <div class="form-btn">
                            <button type="submit" name="submit" class="btns" id="verifytoken">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Page End  -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" id="otp-modal">

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('#verifytoken').on('click', function() {
            var form = document.getElementById('sellerverifyToken');
            if (!$('#phone').val()) {
                $('#error_msg').text('Plz Insert Phone Number First');
                $('#error_msg1').empty();
                return false;
            }
            if (!$('#flexCheckDefault').is(':checked')) {
                $('#error_msg1').text('required');
                $('#error_msg').empty();
                return false;
            }
            $('#error_msg').empty();
            $('#error_msg1').empty();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: "{{ route('seller-get-otp') }}",
                type: "post",
                data: {
                    phone: form['phone'].value
                },
                success: function(response) {
                    if (response.error) {
                        $('#error_msg').text(response.msg);
                        return false;
                    }
                    
                    // $('#form-changes').replaceWith(response);
                    $('#otp-modal').replaceWith(response);
                    $('#exampleModal').modal('show');
                    return true;
                }
            });
        });


        $(document).on('click', '#otp_form', function() {
            var otp_form_value = document.getElementById('verify_otp_with_phone');
            if (!$('#otp_value').val()) {
                // $('#error_msg').text('Plz Insert Phone Number First');
                toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        }
                        toastr.error('Plz Enter Otp First');
                        return false;
                return false;
            }
            $.ajax({
                url: "{{ route('verify_phone_otp') }}",
                type: "post",
                data: {
                    otp: otp_form_value['otp_value'].value,
                    phone: otp_form_value['otp_phone'].value,
                },
                success: function(response) {
                    if (response.error) {
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true
                        }
                        toastr.error(response.msg);
                        return false;
                    }
                    $('#exampleModal').modal('hide');
                    $('#form-changes').replaceWith(response);
                    validateRuleData();
                    toastr.options = {
                        "closeButton": true,
                        "progressBar": true
                    }
                    toastr.success("Otp Verified Successfully ");

                }
            });
        });


        $(document).on('click', '#signUpBtn', function() {
            var seller_data = document.getElementById('signUpForm');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: "{{ route('sellerSignup') }}",
                type: "post",
                data: {
                    name: seller_data['name'].value,
                    email: seller_data['email'].value,
                    otp: seller_data['verified_otp'].value,
                    phone: seller_data['verified_phone'].value,
                    password: seller_data['password'].value,
                    password_confirmation: seller_data['password_confirmation'].value,
                    company_name: seller_data['company_name'].value,
                },
                success: function(response) {
                    if (response.validate) {
                        error_html = '';
                        $.each(response.errors, function(index, value) {
                            $('#' + index).text(value);
                        });
                        return false;
                    }
                    if (response.phone) {
                        $('#phone').text(response.msg);
                        $('#name').text('');
                        $('#email').text('');
                        $('#password').text('');
                        $('#otp').text('');
                        $('#company_name').text('');
                        return false;
                    }
                    if (response.otp) {
                        $('#otp').text(response.msg);
                        $('#name').text('');
                        $('#email').text('');
                        $('#password').text('');
                        $('#phone').text('');
                        $('#company_name').text('');
                        return false;
                    }
                    if (response.error) {
                        swal({
                            title: "Sorry!!",
                            text: response.msg,
                            icon: "error",
                        });
                    }
                    url = response.url;
                    window.location.href = url;
                }
            });
        })
    </script>
    <script>
       
       function validateRuleData()
       {
        $('.sellerEmailField').on('keyup',function()
            {
                var emailValue = $(this).val();
                
                if(emailValue !=null)
                {
                    var isValid = isValidEmail(emailValue);

                if (isValid) {
                   
                    $('.sellerEmailFieldError').empty();
                } else {
                    $('.sellerEmailFieldError').text('Email Must Be A Valid Email');
                   
                }
                }
            });

            $('.sellerPasswordField, .sellerPasswordConfirmationField').on('input', function() {
                var password = $('.sellerPasswordField').val();
                var passwordConfirmation = $('.sellerPasswordConfirmationField').val();

                if (password === passwordConfirmation) {
                    $('.sellerPasswordFieldError').empty();
                } else {
                    $('.sellerPasswordFieldError').text('Password And Password Confirmation Doesnot Match');
                }
            });

            $('.sellerPhoneField').keyup(function() {
                var phoneNumber = $(this).val();
                var phonePattern = /^\d{3}\d{3}\d{4}$/; // Example pattern: XXX-XXX-XXXX

                if (phonePattern.test(phoneNumber)) {
                    $('.sellerPhoneFieldError').empty();
                } else {
                    $('.sellerPhoneFieldError').text('Phone Must Be A Valid Number');
                }
            });

            var signupForm = document.getElementById('signUpForm');
        signupForm.addEventListener('submit', function(event) {
            if ($('.sellerEmailField').is(':visible') || $('.sellerPasswordField').is(':visible') || $('.sellerPhoneField').is(':visible') || $('.sellerPasswordConfirmationField').is(':visible')) {
                event.preventDefault();
            }
        });
       }

        function isValidEmail(email) {
            // Regular expression to validate email format
            var emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
            return emailRegex.test(email);
        }
    </script>

    <script>
        
    </script>
@endpush
