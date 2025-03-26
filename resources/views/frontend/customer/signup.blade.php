@extends('frontend.layouts.app')
@section('title', 'Customer | Signup')


@section('content')


    <!-- Sign Up Page  -->
    <section class="login-page pt pb">
        <div class="container">
            <div class="register-wrap">
                {{-- <div class="register-left">
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
                                <h3>Saving on every purchase with Glass Pipe Nepal</h3>
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
                                <h3>Best & Safest online shopping from Glass Pipe Nepal</h3>
                            </div>
                        </li>
                    </ul>
                </div> --}}
                <div class="register-right">
     
                    <div class="register-right-head">
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-retail-tab" data-bs-toggle="tab" data-bs-target="#nav-retail" type="button" role="tab" aria-controls="nav-retail" aria-selected="false">Retail Register</button>
                                <button class="nav-link" id="nav-wholesale-tab" data-bs-toggle="tab" data-bs-target="#nav-wholesale" type="button" role="tab" aria-controls="nav-wholesale" aria-selected="true"> Customer Register</button>
                            </div>
                          </nav>
                        <a href="{{ route('Clogin') }}">Login</a>
                    </div>
                            
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade" id="nav-wholesale" role="tabpanel" aria-labelledby="nav-wholesale-tab">

                            @if (request()->session('error'))
                                <span class="text-danger"> {{ request()->session()->get('error') }} </span>
                            @endif
                            <form action="{{ route('customerSignups') }}" method="post" enctype="multipart/form-data" id="signupForm">
                                @csrf
                                <div class="form-group">
                                    <input type="text" value="{{ old('name') }}" name="name" class="form-control nameJV"
                                        id="exampleFormControlInput1" placeholder="Name" required>
                                    <span id="nameJValidate" hidden class="text-danger"></span>
                                </div>
                                <div class="form-group">
                                    <input type="text" value="{{ old('phone') }}" name="phone" class="form-control phoneJV"
                                        id="exampleFormControlInput1" placeholder="Phone Number">
                                    <span id="phoneJValidate" hidden class="text-danger"
                                        style="font-size: 12px;font-weight: 300;margin: 1px;"></span>
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="email" value="{{ old('email') }}" name="email" class="form-control nameField"
                                        id="exampleFormControlInput1" placeholder="name@example.com">
                                    <span id="emailJValidate" hidden class="text-danger "
                                        style="font-size: 12px;font-weight: 300;margin: 1px;"></span>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
        
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control passwordJ"
                                        id="exampleFormControlInput1" placeholder="Password">
        
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password_confirmation" class="form-control passwordConJ"
                                        id="exampleFormControlInput1" placeholder="Confirm Password">
                                    <span id="passwordJValidate" hidden class="text-danger"
                                        style="font-size: 12px;font-weight: 300;margin: 1px;"></span>
                                    @error('password_confirmation')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
        
    
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            By continuing, you agree to Mystore E-shopping
                                            <a href="{{ route('general', 'terms-and-conditions') }}">Conditions of Use</a> and <a
                                                href="{{ route('general', 'return-policy') }}">Privacy Notice.</a>
                                        </label>
                                    </div>
                                </div>
                                <div class="g-recaptcha" data-sitekey="6LcFXd0qAAAAADALqw7XzghCg1A3n5rQbmaMrFDI"></div>
                                @error('g-recaptcha-response')
                                    <span class="text-danger"> The reCAPTCHA was invalid. Go back and try it again.
                                    </span>
                                @enderror
                                <input type="text" name="data" value={{ $collect }} hidden>
                                <div class="form-btn">
                                    <button type="submit" class="btns">Agree & Register</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade show active" id="nav-retail" role="tabpanel" aria-labelledby="nav-retail-tab">
                            @if (request()->session('error'))
                            <span class="text-danger"> {{ request()->session()->get('error') }} </span>
                        @endif
                        <form action="{{ route('customerSignups') }}" method="post" enctype="multipart/form-data" id="signupForm">
                            @csrf
                            <div class="form-group">
                                <input type="text" value="{{ old('bussiness_name') }}" name="bussiness_name" class="form-control bussiness_nameJV"
                                    id="exampleFormControlInput1" placeholder="Bussiness Name" required>
                                <span id="bussiness_nameJValidate" hidden class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" value="{{ old('address') }}" name="address" class="form-control addressJV"
                                    id="exampleFormControlInput1" placeholder="Bussiness Address" required>
                                <span id="addressJValidate" hidden class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" value="{{ old('pan') }}" name="pan" class="form-control panJV"
                                    id="exampleFormControlInput1" placeholder="Bussiness Pan" required>
                                <span id="panJValidate" hidden class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <input type="text" value="{{ old('name') }}" name="name" class="form-control nameJV"
                                id="exampleFormControlInput1" placeholder="Contact Name" required>
                            <span id="nameJValidate" hidden class="text-danger"></span>
                            </div>
                            <div class="form-group" id="wholesellercountry">
                                <select name="country" id="country" class="form-control countryJV" required>
                                    <option value="">----Select Country-----</option>
                                    @foreach ($allCountries as $country)
                                        <option value="{{$country->id}}" {{$country->id=='92' ? 'selected':''}}>{{$country->name}}</option>
                                    @endforeach
                                </select>
                                <span id="countryJValidate" hidden class="text-danger"></span>
                                @error('country')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group d-flex">
                                <button class="dropdown-toggle changeCountryCode d-flex align-items-center" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: 1px solid #e5e5e5; border-radius: 4px; background-color: transparent;">
                                    <img src=""  alt="Image 1" id="selectedDataImage" style="width: 30px;">
                                    <span id="spanCode"></span>
                                </button>
                                <input type="text" value="{{ old('phone') }}" name="phone" class="form-control phoneJV"
                                    id="exampleFormControlInput1" placeholder="Phone Number">
                                <span id="phoneJValidate" hidden class="text-danger"
                                    style="font-size: 12px;font-weight: 300;margin: 1px;"></span>
                                @error('phone')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="email" value="{{ old('email') }}" name="email" class="form-control nameField"
                                    id="exampleFormControlInput1" placeholder="name@example.com">
                                <span id="emailJValidate" hidden class="text-danger "
                                    style="font-size: 12px;font-weight: 300;margin: 1px;"></span>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
    
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control passwordJWV"
                                    id="exampleFormControlInput1" placeholder="Password">
    
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input type="password" name="password_confirmation" class="form-control passwordConJWV"
                                    id="exampleFormControlInput1" placeholder="Confirm Password">
                                <span id="passwordJValidateWV" hidden class="text-danger"
                                    style="font-size: 12px;font-weight: 300;margin: 1px;"></span>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
    
                            <div class="form-group mb-0">
                                <div class="form-check mt-0" style="min-height: 0">
                                    <input class="form-check-input" checked hidden type="checkbox" value="1" id="flexCheckDefault"
                                        name="wholeseller">
                                    <label class="form-check-label d-none" for="flexCheckDefault">
                                        Are you a wholeseller? If yes, then please tick the checkbox to the left.
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                                    <label class="form-check-label" for="flexCheckDefault">
                                        By continuing, you agree to Mystore E-shopping
                                        <a href="{{ route('general', 'terms-and-conditions') }}">Conditions of Use</a> and <a
                                            href="{{ route('general', 'return-policy') }}">Privacy Notice.</a>
                                    </label>
                                </div>
                            </div>
                            <div class="g-recaptcha" data-sitekey="6Lc3ZuIpAAAAAK7jtHdENSR7UIMh3jKYNebOHsLv"></div>
                            @error('g-recaptcha-response')
                                <span class="text-danger"> The reCAPTCHA was invalid. Go back and try it again.
                                </span>
                            @enderror
                            <input type="text" name="data" value={{ $collect }} hidden>
                            <div class="form-btn">
                                <button type="submit" class="btns">Agree & Register</button>
                            </div>
                        </form>
                        </div>
                      </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Sign Up Page End  -->


