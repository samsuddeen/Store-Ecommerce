@extends('errors::minimal')
@section('content')
    <div class="misc-inner p-2 p-sm-3">
        <div class="w-100 text-center">
            <h1 class="mb-1">500</h1>
            <h2 class="mb-1">Service Unavailable😟</h2>
            <p class="mb-2">
                Service You were looking is not available at the moment.
            </p><a class="btn btn-primary mb-1 btn-sm-block" href="{{ route('dashboard') }}">Back to
                dashboard</a><img class="img-fluid" src="{{ asset('dashboard/images/pages/coming-soon.svg') }}"
                alt="Not authorized page" />
        </div>
    </div>
@endsection
