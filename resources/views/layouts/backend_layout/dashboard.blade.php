@extends('layouts.backend_layout.template')
@section('title', 'Admin || Dashboard')

@section('main-content')
    <div class="pagetitle">
        <h1>Admin Dashboard</h1>

    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">

            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    <!-- Product -->
                    <div class="col-xxl-4 col-md-4">
                        <a href="{{ route('trip.index') }}">
                            <div class="card info-card revenue-card">
                                <div class="card-body">

                                    <h5 class="card-title">All Active Trips</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa-solid fa-mountain-city"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $trips->count() }}</h6>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div><!-- End Product -->

                    <!-- Banner -->
                    <div class="col-xxl-4 col-md-4">
                        <a href="{{ route('banner.index') }}">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">All Active Banners</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa fa-image"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $banners->count() }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div><!-- End Banner -->

                    <!-- Project -->
                    <div class="col-xxl-4 col-md-4">
                        <a href="{{ route('cyberCast.index') }}">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">All Active Cyber Cast</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-list-stars"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $cybercasts->count() }}</h6>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div><!-- End Project -->

                    <div class="col-xxl-4 col-md-4">
                        <a href="{{ route('team.index') }}">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">All Active Members</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa fa-users"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $members->count() }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div><!-- End Banner -->


                    <!-- Blog -->
                    <div class="col-xxl-4 col-md-6">
                        <a href="{{ route('post.index') }}">
                            <div class="card info-card revenue-card">
                                <div class="card-body">
                                    <h5 class="card-title">All Active Posts</h5>
                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-bootstrap-fill"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $posts->count() }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div><!-- End Blog -->

                    <div class="col-xxl-4 col-md-4">
                        <a href="{{ route('all-messages.index') }}">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <h5 class="card-title">All Messages</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fa fa-envelope"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $messages->count() }}</h6>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </a>
                    </div><!-- End Project -->


                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>

@endsection
