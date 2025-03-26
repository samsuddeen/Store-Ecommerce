@extends('frontend.layouts.app')
{{-- @include('admin.includes.error') --}}
@section('content')
    <div id="content" class="site-content">

        <!-- Modal -->
        <div class="common-popup small-popup modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Resend OTP Verification Code</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="">
                            <div class="form-group">
                                <label for="emailphone">Enter Email/Phone</label>
                                <input type="text" class="form-control email_or_phone" name="emailphone">
                            </div>

                            <input type="button" class="btn btn-primary" id="resend" value="Submit"
                                data-bs-toggle="modal" data-bs-target="#exampleModal">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="otp-page mt mb">
            <div class="container">
                <div class="otp-wrapper">
                    <div class="otp-alert">
                        @if (session('success'))
                            <p id="success">
                                {{request()->session()->get('success')}}
                            </p>
                        @endif

                        @if (session('error'))
                            <p class="text-danger">
                                {{request()->session()->get('error')}}
                            </p>
                        @endif
                    </div>
                    <div class="otp-details">
                        <img src="{{ asset('frontend/images/otp.png') }}" alt="images">
                        <h3>OTP Verification</h3>
                        <p>
                            Please insert the 6 digit OTP that you received in the message.
                        </p>
                        <form method="post" action="{{ route('customer.verification') }}">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="input-text form-control" name="otp" value=""
                                    maxlength="6" required autofocus>
                                <div class="dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <input type="submit" value="Verify" name="login" class="btn">
                                <label for="rememberme"></label>
                            </div>
                            <div class="resend">
                                <p>
                                    Didn't get the message? &nbsp;

                                    <input type="button" value="Resend Code" class="main-resend" data-bs-toggle="modal"
                                        data-bs-target="#exampleModal">
                                </p>
                                <div class="countdown"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>        

    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
    crossorigin="anonymous"></script>

<script>    
    setTimeout(function() {
        @php
            session()->forget('customer_otp_form');
        @endphp
        window.location.href = "login";
    }, 180000);
</script>

<script>
    $(document).on('click', '#resend', function() {

        var url = "{{ route('customers.otp') }}";
        var email_or_phone = $('.email_or_phone').val();
        var $this = $(this);

        $.ajax({
            type: "GET",
            url: url,
            data: {
                email_or_phone: email_or_phone,
            },
            cache: false,
            success: function(response) {
                if (response.error) {
                    alert(response.msg);

                } else {

                    alert(response.msg);

                    $('.main-resend').val('Please wait for 1 minute');
                    $('.main-resend').attr('disabled', true);

                    setTimeout(function() {
                        $('.main-resend').attr('disabled', false);
                        $('.main-resend').val('Resend Code');
                    }, 60000);

                    var timer2 = "1:00";
                    var interval = setInterval(function() {
                        var timer = timer2.split(':');
                        //by parsing integer, I avoid all extra string processing
                        var minutes = parseInt(timer[0], 10);
                        var seconds = parseInt(timer[1], 10);
                        --seconds;
                        minutes = (seconds < 0) ? --minutes : minutes;
                        if (minutes < 0) clearInterval(interval);
                        seconds = (seconds < 0) ? 59 : seconds;
                        seconds = (seconds < 10) ? '0' + seconds : seconds;
                        //minutes = (minutes < 10) ?  minutes : minutes;
                        $('.countdown').html(minutes + ':' + seconds);
                        timer2 = minutes + ':' + seconds;

                    }, 1000);


                }
            }
        });

    });
</script>
