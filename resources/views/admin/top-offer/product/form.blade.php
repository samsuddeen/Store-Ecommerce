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
                        <form action="{{ route('retailer_offer-top-offer-product.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="title">Please Choose Retailer</label>
                                    <select name="retailer_id[]" id="retailer_id" class="form-control form-control-sm select2" required multiple>
                                            <option value="">Please Select</option>
                                            @foreach ($retailers as $retailer)
                                            <option value="{{$retailer->id}}" {{in_array($retailer->id,$retailerList->pluck('retailer_id')->toArray()) ? 'selected':''}}>
                                                {{$retailer->name}}
                                            </option>
                                            @endforeach
                                    </select>
                                    <span class="text-danger">{{$errors->first('top_offer_id')}}</span>
                                </div>
                            </div>
                            <input type="text" name="retailer_offer_section" hidden value="{{$retailerOfferSection->id}}">
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">Please Select Product</label>
                                    <select name="product_id[]" id="product" class="form-control form-control-sm select2" required multiple>
                                        <option value="">Please Select</option>
                                        @foreach ($products as $product)
                                        <option value="{{$product->id}}" {{in_array($product->id,$productList->pluck('product_id')->toArray()) ? 'selected':''}}>
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
