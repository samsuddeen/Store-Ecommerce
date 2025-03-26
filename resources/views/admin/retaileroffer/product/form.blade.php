@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Offer Product</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('retailer_offer-top-offer-product.store', $topOfferProduct) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Please Choose Featured Section</label>
                                    <select name="top_offer_id" id="featured_Section" class="form-control form-control-sm" required>
                                            <option value="">Please Select</option>
                                            @foreach ($offers as $topOffer)
                                            <option value="{{$topOffer->id}}" {{(@$offer->id == $topOffer->id) ? 'selected' : ''}}>
                                                {{$topOffer->title}}
                                            </option>
                                            @endforeach
                                    </select>
                                    <span class="text-danger">{{$errors->first('top_offer_id')}}</span>
                                </div>
                            </div>
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">Please Select Product</label>
                                    <select name="product_id" id="product" class="form-control form-control-sm select2" required>
                                        <option value="">Please Select</option>
                                        <option value="All">All Products</option>
                                        @foreach ($products as $product)

                                        <option value="{{$product->id}}" {{(@$featuredSectionProduct->id == $product->id) ? 'selected' : ''}}>
                                            {{$product->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{$errors->first('product')}}</span>
                                </div>
                            </div>
                        </div>
                        <x-dashboard.button :create="isset($topOfferProduct->id)"></x-dashboard.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
