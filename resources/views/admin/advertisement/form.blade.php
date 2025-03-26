@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Advertisement')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Advertisement</h4>
                    </div>
                    <div class="card-body">
                        @if ($advertisement->id)
                            <form action="{{ route('advertisement.update', $advertisement->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('advertisement.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title <span class="text text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required
                                        value="{{ $advertisement->title }}" placeholder="Enter advertisement title">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="url">advertisement URL</label>
                                    <input type="url" class="form-control" id="url" name="url"
                                        value="{{ $advertisement->url }}" placeholder="Enter advertisement url">
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6 col-12">
                                <div class="mb-1">
                                    <label for="form-label" for="ad_type">Ad Type <span class="text text-danger">*</span></label>
                                    <select name="ad_type" id="ad_type" class="form-control">
                                        <option value="General" {{ old('ad_type',$advertisement->ad_type) == 'General' ? 'selected' : '' }}>General Ad</option>
                                        <option value="Skip Ad" {{ old('ad_type',$advertisement->ad_type) == 'Skip Ad' ? 'selected' : '' }}>Skip Ad</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-6 col-md-6 col-12">
                                <div class="mb-1">
                                    <x-filemanager name="image" :value="$advertisement->image"  ></x-filemanager>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 col-12">
                                <div class="mb-1">
                                    <x-filemanager name="mobile_image" :value="$advertisement->mobile_image" >
                                    </x-filemanager>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <label for="" class="form-label">Advertisement Positions</label>
                                <select class="form-control" name="positions[]" id="">
                                    @foreach ($positions as $position)
                                        <option value="{{ $position->id }}"
                                            {{ in_array($position->id, $selectedPositions) ? 'selected' : null }}>
                                            {{ $position->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-6 col-md-6">
                                <label for="" class="form-label">Advertisement Status</label>
                                <select class="form-control" name="status" id="status">
                                   <option value="active" {{($advertisement->status=='active') ? 'selected':''}}>Active</option>
                                   <option value="inactive" {{($advertisement->status=='inactive') ? 'selected':''}}>In Active</option>
                                </select>
                            </div>
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <label for="">size</label>
                                    <select class="form-control" name="size" id="size">
                                        @foreach (range(1, 12) as $size)
                                            <option value="{{ $size }}"
                                                {{ $size == $advertisement->size ? 'selected' : '' }}>
                                                {{ number_format(($size / 12) * 100) . ' %' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                        </div>
                        <x-dashboard.button :create="isset($advertisment->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
