@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')
@push('style')
    <link href="{{ asset('dashboard/css/pages/app-ecommerce-details.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ asset('dashboard/css/colors.css') }}">
@endpush
@section('content')
    <div class="ecommerce-application">
        <section class="app-ecommerce-details">
            <div class="card">
                <div class="card-body">
                    <div class="row my-2">
                        <div class="col-12 col-md-5 d-flex align-items-center justify-content-center mb-2 mb-md-0">
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="{{ $product->images[0]->image ?? null }}" class="img-fluid product-img"
                                    alt="product image" />
                            </div>
                        </div>
                        <div class="col-12 col-md-7">`
                            <h4>{{ $product->name }}</h4>
                            @if ($product->brand->title !== null)
                                <span class="card-text item-company">By <a href="#"
                                        class="company-name">{{ $product->brand->title ?? '' }}</a></span>
                            @endif

                            <div class="ecommerce-details-price d-flex flex-wrap mt-1">
                                <h4 class="item-price me-1"></h4>
                                <ul class="unstyled-list list-inline ps-1 border-start">
                                    @foreach (range(1, $product->rating) as $rating)
                                        <li class="ratings-list-item"><i data-feather="star" class="filled-star"></i></li>
                                    @endforeach
                                    @for ($i = 1; $i <= 5 - $product->rating; $i++)
                                        <li class="ratings-list-item"><i data-feather="star" class="unfilled-star"></i></li>
                                    @endfor
                                </ul>
                            </div>
                            <p class="card-text">{{ $product->getTotalStock() > 0 ? 'Available' : 'Not Available' }}- <span
                                    class="text-success">In stock</span></p>

                            <p class="card-text">
                                {!! $product->short_description !!}
                            </p>
                            <hr />
                            <div class="product-color-options">
                                <h6>Available Colors</h6>
                                <ul class="list-unstyled mb-0">
                                    @foreach ($colors as $color)
                                        <li class="d-inline-block selected">
                                            <div class="color-option b-primary">
                                                <div class="filloption"
                                                    style="background-color: {{ $color['color_code'] }}"></div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="barcode-modal-left">
                                {{ QrCode::size(100)->generate(@$productBarCode) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tab with icon</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="homeIcon-tab" data-bs-toggle="tab" href="#homeIcon"
                                aria-controls="home" role="tab" aria-selected="true"><i data-feather="home"></i>
                                Description</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profileIcon-tab" data-bs-toggle="tab" href="#profileIcon"
                                aria-controls="profile" role="tab" aria-selected="false"><i data-feather="tool"></i>
                                Color And Images</a>
                        </li>
                        {{-- <li class="nav-item">
                            <a href="disabledIcon" id="disabledIcon-tab" class="nav-link disabled"><i
                                    data-feather="eye-off"></i> Disabled</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link" id="aboutIcon-tab" data-bs-toggle="tab" href="#aboutIcon"
                                aria-controls="about" role="tab" aria-selected="false"><i data-feather="user"></i>
                                Attributes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="other-tab" data-bs-toggle="tab" href="#othertab" aria-controls="other"
                                role="tab" aria-selected="false"><i data-feather="user"></i>
                                Others</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="homeIcon" aria-labelledby="homeIcon-tab" role="tabpanel">
                            {!! $product->long_description !!}
                        </div>
                        <div class="tab-pane" id="profileIcon" aria-labelledby="profileIcon-tab" role="tabpanel">
                           
                                @foreach($colors as $color)
                                <div class="row mb-2">
                                    <div class="col-md-12 mb-1">
                                        <h5 class="btn btn-sm btn-success">{{ ucfirst($color['title'])}}</h5>
                                    </div>
                                    @foreach($color['image'] as $image)
                                        <div class="col-md-2">
                                            <img src="{{$image->image}}" class="img img-fluid img-thumbnail" alt="">
                                        </div>
                                    @endforeach
                                </div>
                                @endforeach
                           
                        </div>
                        {{-- <div class="tab-pane" id="disabledIcon" aria-labelledby="disabledIcon-tab" role="tabpanel">
                            <p>
                                Chocolate croissant cupcake croissant jelly donut. Cheesecake toffee apple pie chocolate bar
                                biscuit
                                tart croissant. Lemon drops danish cookie. Oat cake macaroon icing tart lollipop cookie
                                sweet bear claw.
                            </p>
                        </div> --}}
                        <div class="tab-pane" id="aboutIcon" aria-labelledby="aboutIcon-tab" role="tabpanel">
                                <div class="tab-content-wrapper">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <tbody>
                                                <tr>
                                                    <th>Brand</th>
                                                    <td>{{ $product->brand->name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Color</th>
                                                    <td>
                                                        @foreach ($colors as $key => $color)
                                                            <span>{{ @$color['title'] }}</span>,
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                @if (count(getSpecification($product)) > 0 &&
                                                    !empty(getSpecification($product)) &&
                                                    getSpecification($product) != null)
                                                    @foreach (getSpecification($product) as $product_data)
                                                        <tr>
                                                            <th>{{ $product_data['title'] }}</th>
                                                            <td>
                                                                @foreach ($product_data['value'] as $key => $value)
                                                                    <span>{{ @$value }}</span>,
                                                                @endforeach
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="othertab" aria-labelledby="othertab" role="tabpanel">
                            <div class="tab-content-wrapper">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <th>Color</th>
                                            <th>Price</th>
                                            <th>Special Price</th>
                                            <th>Stock Qty</th>
                                        </thead>
                                        <tbody>
                                            @if (count($product->stocks) >0)
                                                @foreach ($product->stocks as $stock)
                                                    <tr>
                                                        <th>{{ @$stock->getColor->title ?? '-' }}</th>
                                                        <td>
                                                            {{ $stock->price}}
                                                        </td>
                                                        <td>
                                                            {{ $stock->special_price ?? 'null'}}
                                                        </td>
                                                        <td>
                                                            {{ ($stock->quantity >=1) ? $stock->quantity : 'Out Of Stock'}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
