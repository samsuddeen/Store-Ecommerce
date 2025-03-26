@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Setting')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"> Banner & Logo </h4>
                    </div>
                    <div class="card-body">
                        @if ($sellerSetting->id)
                            <form action="{{ route('seller-setting.update', $sellerSetting->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('seller-setting.store') }}" method="POST">
                        @endif
                        @csrf

                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">

                                <label class="form-label" for="banner_image">Banner</label>

                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="banner_image" data-input="thumbnail" data-preview="holder"
                                            class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> Choose<span class="text-black">size (1920 *
                                                475)</span>
                                        </a>
                                    </span>
                                    <input id="thumbnail" class="form-control" type="text" name="banner_image"
                                        value="{{ old('banner_image', $sellerSetting->banner_image) }}" required>
                                </div>
                                @if (isset($sellerSetting->banner_image))
                                    <img src="{{ $sellerSetting->banner_image }}" alt="Img"
                                        style="width: 100px; height: auto;">
                                @endif

                                @error('banner_image')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                @enderror

                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <label for="logo"> Logo</label>

                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <a id="logo_image" data-input="thumbnail1" data-preview="holder"
                                            class="btn btn-primary">
                                            <i class="fa fa-picture-o"></i> Choose<span class="text-black">size (194 *
                                                120)</span>
                                        </a>
                                    </span>
                                    <input id="thumbnail1" class="form-control" type="text" name="logo"
                                        value="{{ old('logo', $sellerSetting->logo) }}" required>

                                </div>

                                @if (isset($sellerSetting->logo))
                                    <img src="{{ $sellerSetting->logo }}" alt="Img"
                                        style="width: 100px; height: auto;">
                                @endif

                                @error('logo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror

                            </div>

                        </div>
                        <x-dashboard.button :create="isset($sellerSetting->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        $('#banner_image').filemanager("image", {
            prefix: "/laravel-filemanager"
        });

        $('#logo_image').filemanager("image", {
            prefix: "/laravel-filemanager"
        });
    </script>
@endpush
