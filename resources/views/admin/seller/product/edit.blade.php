@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Product')
@section('content')
    <section id="default-breadcrumb">
        <section id="multiple-column-form">
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>
                                {{ $message }}
                            </li>
                        @endforeach
                    </ul>

                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Basic Information</h4>
                        </div>
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Basic
                                    Information</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="#" type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" role="tab" aria-controls="profile"
                                    aria-selected="false">Category</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Attributes</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                                    type="button" role="tab" aria-controls="contact"
                                    aria-selected="false">Description</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="price-tab" data-bs-toggle="tab" data-bs-target="#price"
                                    type="button" role="tab" aria-controls="price" aria-selected="false">Price and
                                    Stock</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="service-tab" data-bs-toggle="tab" data-bs-target="#service"
                                    type="button" role="tab" aria-controls="service" aria-selected="false">Service and
                                    Delivery</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                    type="button" role="tab" aria-controls="seo" aria-selected="false">SEO</button>
                            </li>
                        </ul>

                        <div class="ms-1">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="home" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    @include('admin.seller.product.edit.basic')
                                </div>
                                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    @include('admin.seller.product.edit.attribute')
                                </div>
                                <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                                    @include('admin.seller.product.edit.description')
                                </div>
                                <div class="tab-pane fade" id="price" role="tabpanel" aria-labelledby="contact-tab">
                                    @include('admin.seller.product.edit.price-stock')
                                </div>
                                <div class="tab-pane fade" id="service" role="tabpanel" aria-labelledby="contact-tab">
                                    @include('admin.seller.product.edit.service-delivery')
                                </div>

                                <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                    @include('admin.seller.product.edit.seo-info')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @include('admin.product.category-edit.category-edit')
            </form>
        </section>
    </section>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('dashboard/css/plugins/forms/form-file-uploader.css') }}">
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            loadUniSharp();
        });
    </script>
@endpush
