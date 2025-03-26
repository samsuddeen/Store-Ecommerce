@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')

@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Pages</h4>
                    </div>
                    <div class="card-body">
                        @if ($page->id)
                            <form action="{{ route('page.update', $page->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('page.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="title"
                                        value="{{old('title', $page->title )}}" placeholder="Enter Page name">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="status">Status</label>
                                    <select name="status" id="status" class="form-control form-control-sm"  value="{{old('status', $page->status )}}" required>
                                        <option value="" selected>-----------Select Any One---------</option>
                                        <option value="active"  {{($page->status=='active') ?'selected':''}}>Active</option>
                                        <option value="inactive" {{ ($page->status=='inactive') ?'selected':''}}>In-Active</option>
                                    </select>
                                    @error('status')
                                    <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-1">
                                        <label class="form-label" for="description">Description</label>
                                        <textarea name="description" id="description" class="form-control" rows="3">{{old('description', @$page->description)}}</textarea>
                                        @error('description')
                                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <x-filemanager name='image' :value="$page->image"></x-filemanager>
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($page->id)"></x-dashboard.button>
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
      filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
      filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
      filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
      filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
    };
  </script>
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('description', options);
        </script>
@endpush
