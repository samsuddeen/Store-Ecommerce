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
                                <label for="">Enter Email/Phone</label>
                                <input type="text" name="email_or_phone" class="email_or_phone form-control"
                                    class="form-control">
                            </div>
                            <button type="button" id="resend" value="Resend Code" class="btn" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal End  -->

    </div>

        <!-- Modal End  -->        
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
                        <form method="post" action="{{ route('seller.verification') }}">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="input-text form-control" name="otp" value=""
                                    maxlength="6" required autofocus />
                                <div class="dots">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <input type="submit" class="btn" value="Verify" name="login">
                                <label for="rememberme"></label>
                            </div>
                            <div class="resend">
                                <p>Didn't get the message?
                                    <input type="button" value="Resend Code" class="btns disabled-modal"
                                        data-bs-toggle="modal" data-bs-target="#exampleModal">
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
        $(document).ready(function(){                
            setTimeout(function(){
                @php
                    session()->forget('seller_otp_form');           
                @endphp
                window.location.href="login";
            }, 180000);
        });
    </script>

<script>
  
    $(document).on('click', '#resend', function() {        
        var url = "{{ route('sellers.otp') }}"
        var $this = $(this);
        var email_or_phone = $('.email_or_phone').val();

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
                                        
                    alert(response.msg)

                    $('.disabled-modal').val('Please wait for 1 minute');
                    $('.disabled-modal').attr('disabled', true);

                    setTimeout(function() {
                        $('.disabled-modal').attr('disabled', false);
                        $('.disabled-modal').val('Resend Code');
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
