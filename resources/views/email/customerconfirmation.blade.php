@component('mail::message')

{{-- <img src="{{asset('')}}uploads/settings/{{$setting->site_logo}}" height="120px" width="350px"><br> --}}

Dear {{ $customer['name'] }},
# Welcome to {{ config('app.name') }}.

Thank you for signing up.
Your OTP for verification is:

#{{$customer['verify_otp']}}

It will expire in 5 mins.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
