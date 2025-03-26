@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Task')
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
                            <h4>{{ $log->log_title }}</h4>
                            @if($log->guard == 'customer')
                                <span class="card-text item-company">Name of the User <a href="{{ route('customer.show',$log->log_id) }}"
                                        class="company-name">{{ $user->name }}</a></span>
                            @else
                            <span class="card-text item-company">Name of the User <a href="{{ route('user.show',$log->log_id) }}"
                                class="company-name">{{ $user->name }}</a></span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($actions as $action)
                            <div class="col-12 col-sm-4">
                                <ul>
                                    <li><span class="text text-danger">{{ $action->action }}</span> - {{ $action->created_at->format('Y-m-d H:i:s') }}</li>
                                </ul>
                            </div>
                        @endforeach
                    </div>
                    {{ $actions->links() }}
                </div>
            </div>

        </section>
    </div>
@endsection
