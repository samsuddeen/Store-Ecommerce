@extends('frontend.layouts.app')
@section('title','Customer | Email Verify')
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
                        <h3>Enter Your Email Here</h3>
                    </div>
                    @if (request()->session('error'))
                        <span class="text-danger"> {{ request()->session()->get('error') }} </span>
                    @endif

                    <form action="{{route('facebook.verifyUser.post')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <input class="form-control" value="{{ old('email') }}" id="login-email" type="email"
                                name="email" placeholder="name@example.com" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="g-recaptcha" data-sitekey="6Lf6aRMnAAAAAAJP9TnS50J5xajMNaxrZqNK-jAi"></div>
                        @error('g-recaptcha-response')
                            <span class="text-danger"> The reCAPTCHA was invalid. Go back and try it again.
                            </span>
                        @enderror
                       
                        <div class="form-btn">
                            <button type="submit" name="submit" class="btns">Log In</button>
                        </div>
                
                    </form>
                    
                </div>
            </div>
        </div>
    </section>
    <!-- Login Page End  -->

@endsection
