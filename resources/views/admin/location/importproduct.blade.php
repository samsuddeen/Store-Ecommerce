@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Product Import</h4>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <p><strong>Note:</strong><span style="color: red">Please Import the file which is exact as sample downloaded sample.</span>If You do not have sample file then please download fro the sample section.</p>
                               
                            </div>
                            <div class="row">
                                <div class="col-xl-4 col-md-6 col-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="title">Please Choose File:</label>
                                        <input type="file" name="file" class="form-control"
                                            value="{{ old('file') }}">
                                        @error('file')
                                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-success waves-effect waves-float waves-light mt-2" type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check me-25">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                <span>Import</span>
                            </button>
                            <button type="reset" class="btn btn-outline-primary waves-effect mt-2 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-recycle"
                                    viewBox="0 0 16 16">
                                    <path
                                        d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z" />
                                </svg>
                                <span>Reset</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
