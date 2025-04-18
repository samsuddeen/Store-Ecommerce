@extends('frontend.layouts.app')
@include('admin.includes.error')
@section('content')
<div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                 <div class="card-header">Verify Your Email Address</div>
                   <div class="card-body">
                    @if (session('resent'))
                         <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    <a href="http://customlaravelauth.co/{{$token}}/reset-password">Click Here</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
