
@extends('errors::minimal')
@section('content')
    <div class="misc-inner">
        <div class="w-100 text-center">
            {{-- <h1 class="mb-1">404</h1>
            <h2 class="mb-1">Page Not Found! 😟</h2>
            --}}
            {{-- <img class="img-fluid" src="{{ asset('frontend/Error 404.png') }}"
            alt="Not authorized page" style="height:500px;"/> --}}
            <p class="mb-2">
                The page you are looking for could not be found.
            </p>
            <a class="btn btn-primary mb-1 btn-sm-block" href="{{ route('index') }}">Back to
                Home Page</a>

        </div>
    </div>
@endsection
