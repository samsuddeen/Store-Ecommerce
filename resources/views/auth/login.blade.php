@extends('auth.layout.auth')
@section('title', env('DEFAULT_TITLE') . '|' . 'System|Login')
@section('content')
    <!-- Left Text-->
    {{-- <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid"
                src="{{ asset('/dashboard/images/pages/login-v2.svg') }}" alt="Login V2" />
        </div>
    </div> --}}

    <!-- /Left Text-->
    <!-- Login-->

    {{-- @dd($system_setting) --}}
    <div class=" align-items-center auth-bg px-2 p-lg-5 auth-login-page">
        <div class="">
            <h2 class="card-title fw-bold mb-1">Welcome to {{ env('APP_NAME') }}! 👋</h2>
            <p class="card-text mb-2">Please sign-in to your account and start the adventure</p>
            <form class="auth-login-form mt-2" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-1">
                    <label class="form-label" for="login-email">Email</label>
                    <input class="form-control" value="{{ old('email') }}" id="login-email" type="text" name="email"
                        placeholder="john@example.com" aria-describedby="login-email" autofocus="" tabindex="1" required />
                    @error('email')
                        <p><small class="text-danger">{{ $message }}</small></p>
                    @enderror
                </div>
                <div class="mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="login-password">Password</label><a
                            href="{{ route('password.request') }}"><small>Forgot
                                Password?</small></a>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="login-password" type="password" name="password"
                            placeholder="············" aria-describedby="login-password" tabindex="2" required /><span
                            class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                        @error('password')
                            <p><small class="text-danger">{{ $message }}</small></p>
                        @enderror
                    </div>
                </div>
                <div class="mb-1">
                    <div class="form-check">
                        <input class="form-check-input" id="remember-me" type="checkbox" tabindex="3" />
                        <label class="form-check-label" for="remember-me"> Remember Me</label>
                    </div>
                </div>
                <button class="btn w-100" style="background-color: #0080ff; color:#fff" tabindex="4" type="submit">Sign in</button>
            </form>
            @if (Route::has('register'))
                <p class="text-center mt-2"><span>New on our platform?</span><a
                        href="{{ route('register') }}"><span>&nbsp;Create an account</span></a></p>
            @endif
            {{-- <div class="divider my-2">
                <div class="divider-text">or</div>
            </div>
            <div class="auth-footer-btn d-flex justify-content-center"><a class="btn btn-facebook" href="#"><i
                        data-feather="facebook"></i></a><a class="btn btn-twitter white" href="#"><i
                        data-feather="twitter"></i></a><a class="btn btn-google" href="#"><i data-feather="mail"></i></a><a
                    class="btn btn-github" href="#"><i data-feather="github"></i></a></div> --}}
        </div>
    </div>
    <!-- /Login-->
@endsection
@push('script')
    <script>
        $('#toggle-password').on('click', function() {
            $('#password').prop('type');
            $('#password').prop('type') == 'password' ? $('#password').prop("type", "text") : $('#password').prop(
                "type", "password");

            $(this).toggleClass('la-eye');
            $(this).toggleClass('la-pen');
        })
    </script>
@endpush
