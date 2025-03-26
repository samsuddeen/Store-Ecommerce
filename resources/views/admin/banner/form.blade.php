@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Banners</h4>
                    </div>
                    <div class="card-body">
                        @if ($banner->id)
                            <form action="{{ route('banner.update', $banner->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('banner.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="title"
                                        value="{{old('title', $banner->title )}}" placeholder="Enter brand name">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Content</label>
                                    <textarea name="content" id="content" class="form-control" rows="3">{{old('content', @$banner->content)}}</textarea>
                                    @error('content')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <x-filemanager name='image' :value="$banner->image"></x-filemanager>
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($banner->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
