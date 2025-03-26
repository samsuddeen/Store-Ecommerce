@extends('auth.layout.auth')
@section('content')
    <!-- /Brand logo-->
    <!-- Left Text-->
    {{-- <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
        <div class="w-100 d-lg-flex align-items-center justify-content-center px-5"><img class="img-fluid"
                src="/dashboard/images/pages/reset-password-v2.svg" alt="Register V2" /></div>
    </div> --}}
    <!-- /Left Text-->
    <!-- Reset password-->
    <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
        <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
            <h2 class="card-title fw-bold mb-1">Reset Password 🔒</h2>
            {{-- <p class="card-text mb-2">Your new password must be different from previously used passwords</p> --}}
            <form class="auth-reset-password-form mt-2" action="{{ route('customer.password.updates', $token) }}" method="post">
                @method('PATCH')
                @csrf
                <div class="mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">New Password</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="password" type="password" name="password"
                            placeholder="············" aria-describedby="password" autofocus="" tabindex="1"
                            required /><span class="input-group-text cursor-pointer"><i data-feather="eye"></i></span>
                    </div>

                    @error('password')
                        <p><small class="text-danger">{{ $message }}</small></p>
                    @enderror
                </div>
                <div class="mb-1">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password_confirmation">Confirm Password</label>
                    </div>
                    <div class="input-group input-group-merge form-password-toggle">
                        <input class="form-control form-control-merge" id="password_confirmation" type="password"
                            name="password_confirmation" placeholder="············" aria-describedby="password_confirmation"
                            tabindex="2" required /><span class="input-group-text cursor-pointer"><i
                                data-feather="eye"></i></span>
                    </div>
                </div>
                <button tyoe="submit" class="btn btn-primary w-100" tabindex="3">Set New Password</button>
            </form>
            <p class="text-center mt-2"><a href="{{ route('Clogin') }}"><i data-feather="chevron-left"></i> Back to
                    login</a></p>
        </div>
    </div>
@endsection
