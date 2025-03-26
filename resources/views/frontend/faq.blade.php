@extends('frontend.layouts.app')
@section('title', @$content->name)
@section('content')
    <section id="general_page_header">
        @if($content->banner_image !=null)
        <img src="{{ @$content->banner_image }}" alt="images">
        @endif
        {{-- <img src="{{ asset('frontend/images/bg10.jpg') }}" alt="images"> --}}
    </section>
    <section id="general_page_wrapper" class="mt mb">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-7">
                    <div class="general_background_style">
                        <h1>{!! @$content->name !!}</h1>
                        <div class="accordion accordion-flush" id="accordionFlushExample">
                            @foreach ($faqs as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading{{ $faq->slug }}">
                                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $faq->slug }}" aria-expanded="false" aria-controls="flush-collapse{{ $faq->slug }}">
                                    {{ $faq->title }}
                                  </button>
                                </h2>
                                <div id="flush-collapse{{ $faq->slug }}" class="accordion-collapse collapse" aria-labelledby="flush-heading{{ $faq->slug }}" data-bs-parent="#accordionFlushExample">
                                  <div class="accordion-body">
                                        {!! $faq->description !!}
                                    </div>
                                </div>
                              </div>                                
                            @endforeach
                          </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-5">
                    <div class="general-sidebar">
                        {{-- @dd($content) --}}
                        {{-- <a href="#"> --}}
                            @if($content->image !=null)
                        <img src="{{ @$content->image }}" alt="images">
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
