
@extends('frontend.layouts.app')
{{-- @include('frontend.includes.error') --}}
@section('content')
<section id="login_wrapper" style="background: url(images/bg.jpg);">
	<div class="container">
		<div class="login_inner_wrapper">
			<div class="login_inner_content">
				<h2>Login Here</h2>
                <form class="auth-login-form mt-2" action="{{ route('loginaccess') }}" method="POST">
                    @csrf
				<div class="mb-3 input_type_wrap">
					<label for="exampleFormControlInput1" class="form-label">Email address</label>
					<div class="input_field">
						<i class="lar la-envelope"></i><input class="form-control" value="{{ old('email') }}" id="login-email" type="text" name="email" placeholder="name@example.com">
					</div>
				</div>
				<div class="mb-3 input_type_wrap">
					<label for="exampleFormControlInput1" class="form-label">Password</label>
					<div class="input_field">
						<i class="las la-eye"></i>
						<input class="form-control" id="login-password" type="password" name="password" placeholder="password">
					</div>
				</div>
				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
					<label class="form-check-label" for="flexCheckDefault">
						By continuing, you agree to Eshoping <a href="#">Conditions of Use</a> and <a href="#">Privacy Notice.</a>
					</label>
				</div>
                @php


                @endphp
				<div class="login-foot">

					<button type="submit"name="submit" class="btn btn-danger">Login</a>


				</div>

                <div class="get-help">Forgot Password? <a href="{{ route('getpasswords/{token}') }}">Click Here</a></div>
                </form>
			</div>
		</div>
	</div>
</section>
@endsection

@push('script')

@endpush
