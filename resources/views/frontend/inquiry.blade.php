@extends('frontend.layouts.app')
@section('title', 'Inquiry')
@section('content')
    <section id="general_page_header">
        @isset ($content)
            <img src="{{ @$content->banner_image }}" alt="images">
        @endisset
        {{-- <img src="{{ asset('frontend/images/bg10.jpg') }}" alt="images"> --}}
    </section>
    <section id="general_page_wrapper" class="mt mb">
        <div class="container">
            <div class="row">
                {{-- <div class="col-lg-4 col-md-5">
                    <div class="general-sidebar">
                        <div class="row contact-info">
                            <div class="col-lg-12 col-md-12">
                                <div class="contact-detail-field">
                                    <div class="icon-side">
                                        <div class="icon-img">
                                            <img src="{{asset('icon_image/phone-call.png')}}" alt="Contact" height="50" width="50">
                                        </div>
                                    </div>
                                    <div class="detail-side">
                                        <div class="contact-detail">
                                            <ul class="">
                                                <li>{{ @$meta_setting['phone'] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="contact-detail-field">
                                    <div class="icon-side">
                                        <div class="icon-img">
                                            <img src="{{asset('icon_image/mail.png')}}" alt="Email" height="50" width="50">
                                        </div>
                                    </div>
                                    <div class="detail-side">
                                        <div class="contact-detail">
                                            <ul class="">
                                                <li>{{ @$meta_setting['email'] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="contact-detail-field">
                                    <div class="icon-side">
                                        <div class="icon-img">
                                            <img src="{{asset('icon_image/location.png')}}" alt="Location" height="50" width="50">
                                        </div>
                                    </div>
                                    <div class="detail-side">
                                        <div class="contact-detail">
                                            <ul class="">
                                                <li>{{ @$meta_setting['address'] }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="col-lg-12 col-md-12">
                    <div class="general_background_style">
                        <h3 class="ms-3">Get Product({{@$product->name}}) Inquiry</h3>
                        <form action="{{ route('getinquirysend_inquiry') }}" method="POST" id="contactForm" class="p-2">
                            @csrf
                            <div class="row p-2 my-3">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="full_name" id="full_name" placeholder="Full Name" value="{{ old('full_name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="email" id="email" placeholder="Email Address" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row p-2 my-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="tel" class="form-control" name="phone" id="phone" placeholder="Contact Number" value="{{ old('phone') }}" required>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row p-2 my-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <select name="title" id="title" class="form-control">
                                            <option value="Customer Service" {{ old('title') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                                            <option value="Delivery Person" {{ old('title') == 'Delivery Person' ? 'selected' : '' }}>Delivery Person</option>
                                            <option value="Web" {{ old('title') == 'Web' ? 'selected' : '' }}>Web</option>
                                            <option value="App" {{ old('title') == 'App' ? 'selected' : '' }}>App</option>
                                        </select>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="row p-2 my-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" id="message" class="form-control" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <input type="text" name="product_id" value="{{@$product->id}}" hidden>
                            <div class="row p-2 my-3">
                                <div class="col-md-12">
                                    <div name="captcha" class="g-recaptcha footerCaptcha"
                                    data-sitekey="6LeQV5wpAAAAAAw4GSF3A5D8EQckw_q5232OZX_E" required></div>
                                    @error('g-recaptcha-response')
                                        <span class="text text-danger">{{ $message }}</span>
                                    @enderror
                                    <span class="text-warning captchaerror" hidden> The reCAPTCHA was invalid. Go back and try
                                        it again.
                                    </span>
                                </div>
                            </div>
                            <div class="row p-2 my-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-default">Send Message</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
