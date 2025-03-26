@extends('layouts.app')
@isset($category->id)
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Update Category')
@else
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Create Category')
@endif



@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Icons</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body">
                         
                                <div class="app-content content ">
                                    <div class="content-overlay"></div>
                                    <div class="header-navbar-shadow"></div>
                                    <div class="content-wrapper container-xxl p-0">
                                        <div class="content-header row">
                                            <div class="content-header-left col-md-9 col-12 mb-2">
                                                <div class="row breadcrumbs-top">
                                                    <div class="col-9">
                                                        <h2 class="content-header-title float-start mb-0">Feather Icons</h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content-body">
                                            <!-- Feather icons section start -->
                                            <section id="feather-icons">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="icon-search-wrapper my-3 mx-auto">
                                                            <div class="mb-1 input-group input-group-merge">
                                                                <span class="input-group-text"><i data-feather="search"></i></span>
                                                                <input type="text" class="form-control" id="icons-search" placeholder="Search Icons..." />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- ICON KO LAGI YO USE HUNCHA -->
                                              
                                                <div class="d-flex flex-wrap" id="icons-container"></div>
                                            </section>
                                            <!-- Feather icon-s section end -->
                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                          </div>
                        </div>
                      </div>

                    <div class="card-header">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab"
                                    data-bs-target="#general" type="button" role="tab" aria-controls="general"
                                    aria-selected="true">General Info</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                    type="button" role="tab" aria-controls="seo" aria-selected="false">SEO
                                    Info</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        @if (isset($category->id))
                            <form action="{{ route('category.update', $category->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('category.store') }}" method="POST">
                        @endif
                        @csrf

                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="general" role="tabpanel"
                                aria-labelledby="general-tab">
                                <div class="row">
                                    <div class="col-md-4 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="title">Title</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                value="{{ $category->title }}" placeholder="Enter category title">
                                            @error('title')
                                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <div class="mb-1">
                                            <label class="form-label" for="title">Icon</label>
                                            <input type="text" class="form-control" id="icons" name="icon"
                                                value="{{ $category->icon }}" placeholder="Icon"  >
                                            @error('icon')
                                                <p class="form-control-static text-danger" id="staticInput">{{ $message }}
                                                </p>
                                            @enderror
                                        </div>
                                        <div role="iconpicker"></div>
                                    </div>

                                    <div class="col-md-4 col-12">
                                        <label class="form-label" for="select2-basic">Parent Category</label>
                                        <select class="select2 form-select" id="select2-basic" name="parent_id">
                                            <option value="">Root</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $item->id == $category->parent_id ? 'selected ' : null }}>
                                                    {{ $item->title }}</option>
                                            @endforeach
                                        </select>
                                        @error('parent_id')
                                            <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-4 col-12">
                                        <x-filemanager :value="$category->image"></x-filemanager>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label class="form-label" for="status">Status</label>
                                        {{ Form::select('status',[1=>'Active',0=>'In-Active'],@$category->status,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                                       
                                        @error('status')
                                            <p class="form-control-static text-danger" id="status">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-2 form-group">
                                        <label class="product-form-label">Show On Home Page</label>
                                    </div>

                                    <div class="col-sm-10 form-group radio-dashboard">

                                        <label class="ui-radio ui-radio-primary">
                                            @if ($category->showOnHome == 0)
                                                <input type="radio" name="showOnHome" value="0" checked="checked">
                                            @else
                                                <input type="radio" name="showOnHome" value="0">
                                            @endif
                                            <span class="input-span"></span>
                                            No
                                        </label>
                                        <label class="ui-radio ui-radio-primary">
                                            @if ($category->showOnHome == 1)
                                                <input type="radio" name="showOnHome" value="1" checked="checked">
                                            @else
                                                <input type="radio" name="showOnHome" value="1">
                                            @endif
                                            <span class="input-span"></span>
                                            Yes
                                        </label>
                                    </div>
                                </div>

                                <Attribute category='{{ $category->id }}'></Attribute>
                            </div>
                            <div class="tab-pane fade show" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <inputelement name="meta_title" placeholder="Macbook Pro 2019"
                                                value="{{ old('meta_title', @$category->meta_title) }}"
                                                :errors="{{ json_encode($errors->toArray()) }}">
                                            </inputelement>

                                            
                                        </div>
                                        <div class="col-md-6 seo-ogimage">
                                            <x-filemanager :value="@$category->og_image" :name="'og_image'">
                                            </x-filemanager>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-1">
                                                <label class="form-label" for="name">Meta
                                                    Keywords:</label><small class="text-danger">*</small>
                                                <textarea class="form-control" rows="4" name="meta_keywords">{{ old('meta_keywords', @$category->meta_keywords) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-1">
                                                <label class="form-label" for="name">Meta Description:</label><small
                                                    class="text-danger">*</small>
                                                <textarea class="form-control" rows="4" name="meta_description">{{ old('meta_description', @$category->meta_description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($category->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
<script>
    $(document).ready(function() {
         
    $('#exampleModal .close').click(function() {
        $('#exampleModal').modal('hide');
        
    });

    $('#exampleModal').click(function(){
       $(this).modal('hide');
    });
    $('.dark-layout .header-navbar .navbar-container .nav .nav-item .nav-link').click(function(){
        $('.navbar-dark')
    });
    var searchBar = document.getElementById('icons-search');
    searchBar.addEventListener('click', function (event) {
      event.stopPropagation();
    });
});
</script>
<style>
    .radio-dashboard label {
    margin: 0 5px;
}
#exampleModal .content-wrapper {
    padding: 0 !important;
}
</style>
@endpush
