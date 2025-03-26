@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Seller Commissison</h4>
                    </div>
                    <div class="card-body">
                        @if (@$seller_commission->id)
                            <form action="{{ route('sellercommission.update', $seller_commission->id) }}" method="POST">
                                @method('PATCH')
                            @else
                                <form action="{{ route('sellercommission.store') }}" method="POST">
                        @endif
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Title</label>
                                    <input type="text" class="form-control form-control-sm" id="name" name="title"
                                        value="{{old('title', @$seller_commission->title )}}" placeholder="Enter brand name">
                                    @error('title')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="percent">Percent</label>
                                    <input type="number" class="form-control form-control-sm" id="name" name="percent"
                                        value="{{old('percent', @$seller_commission->percent )}}" placeholder="Enter brand name" min="1">
                                    @error('percent')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="category_id">Category</label>
                                    <select name="category_id[]" id="category_id" class="select2" multiple>
                                        @foreach ($category as $cat)
                                            <option value="{{$cat->id}}" {{@$seller_commission ? (in_array($cat->id,@$seller_commission->category->pluck('category_id')->toArray())) ? 'selected':'' : null}}>{{$cat->title}}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="brand_id">Brand</label>
                                    <select name="brand_id[]" id="brand_id" class="select2" multiple>
                                        @foreach ($brand as $br)
                                        <option value="{{$br->id}}" {{@$seller_commission ? (in_array($br->id,@$seller_commission->brand->pluck('brand_id')->toArray())) ? 'selected':'' :null}}>{{$br->name}}</option>
                                    @endforeach
                                    </select>
                                    @error('brand_id')
                                        <p class="form-control-static text-danger" id="staticInput">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                        </div>
                        <x-dashboard.button :create="isset($seller_commission->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
