@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Task Action Create')
@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <div class="card">

                <div class="card-body">
                    @if (@$action->id)
                        <form class="row gy-1 pt-75" action="{{ route('task-action.update', @$action->id) }}" method="post">
                            @method('PUT')
                        @else
                            <form class="row gy-1 pt-75" action="{{ route('task-action.store') }}" method="post">
                    @endif
                    @csrf
                    <div class="col-12 col-md-4">
                        <label class="form-label" for="name">Title</label>
                        <input type="text" id="title" name="title" class="form-control" required
                            value="{{ old('title', @$action->title) }}" placeholder="Packing" />
                        @error('title')
                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center mt-1">
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" class="form-check-input" id="customSwitch10" name="status"
                                    {{ @$action->status ? 'checked' : null }} />
                                <label class="form-check-label" for="customSwitch10">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                            <label class="form-check-label fw-bolder" for="customSwitch10">Status</label>
                            @error('status')
                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="d-flex align-items-center mt-1">
                            <div class="form-check form-switch form-check-primary">
                                <input type="checkbox" class="form-check-input" id="customSwitch11" name="is_default"
                                    {{ @$action->is_default ? 'checked' : null }} />
                                <label class="form-check-label" for="customSwitch11">
                                    <span class="switch-icon-left"><i data-feather="check"></i></span>
                                    <span class="switch-icon-right"><i data-feather="x"></i></span>
                                </label>
                            </div>
                            <label class="form-check-label fw-bolder" for="customSwitch10">Is Default</label>
                            @error('is_default')
                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                   
                    <div class="col-12 mt-2 pt-50">
                        <button type="submit" class="btn btn-primary me-1">Submit</button>
                        <button type="reset" class="btn btn-outline-secondary" aria-label="Close">
                            Discard
                        </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
         $('#lfm').filemanager('image');
    </script>
@endpush
