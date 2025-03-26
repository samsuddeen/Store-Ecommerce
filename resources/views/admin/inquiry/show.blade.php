@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Inquiry')
@push('style')
    <link href="{{ asset('dashboard/css/pages/app-ecommerce-details.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/colors.css') }}">
@endpush
@section('content')
    <div class="ecommerce-application">
        <section class="app-ecommerce-details">
            <div class="card">
                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-12 col-md-7">`
                            <h4>{{ $inquiry->title }}</h4>
                            <p class="card-text">Name<span
                                    class="text-success ms-1">{{ $inquiry->full_name}}</span></p>
                            <p class="card-text">Email<span
                                        class="text-success ms-1">{{ $inquiry->email}}</span></p>
                            <p class="card-text">Contact<span
                                            class="text-success ms-1">{{ $inquiry->contact }}</span></p>

                            <p class="card-text">
                                {!! $inquiry->message !!}
                            </p>
                            <hr />
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
@endsection
