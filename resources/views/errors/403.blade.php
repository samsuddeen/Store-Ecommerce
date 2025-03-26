@extends('errors::minimal')
@section('content')
    <div class="misc-inner p-2 p-sm-3">
        <div class="w-100 text-center">
            <h1 class="mb-1">403</h1>
            <h2 class="mb-1">Unauthorized 🔐</h2>
            <p class="mb-2">
                You are unauthorized. please go back.
            </p><a class="btn btn-primary mb-1 btn-sm-block" href="{{ url()->previous() }}">Go Back</a><img
                class="img-fluid" src="{{ asset('dashboard/images/pages/login-v2.svg') }}"
                alt="Not authorized page" />
        </div>
    </div>
@endsection
