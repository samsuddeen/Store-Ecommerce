@extends('frontend.layouts.app')
@section('title', @$content->name)
@section('content')
    <section id="general_page_header">
        @if($content->banner_image !=null)
        <img src="{{ @$content->banner_image }}" alt="images" width="100%">
        @endif
        {{-- <img src="{{ asset('frontend/images/bg10.jpg') }}" alt="images"> --}}
    </section>
    <section id="general_page_wrapper" class="mt mb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <div class="general_background_style">
                        <h1>{!! @$content->name !!}</h1>
                        {!! @$content->content !!}
                    </div>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="general-sidebar">
                        {{-- @dd($content) --}}
                        {{-- <a href="#"> --}}
                            @if($content->image !=null)
                        <img src="{{ @$content->image }}" alt="images" width="100%">
                        @endif
                        {{-- <img src="{{asset('frontend/images/ads.png')}}" alt="images"> --}}
                        {{-- </a> --}}
                    </div>
                </div>
            </div>
            {{-- <div class="col-lg-4">
                    <div class="quotes_info">
                        <img src="{{ @$content->image }}" height="450" width="350" title="Feature Image">
                    </div>
                </div> --}}
        </div>
    </section>
@endsection