@endsection

@push('script')
    <script>
        const loadCountryData=()=>{
            $.ajax({
                url:"{{route('getallcountrydata')}}",
                type:"get",
                data:{

                },
                success:function(response){
                    $('#spanCode').text(response.data.country_zip);
                    $('#selectedDataImage').attr('src',response.data.flags);
                }
            });
        }
        loadCountryData();
         $(document).on('change','.countryJV',function(){
            var country_id=$(this).val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"{{route('getwholesellercountrydata')}}",
                type:"post",
                data:{
                    country_id:country_id
                },
                success:function(response){
                    if(response.error){
                        return false;
                    }
                    $('#spanCode').text(response.spanCode);
                    $('#selectedDataImage').attr('src',response.selectedDataImage);
                }
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.province').on('change', function() {
                var province_id = $('.province').val();
                $.ajax({
                    url: "{{ route('getDistrict') }}",
                    type: 'post',
                    data: {
                        province_id: province_id,
                    },
                    // dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        $('.district').empty();
                        $('.district').append('<option >Select District</option>');
                        $.each(response.districts, function(key, value) {
                            $('.district').append('<option value="' + value.dist_id +
                                '">' + value.np_name + '</option>');
                        })
                    },
                    error: function(response) {}
                });
            })
        })
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        $(document).ready(function() {
            $('.district').on('change', function() {
                var district_id = $('.district').val();
                $.ajax({
                    url: "{{ route('getLocal') }}",
                    type: 'post',
                    data: {
                        district_id: district_id,
                    },
                    // dataType: 'JSON',
                    success: function(response) {
                        console.log(response);
                        $('.local').empty();
                        $('.local').append('<option >Select Local</option>');
                        $.each(response.locals, function(key, value) {
                            $('.local').append('<option value="' + value.id + '">' +
                                value.local_name + '</option>');
                        })
                    },
                    error: function(response) {}
                });
            })
        })
    </script>

    <script>
        $(document).ready(function() {
            $('.nameField').on('keyup', function() {
                var emailValue = $(this).val();

                if (emailValue != null) {
                    var isValid = isValidEmail(emailValue);

                    if (isValid) {
                        $('#emailJValidate').attr('hidden', true);
                        $('#emailJValidate').empty();
                    } else {
                        $('#emailJValidate').text('Email Must Be A Valid Email');
                        $('#emailJValidate').removeAttr('hidden');
                    }
                }
            });

            $('.passwordJ, .passwordConJ').on('input', function() {
                var password = $('.passwordJ').val();
                var passwordConfirmation = $('.passwordConJ').val();

                if (password === passwordConfirmation) {
                    $('#passwordJValidate').attr('hidden', true);
                    $('#passwordJValidate').empty();
                } else {
                    $('#passwordJValidate').text('Password And Password Confirmation Doesnot Match');
                    $('#passwordJValidate').removeAttr('hidden');
                }
            });

            $('.passwordJWV, .passwordConJWV').on('input', function() {
               
                var passwordW = $('.passwordJWV').val();
                var passwordConfirmationW = $('.passwordConJWV').val();
                if (passwordW === passwordConfirmationW) {
                    $('#passwordJValidateWV').attr('hidden', true);
                    $('#passwordJValidateWV').empty();
                } else {
                    $('#passwordJValidateWV').text('Password And Password Confirmation Doesnot Match');
                    $('#passwordJValidateWV').removeAttr('hidden');
                }
            });

            $('.phoneJV').keyup(function() {
                var phoneNumber = $(this).val();
                var phonePattern = /^\d{3}\d{3}\d{4}$/; // Example pattern: XXX-XXX-XXXX

                if (phonePattern.test(phoneNumber)) {
                    $('#phoneJValidate').attr('hidden', true);
                    $('#phoneJValidate').empty();
                } else {
                    $('#phoneJValidate').text('Phone Must Be A Valid Number');
                    $('#phoneJValidate').removeAttr('hidden');
                }
            });


        });

        function isValidEmail(email) {
            // Regular expression to validate email format
            var emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
            return emailRegex.test(email);
        }
    </script>

    <script>
        var signupForm = document.getElementById('signupForm');
        signupForm.addEventListener('submit', function(event) {
            if ($('#emailJValidate').is(':visible') || $('#passwordJValidate').is(':visible') || $('#nameJValidate')
                .is(':visible') || $('#phoneJValidate').is(':visible') || $('#recaptchaError').is(':visible')) {
                event.preventDefault();
            }
        });
    </script>
@endpush
