@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tag</h4>
                    </div>
                    <div class="card-body">
                        @if ($tag->id)
                            <form action="{{ route('tag.update', $tag->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('tag.store') }}" method="POST">
                        @endif
                        @csrf
                        
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="title"
                                        value="{{old('title', $tag->title )}}" placeholder="Enter tag name" required>
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Content</label>
                                    <textarea name="description" id="description" class="form-control" rows="3">{{old('description', @$tag->description)}}</textarea>
                                    @error('description')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <x-filemanager name='image' :value="$tag->image" required></x-filemanager>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <label class="form-label" for="publishStatus">Status</label>
                            {{ Form::select('publishStatus',[1=>'Active',0=>'In-Active'],@$tag->publishStatus,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                           
                            @error('publishStatus')
                                <p class="form-control-static text-danger" id="publishStatus">{{ $message }}</p>
                            @enderror
                        </div>
                        <x-dashboard.button :create="isset($tag->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
