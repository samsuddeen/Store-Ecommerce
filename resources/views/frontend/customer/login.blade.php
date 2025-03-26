@extends('frontend.layouts.app')
@section('title','Customer | Login')
@section('content')


    <!-- Login Page  -->
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
                </div> --}}
                <div class="register-right">
                    <div class="register-right-head">
                        <h3>Login</h3>
                        <a href="{{ route('signup') }}">Register</a>
                    </div>
                    @if (request()->session('error'))
                        <span class="text-danger"> {{ request()->session()->get('error') }} </span>
                    @endif
                    <form action="{{ route('loginaccess') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" value="{{ old('email') }}" id="login-email" type="text"
                                name="email" placeholder="name@example.com" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group" style="position: relative;">
                            <input class="form-control" id="login-password" type="password" name="password"
                                placeholder="password" required><span toggle="#login-password" class="fa fa-eye field-icon toggle-password"></span>
                            @error('password')
                                <span class="text-danger">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="g-recaptcha" data-sitekey="6LcFXd0qAAAAADALqw7XzghCg1A3n5rQbmaMrFDI"></div>
                                        @error('g-recaptcha-response')
                                            <span class="text-danger"> The reCAPTCHA was invalid. Go back and try it again.
                                            </span>
                                        @enderror
                        
                        {{-- <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                                <label class="form-check-label" for="flexCheckDefault">
                                    By logging in, I understand & agree to terms of use and privacy policy
                                </label>
                            </div>
                        </div> --}}
                        <div class="form-btn">
                            <button type="submit" name="submit" class="btns">Log In</button>
                        </div>
                        <span class="text-center">Or</span>
                        <div class="other-option">
                            <ul>
                                <li>
                                    <a href="{{ route('facebook') }}" title="Facebook"><i class="lab la-facebook-f"></i> Facebook</a>
                                </li>
                                <li>
                                    <a href="{{ route('google') }}" title="Google"><i class="lab la-google-plus"></i> Google</a>
                                </li>
                            </ul>
                        </div>
                        <div class="forgot-password">
                            <a href="{{ route('customer.getpass') }}">Forgot Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Page End  -->

@endsection
@push('script')
    <script>
        $(document).ready(function(){
            $(document).on('click','.toggle-password',function(){
                $(this).toggleClass("fa-eye fa-eye-slash");
                    var input = $($(this).attr("toggle"));
                    if (input.attr("type") == "password") {
                    input.attr("type", "text");
                    } else {
                    input.attr("type", "password");
                    }
                });
        });
    </script>
@endpush