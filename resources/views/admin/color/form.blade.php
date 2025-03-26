@extends('layouts.app')
@isset($color->id)
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Update Color')
@else
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Create Color')
@endif

@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/css/forms/select/select2.min.css') }}">
@endpush

@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Colors</h4>
                    </div>
                    <div class="card-body">
                        @if ($color->id)
                            <form action="{{ route('color.update', $color->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('color.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-4 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ old('title', $color->title) }}" placeholder="red,green,lightblue"
                                        required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4 col-12">
                                <div class="mb-1 color-codedash">
                                    <label class="form-label" for="title">Color code</label>
                                    <input type="color" class="form-control" id="colorCode" name="colorCode"
                                        value="{{ old('colorCode',$color->colorCode) }}" placeholder="red,green,lightblue" required>
                                    @error('colorCode')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="status">Status</label>
                                {{ Form::select('status',[1=>'Active',0=>'In-Active'],@$color->status,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                               
                                @error('status')
                                    <p class="form-control-static text-danger" id="status">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <x-dashboard.button :create="isset($color->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
