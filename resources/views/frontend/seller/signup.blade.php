@extends('frontend.layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Signup')
@section('content')
    <section id="signup_wrapper" style="background: url({{ asset('frontend/images/login.jpg') }});">
        <div class="container">
            <div class="login_inner_wrapper signup_inner_wrapper">
                <div class="login_inner_content">
                    <h2>Register Your Account</h2>
                    <form action="{{ route('sellerSignup') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{-- <div class="form-group">
                            <label for="exampleFormControlInput1" class="form-label"><strong>Seller
                                    Registration:</strong></label>

                            @foreach ($roles as $key => $role)
                                @if ($key != 0)
                                    <div class="input_field radio-field">
                                        <div class="form-check">
                                            <input class="form-check-input" value="{{ $role->id }}"
                                                {{ $role->name == 'seller' ? 'checked' : '' }}type="radio" name="role"
                                                id="flexRadioDefault1" hidden>
                                            <label class="form-check-label" for="flexRadioDefault1" hidden>
                                                {{ $role->name }}   
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div> --}}
                        <div class="row">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="text-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                            {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleFormControlInput1" class="form-label">Country/Region</label>
                                <div class="input_field">
                                    <select name="country" class="form-select form-control">
                                            <option selected> Select Country </option>
                                        @foreach ($countries as $country)
                                            <option value="{{$country->id}}">{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div> --}}
                            {{-- @foreach ($provinces as $province)
                            @dd($province)
                        @endforeach --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Province</label>
                                    <div class="input_field">
                                        <select name="province" class="form-select form-control province">
                                            <option selected> Select Province </option>
                                            @foreach ($provinces as $province)
                                                <option value="{{ $province->id }}">{{ $province->eng_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">District</label>
                                    <div class="input_field">
                                        <select name="district" class="form-select form-control district">
                                            <option selected> Select District </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Area</label>
                                    <div class="input_field">
                                        <select name="area" class="form-select form-control local">
                                            <option selected> Select area </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Additional Address
                                        (optional)</label>
                                    <div class="input_field">
                                        <i class="lar la-envelope"></i><input type="text" name="address"
                                            value="{{ old('address') }}" class="form-control" id="exampleFormControlInput1"
                                            placeholder="15 m inside machapokhari">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Zip Code</label>
                                    <div class="input_field">
                                        <i class="lar la-envelope"></i><input type="number" value="{{ old('zip') }}"
                                            name="zip" class="form-control" id="exampleFormControlInput1"
                                            placeholder="44602">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Email address</label>
                                    <div class="input_field">
                                        <i class="lar la-envelope"></i><input type="email" value="{{ old('email') }}"
                                            name="email" class="form-control" id="exampleFormControlInput1"
                                            placeholder="name@example.com">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Password</label>
                                    <div class="input_field">
                                        <i class="las la-eye"></i><input type="password" name="password"
                                            class="form-control" id="exampleFormControlInput1" placeholder="Password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Confirm Password</label>
                                    <div class="input_field">
                                        <i class="las la-eye"></i></i><input type="password" name="confirm_password"
                                            class="form-control" id="exampleFormControlInput1"
                                            placeholder="Confirm Password">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Company Name</label>
                                    <div class="input_field">
                                        <i class="las la-mosque"></i><input type="text" value="{{ old('company') }}"
                                            name="company" class="form-control" id="exampleFormControlInput1"
                                            placeholder="Company Name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Full Name</label>
                                    <div class="input_field">
                                        <i class="las la-user"></i><input type="text" value="{{ old('name') }}"
                                            name="name" class="form-control" id="exampleFormControlInput1"
                                            placeholder="Name">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="exampleFormControlInput1" class="form-label">Phone Number</label>
                                    <div class="input_field">
                                        <i class="las la-phone-volume"></i><input type="text"
                                            value="{{ old('phone') }}" name="phone" class="form-control"
                                            id="exampleFormControlInput1" placeholder="Phone Number">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input class="form-check-input" name="agree" type="checkbox" value="1"
                                            id="flexCheckDefault">
                                        <label class="form-check-label" for="flexCheckDefault">
                                            By continuing, you agree to Eshoping <a href="{{ route('general', 'terms-conditions') }}"> Conditions of Use</a>
                                            and <a href="{{route('general', 'return-policy')}}"> Privacy Notice.</a>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="login-foot">
                                    <button type="submit" class="btn">Agree & Register</button>
                                    <p>Already have Account? <a href="{{ route('sellerLogin') }}">Login</a></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
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
@endpush
