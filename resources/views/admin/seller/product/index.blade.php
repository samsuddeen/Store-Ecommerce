@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Product')
@push('style')
    <style>
        td img {
            height: 85px !important;
            display: block;
        }
        .select2-selection__arrow{
            display: none !important;
        }
    </style>
@endpush
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">
                    <div class="me-2 ms-2 mt-2">
                        <a href="#" class="btn btn-sm btn-primary me-1 btn-tabs" data-type="1" role="button">
                            All <span class="badge" style="background-color: blueviolet">{{$counts['all']}}</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-secondary me-1 btn-tabs" data-type="2" role="button">
                            Online/Live <span class="badge" style="background-color: blueviolet">{{$counts['online']}}</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-info me-1 btn-tabs" data-type="3" role="button">
                            Pending <span class="badge" style="background-color: blueviolet">{{$counts['pendings']}}</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger me-1 btn-tabs" data-type="4" role="button">
                            In Stocks <span class="badge" style="background-color: blueviolet">{{$counts['in_stocks']}}</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger me-1 btn-tabs" data-type="5" role="button">
                            Out Of Stock <span class="badge" style="background-color: blueviolet">{{$counts['out_of_stocks']}}</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-warning me-1 btn-tabs" data-type="6" role="button">
                            Suspended <span class="badge" style="background-color: blueviolet">{{$counts['suspended']}}</span>
                        </a>
                        <a href="#" class="btn btn-sm btn-danger me-1 btn-tabs" data-type="7" role="button">
                            Deleted <span class="badge" style="background-color: blueviolet">{{$counts['deleted']}}</span>
                        </a>
                    </div>
                    <div class="card-header d-flex justify-space-between">
                        <h4 class="card-title text-capitalize">Product</h4>
                        <div class="div">
                            <a href="{{ route('seller-product.create') }}" id="" class="btn btn-sm btn-primary me-1" role="button">
                                create
                            </a>
                        </div>
                    </div>
                    <div class="row me-2 ms-2">
                        <form action="{{route('seller-product.index')}}" id="filter-form" method="get">
                            <input type="text" id="type" name="type" value="{{$filters['type'] ?? '1'}}" hidden>
                            <input type="text" id="filter-type" name="filter_type" value="{{$filters['filter_type']}}" hidden>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm">
                                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" id="show-filter-type" aria-expanded="false">
                                            @if((int)$filters['filter_type'] === 1)
                                            Category
                                            @elseif((int)$filters['filter_type'] === 2)
                                            Product ID
                                            @elseif((int)$filters['filter_type'] === 3)
                                            Product Name
                                            @elseif((int)$filters['filter_type'] === 4)
                                            Seller SKU
                                            @elseif((int)$filters['filter_type'] === 5)
                                            Seller
                                            @endif
                                        </button>
                                        <ul class="dropdown-menu">
                                          <li><a class="dropdown-item filter-type" data-type="1" href="#">Category</a></li>
                                          <li><a class="dropdown-item filter-type" data-type="2" href="#">Product ID</a></li>
                                          <li><a class="dropdown-item filter-type" data-type="3" href="#">Product Name</a></li>
                                          {{-- <li><a class="dropdown-item filter-type" data-type="4" href="#">Seller SKU</a></li> --}}
                                          {{-- <li><a class="dropdown-item filter-type" data-type="5" href="#">Seller</a></li> --}}
                                        </ul>
                                        <span  class="form-control form-control-sm" id="filter-span">
                                            @if((int)$filters['filter_type'] === 1)
                                            <select name="search_string" id="category" class="form-control form-control-sm select2">
                                                <option value="">Please Select</option>
                                                @foreach ($category as $areas)
                                                    <option value="{{ $areas->id }}" {{((int)$filters['search_string'] == $areas->id) ? 'selected' : ''}}>{{ $areas->title }}</option>
                                                @endforeach
                                            </select>
                                            @else
                                                <input type="text" name="search_string" class="form-control form-control-sm" value="{{$filters['search_string'] ?? null}}" />
                                            @endif
                                        </span>
                                    </div>  
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-primary" id="filter-btn"
                                    type="submit">Filter</button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group mt-2">
                                    <select name="per" id="per">
                                        <option value="10"   {{((int)$filters['per'] == 10) ? 'selected' : ''}}>10</option>
                                        <option value="25"   {{((int)$filters['per'] == 25) ? 'selected' : ''}}>25</option>
                                        <option value="100"   {{((int)$filters['per'] == 100) ? 'selected' : ''}}>100</option>
                                        <option value="all"   {{($filters['per'] == 'all') ? 'selected' : ''}}>All</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="card-body border-bottom p-0">                        
                        <div class="card-datatable table-responsive pt-0">
                            <table class="product-list-table table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        <th><input type="checkbox" name="select_all" id="select_all"></th>
                                        <th>S.N</th>
                                        <th>Status</th>
                                        <th>name</th>
                                        {{-- <th>category</th> --}}
                                        <th>stock</th>
                                        <th>Publish</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">
                                    @php
                                      $i = 1;
                                    @endphp
                                    @forelse($products as $index=>$product)
                                    
                                        <tr>
                                            <td><input type="checkbox" name="particular_select" class="particular_select"></td>
                                            <td>{{$i ++}}</td>
                                            <td>
                                                {!! getStartupStatus($product) !!}
                                            </td>
                                            <td>
                                                {!! getProductName($product) !!}
                                            </td>
                                            {{-- <td>
                                                <a href="{{route('category.show', $product->category->id ?? 0)}}"> {{ substr($product->category->title, 0, 10) }}...</a>
                                            </td> --}}
                                            <td>
                                                {{$product->getTotalStock()}}
                                            </td>
                                            <td>
                                                @if(($product->publishStatus))
                                                <a href="{{route('seller-product-status',[$product->id,true])}}"> <span class="badge bg-primary">Yes</span> </a>
                                                @else
                                                <a href="{{route('seller-product-status',[$product->id,2])}}"> <span class="badge bg-danger" style="background-color:#ea5455 !important">No</span> </a>
                                                @endif
                                            </td>
                                            <td>
                                                {!! getProductFinalAction($product) !!}
                                            </td>
                                        </tr>
                                    @empty
                                    <tr>
                                        <td><center>No Record Found</center></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator )
                            {{ $products->links() }}
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>





        <div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure </h1>
                        <p class="text-center">Please Confirm</p>
                        <form class="row gy-1 gx-2 mt-75" method="post" action="{{route('seller-product.status')}}">
                            @csrf
                            @method("PATCH")
                            <div class="col-12" style="display: none" id="reason-box">
                                <label class="form-label" for="modalAddCardNumber">Reason/Remarks <span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                   <textarea name="remarks" id="remarks" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <input type="text" name="type" id="type-input" hidden>
                            <input type="text" name="product_id" id="order_id-input" hidden>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-1 mt-1">Submit</button>
                                <button type="reset" class="btn btn-outline-secondary mt-1" data-bs-dismiss="modal"
                                    aria-label="Close">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
<script>
    $('#per').on('change', function(e){
        e.preventDefault();
        let per = $(this).val();
        $('#filter-form').submit();
    });

    $('.btn-tabs').on('click', function(e){
        e.preventDefault();
        $('#type').val($(this).data('type'));
        $('#filter-form').submit();
    });
    $('.filter-type').on('click', function(e){
        $('#show-filter-type').text($(this).text());
        let filterType = $(this).data('type');
        $('#filter-type').val(filterType);
        html_string = "";
        if(parseInt(filterType) ==1 ){
            html_string = '<select name="search_string" class="form-control form-control-sm select2"><option value="" id="categoryvalue">Please Select</option>';
                @foreach ($category as $areas)
                    html_string +='<option value="{{ $areas->id }}">{{ $areas->title }}</option>';
                @endforeach
            html_string +='</select>';
        }else{
            html_string = '<input type="text" name="search_string" class="form-control form-control-sm" />';
        }
        $('#filter-span').html(html_string);
    });
</script>
@endpush
