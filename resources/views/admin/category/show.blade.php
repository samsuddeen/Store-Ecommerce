@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')
@push('style')
    <link href="{{ asset('dashboard/css/pages/app-ecommerce-details.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/colors.css') }}">
@endpush
@section('content')
{{-- @dd($category) --}}
    <div class="ecommerce-application">
        <section class="app-ecommerce-details">
            <div class="card">
                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-12 col-md-12 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                            <div class="d-flex align-items-center justify-content-center flex-column">
                                <h4>{{ @$category->title }}</h4>
                                <h4>Sub Parent Of "{{ @$category->getParent->title}}"</h4>
                            
                                <img src="{{ $category->image ?? null }}" class="img-fluid product-img"
                                    alt="product image" />
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
           
        </section>
    </div>
@endsection
