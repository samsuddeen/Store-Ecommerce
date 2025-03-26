@extends('layouts.app')
@isset($brand->id)
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Update Brand')
@else
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Create Brand')
@endif

@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">brand</h4>
                    </div>
                    <div class="card-body">
                        @if ($brand->id)
                            <form action="{{ route('brand.update', $brand->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('brand.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row createbrand-dash">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $brand->name }}" placeholder="Enter brand name">
                                    @error('name')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1 brandlogo-dash">
                                    <x-filemanager name='logo' :value="$brand->logo"></x-filemanager>
                                </div>
                            </div>
                           
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Status</label>
                                    {{ Form::select('status',[1=>'Active',0=>'In-Active'],@$brand->status,['class'=>'select2 form-select','required'=>true,'placeholder'=>'------------Select Any One-----------'])}}
                                    @error('status')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>
                        
                        <x-dashboard.button :create="isset($brand->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
