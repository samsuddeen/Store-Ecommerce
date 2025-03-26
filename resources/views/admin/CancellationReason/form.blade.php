@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Reasons')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Reasons</h4>
                    </div>
                    <div class="card-body">
                        @if (@$cancellationReason->id)
                            <form action="{{ route('cancel-reason.update', @$cancellationReason->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('cancel-reason.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title <span class="text text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ @$cancellationReason->title }}" placeholder="Enter title">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="mb-1">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="0" {{ old('status',@$cancellationReason->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                        <option value="1" {{ old('status',@$cancellationReason->status) == 1 ? 'selected' : '' }}>Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($cancellationReason->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
