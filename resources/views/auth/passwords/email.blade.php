@extends('frontend.layouts.app')
@section('content')

    {{-- <section id="login_wrapper" style="background: url({{ asset('frontend/images/login.jpg') }});">
        <div class="container">
            <div class="login_inner_wrapper">
                <div class="login_inner_content change-for-forget-password">

                    <h2>Forget Password</h2>
                    @if (request()->session('error'))
                        <span class="text-danger"> {{ request()->session()->get('error') }} </span>
                    @endif

                    <form class="auth-login-form mt-2" action="{{ route('customer.password.email') }}" method="POST">
                        @csrf

                        <h5> Please Enter Your Email for Reset Link</h5>

                        <div class="form-group">
                            <input id="email" type="email" class="form-control" name="email"
                                value="{{ old('email') }}" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-danger mt-2">
                            Send Password Reset Link
                        </button>

                </div>
                <div class="get-help">Login Back, <a href="{{ route('Clogin') }}" class="change-form"> Click Here </a></div>
                </form>
            </div>
        </div>
        </div>
    </section> --}}

    <!-- Forgot Password Page  -->
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
                        <h3>Forget Password</h3>
                        <a href="{{ route('Clogin') }}">Login</a>
                    </div>
                    @if (request()->session('error'))
                        <span class="text-danger"> {{ request()->session()->get('error') }} </span>
                    @endif
                    <form class="auth-login-form" action="{{ route('customer.password.email') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input id="email" type="email" class="form-control" name="email"
                                value="{{ old('email') }}" autofocus placeholder="Enter your email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    By continuing, you agree to Eshoping with Glass Pipe
                                    <a href="{{ route('general', 'terms-conditions') }}">Conditions of Use</a> and <a
                                        href="{{ route('general', 'return-policy') }}">Privacy Notice.</a>
                                </label>
                            </div>
                        </div>
                        <div class="form-btn">
                            <button type="submit" class="btns">Send Password Reset Link</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Forgot Password Page End  -->
@endsection
