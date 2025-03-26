@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Featured Section</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('featured-section-product.store', $featuredSection) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Please Choose Featured Section</label>
                                    <select name="featured_section_id" id="featured_Section" class="form-control form-control-sm">
                                            <option value="">Please Select</option>
                                            @foreach ($featured_sections as $featured_section)
                                            <option value="{{$featured_section->id}}" {{(@$type == $featured_section->type) ? 'selected' : ''}}>
                                                {{$featured_section->title}}
                                            </option>
                                            @endforeach
                                    </select>
                                    <span class="text-danger">{{$errors->first('featured_section')}}</span>
                                </div>
                            </div>
                            {{-- @dd($foreign_data) --}}
                            {{-- @dd($featuredData->featured->pluck('foreign_id')->toArray()) --}}
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">Select Data</label>
                                    <select name="foreign_id[]" id="foreign_id" class="form-control form-control-sm select2" multiple>
                                        <option value="">Please Select</option>

                                        @foreach ($foreign_data as $foreign)
                                        <option value="{{$foreign->id}}" {{in_array(@$foreign->id,@$featuredData->featured->pluck('foreign_id')->toArray()) ? 'selected':''}}>
                                            @if($type == 1)
                                                {{$foreign->title}}
                                            @endif

                                            @if($type == 2)
                                            {{$foreign->name}}
                                            @endif
                                            @if($type == 3)
                                            {{$foreign->name}}
                                            @endif

                                        </option>

                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{$errors->first('product')}}</span>
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($featuredSectionProduct->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
