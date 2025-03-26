@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Hub')
@push('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/vendors/css/forms/select/select2.min.css') }}">
@endpush

@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Hubs</h4>
                    </div>
                    <div class="card-body">
                        @if ($hub->id)
                            <form action="{{ route('hub.update', $hub->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('hub.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ old('title', $hub->title) }}" placeholder="Please Enter Hub Title"
                                        required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="{{ old('address', $hub->address) }}" placeholder="Please Enter Hub Title"
                                        required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4 col-12">
                                <label class="form-label" for="status">Status</label>
                                {{ Form::select('status',[1=>'Active',0=>'In-Active'],@$hub->status,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                               
                                @error('status')
                                    <p class="form-control-static text-danger" id="status">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($hub->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection