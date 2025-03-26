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
            <div class="register-right">
                <div class="register-right-head" id="form-changes">

                    <h3>Create Your Account</h3>
                    <a href="{{ route('sellerLogin') }}">Login</a>

                </div>
                <form action="javascript:;" method="post" id="signUpForm">
                    @csrf
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div class="text-danger">{{ $error }}</div>
                        @endforeach
                    @endif
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone</label>
                        <div class="form-group">
                            <div class="input_field ">
                                <input type="tel" disabled value="{{ old('phone', @$phone) }}" name="phone"
                                    class="form-control sellerPhoneField" placeholder="+977-">
                            </div>
                            <span class="text-danger sellerPhoneFieldError" id="phone"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">Name</label>
                        <div class="form-group">
                            <div class="input_field "></i><input type="text" value="{{ old('name') }}"
                                    name="name" class="form-control sellerNameField">
                            </div>
                            <span class="text-danger sellerNameFieldError" id="name"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <div class="form-group">
                            <div class="input_field "><input type="email" value="{{ old('email') }}" name="email"
                                    class="form-control sellerEmailField">
                            </div>
                            <span class="text-danger sellerEmailFieldError" id="email"></span>

                        </div>

                    </div>
                    <input type="hidden" name="verified_phone" value="{{ @$phone }}">
                    <input type="hidden" name="verified_otp" value="{{ @$otp }}">
                    {{-- <div class="form-group">
                            <label for="otp" class="form-label">Verification Otp</label>
                            <br>
                            <span>The verification code has been sent to +977{{ @$phone }}</span>
                            <div class="form-group">
                                <div class="input_field">
                                    <input type="hidden" value="{{ old('otp') }}" name="otp" class="form-control">
                                </div>
                                <span class="text-danger" id="otp"></span>
                            </div>
                        </div> --}}
                    <div class="form-group">
                        <label for="password" class="form-label">Setup Password</label>
                        <div class="form-group">
                            <div class="input_field">
                                <input type="password" value="{{ old('password') }}" name="password"
                                    class="form-control sellerPasswordField">
                            </div>
                            <span class="text-danger sellerPasswordFieldError" id="password"></span>

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Retype Password</label>
                        <div class="form-group">
                            <div class="input_field">
                                <input type="password" value="{{ old('password_confirmation') }}"
                                    name="password_confirmation" class="form-control sellerPasswordConfirmationField" id="password_confirmation">
                            </div>
                            <span class="text-danger sellerPasswordConfirmationFieldError" id="error_msg"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="company_name" class="form-label">Company Name</label>
                        <div class="form-group">
                            <div class="input_field ">
                                <input type="text" value="{{ old('company_name') }}" name="company_name"
                                    class="form-control">
                            </div>
                            <span class="text-danger" id="company_name"></span>

                        </div>

                    </div>


                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input sellerAgreeField" name="agree" type="checkbox" value="1"
                                id="flexCheckDefault" required>
                            <label class="form-check-label" for="flexCheckDefault">
                                By continuing, you agree to Eshoping <a
                                    href="{{ route('general', 'terms-conditions') }}">Conditions of
                                    Use</a>
                                and <a href="{{ route('general', 'return-policy') }}">Privacy
                                    Notice.</a>
                            </label>
                        </div>
                    </div>
                    <div class="form-btn">
                        <button type="submit" class="btns sellerSubmitField" id="signUpBtn">Create Account</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
