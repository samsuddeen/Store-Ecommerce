@extends('layouts.app')
@section('content')
    <section id="basic-input">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Selected Product</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('store.selectedProduct') }}" method="POST">
                        @csrf
                        <div class="row">
                            
                            <div class="col-xl-4 col-md-6 col-12">
                                <div class="mb-1">
                                    <label class="form-label" for="count">Please Select Product</label>
                                    <select name="product_id[]" id="product" class="form-control form-control-sm select2" multiple required>
                                        <option value="">Please Select</option>
                                        @foreach ($products as $product)

                                        <option value="{{$product->id}}" {{in_array($product->id,@$selectedProducts) ? 'selected':''}}>
                                            {{$product->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
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
