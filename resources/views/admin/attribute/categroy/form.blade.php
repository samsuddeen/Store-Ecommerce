@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Attributes')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Attributes</h4>
                    </div>
                    <div class="card-body">
                        @if (@$attribute->id)
                            <form action="{{ route('attribute-category.update', @$attribute->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('attribute-category.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-5 col-md-5 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title <span class="text text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title"
                                        value="{{ @$faq->title }}" placeholder="Enter faq title">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-5 col-md-5 col-12">
                                <div class="row">
                                    <div class="col-xl-10 col-md-10 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="title">Value <span class="text text-danger">*</span></label>
                                            <input type="text" class="form-control" id="title" name="value[]"
                                            placeholder="Enter Value">
                                        </div>
                                    </div>
                                    <div class="col-xl-2 col-md-2 col-12" style="display:flex;align-items:center;">
                                        <div class="">
                                            <button type="button" class="btn btn-primary btn-sm addRowBtn">+</button>
                                        </div>
                                    </div>
                                    <div class="valueRows">

                                        
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="row">
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
<script>
    $(document).ready(function(){
        $(document).on('click','.addRowBtn',function(){
            $('.valueRows').append(`<div class="row">
                                        <div class="col-xl-10 col-md-10 col-12">
                                            <div class="mb-1">
                                                <label class="form-label" for="title">Value <span class="text text-danger">*</span></label>
                                                <input type="text" class="form-control" id="title" name="value[]"
                                                placeholder="Enter Value">
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-2 col-12" style="display:flex;align-items:center;">
                                            <div class="">
                                                <button type="button" class="btn btn-danger btn-sm removeRowBtn">-</button>
                                            </div>
                                        </div>
                                    </div>
            `);
        });

        $(document).on('click','.removeRowBtn',function(){
            $(this).parent().parent().parent().hide();
        });
    });
</script>
@endpush