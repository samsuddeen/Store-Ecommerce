@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'FAQ')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">FAQ</h4>
                    </div>
                    <div class="card-body">
                        @if (@$faq->id)
                            <form action="{{ route('faq.update', @$faq->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('faq.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title <span class="text text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" required
                                        value="{{ @$faq->title }}" placeholder="Enter faq title">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12 col-12">
                                <label for="description" class="form-label">Description <span class="text text-danger">*</span></label>
                                <textarea name="description" id="description" class="form-control" required>{{ old('description', @$faq->description) }}</textarea>
                                @error('description')
                                    <span class="text text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Meta Title</label>
                                    <input type="text" class="form-control" id="metatitle" name="meta_title"
                                        value="{{ @$faq->meta_title }}" placeholder="Enter Meta Title">
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12 col-12">
                                <label for="description" class="form-label">Meta Description</label>
                                <textarea name="meta_description" id="meta_description" class="form-control">{{ old('meta_description', @$faq->meta_description) }}</textarea>  
                            </div>
                            <div class="col-xl-12 col-md-12 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                        value="{{ @$faq->meta_keywords }}" placeholder="Enter Meta Keywords">
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-4 col-12">
                                <div class="mb-1">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="0" {{ old('status',@$faq->status) == 0 ? 'selected' : '' }}>Inactive</option>
                                        <option value="1" {{ old('status',@$faq->status) == 1 ? 'selected' : '' }}>Active</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($faq->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
<script>

    var options = {
       filebrowserImageBrowseUrl: '/admin/laravel-filemanager?type=Images',
       filebrowserImageUploadUrl: '/admin/laravel-filemanager/upload?type=Images&_token=',
       filebrowserBrowseUrl: '/admin/laravel-filemanager?type=Files',
       filebrowserUploadUrl: '/admin/laravel-filemanager/upload?type=Files&_token='
   };
    CKEDITOR.replace('description', options);

</script>
@endpush