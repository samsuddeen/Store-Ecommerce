@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Featured Section')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Featured Section</h4>
                    </div>
                    <div class="card-body">
                        @if ($featuredSection->id)
                            <form action="{{ route('featured-section.update', $featuredSection->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('featured-section.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="title"
                                        value="{{ $featuredSection->title }}" placeholder="Enter brand name">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">Type</label>
                                    <select name="type" id="type" class="form-control form-control-sm">
                                        <option value="">Please Select</option>
                                        @foreach($types as $index=>$type)
                                        <option value="{{$type}}" {{ (old('type', ($featuredSection->id ?  $featuredSection->type : '')) == $type) ? 'selected' : '' }}>{{$index}}</option>
                                        @endforeach
                                    </select>
                                    @error('count')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">Status</label>
                                    <select name="status" id="status" class="form-control form-control-sm">
                                        <option value="1" {{ (old('status', ($featuredSection->id ?  $featuredSection->status : '')) == 1) ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ (old('status', ($featuredSection->id ? $featuredSection->status : '')) == 0) ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('count')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($featuredSection->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
