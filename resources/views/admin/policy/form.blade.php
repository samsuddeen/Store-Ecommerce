@extends('layouts.app')

@section('content')
    <section id="default-breadcrumb">
        <section id="multiple-column-form">
            @if (@$appPolicy->id)
                <form action="{{ route('app-policy.update', @$appPolicy->id) }}" class="form" enctype="multipart/form-data"
                    method="POST">
                    @method('PATCH')
                @else
                    <form action="{{ route('app-policy.store') }}" class="form" enctype="multipart/form-data" method="POST">
            @endif
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                        data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                        aria-selected="true">Policy</button>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="general" role="tabpanel"
                                    aria-labelledby="general-tab">
                                    <div class="row" id="nothing">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <inputelement name="year" placeholder="2019"
                                                        value="{{ old('name', @$appPolicy->year) }}"
                                                        :errors="{{ json_encode($errors->toArray()) }}" required="true">
                                                    </inputelement>
                                                </div>

                                                @foreach ($policies as $policy)
                                                    <div class="col-md-12">
                                                        <div class="mb-1">
                                                            <label class="form-label" for="year">{{$policy['title']}}</label>
                                                                <small class="text-danger">*</small>
                                                            <textarea name="title[{{$policy['title']}}]" id="" class="form-control" rows="3">{{ Arr::get(@$assists, $policy['title']) ? @$assists[$policy['title']][0]['policy'] : '' }}</textarea>
                                                        </div>
                                                    </div>
                                                @endforeach


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary waves-effect waves-float waves-light">Submit</button>
                <button type="reset" class="btn btn-secondary waves-effect waves-float waves-light">Reset</button>
            </div>
            </form>
        </section>
    </section>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('dashboard/vendors/css/file-uploaders/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/css/plugins/forms/form-file-uploader.css') }}">
@endpush
@push('script')
    <script src="{{ asset('dashboard/vendors/js/file-uploaders/dropzone.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.lfm').filemanager("image");
        });
    </script>
@endpush
