@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Product')
@push('style')
    <style>
        td img {
            height: 85px !important;
            display: block;
        }

        .select2-selection__arrow {
            display: none !important;
        }

        .group-btns ul {
            display: flex;
            margin: 0;
            padding: 0;
        }

        .group-btns {
            padding: 0 20px;
        }

        .group-btns ul li {
            list-style: none;
        }

        .group-btns ul li+li {
            margin-left: 7px;
        }

        .group-btns .btns {
            font-weight: 500;
            display: flex;
            align-items: center;
            font-size: 12px;
            border-radius: 0.3em;
            padding: 5px 10px;
            box-shadow: inset 0 0.08em 0 rgb(255 255 255 / 70%), inset 0 0 0.08em rgb(255 255 255 / 50%);
            text-shadow: 0 1px 0 rgb(255 255 255 / 80%);
            border: 1px solid #aaa;
            color: #050505;
            background-color: #fbfbfb;
            border-color: rgba(0, 0, 0, 0.3);
            border-bottom-color: rgba(0, 0, 0, 0.5);
            background-image: linear-gradient(rgba(255, 255, 255, .1), rgba(255, 255, 255, .05) 49%, rgba(0, 0, 0, .05) 51%, rgba(0, 0, 0, .1));
        }

        .group-btns .badge {
            background: var(--secondary-color);
            color: #ffffff;
            font-size: 10px;
            font-weight: normal;
            padding: 5px 7px;
            margin-left: 7px;
        }

        td img {
            height: 50px !important;
            width: auto;
        }

        .table-filter {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            margin-bottom: 15px;
            border-top: 1px solid #efefef;
            padding-top: 10px;
        }

        #filter-span {
            width: 250px;
        }

        .qty-count select {
            padding: 6px 10px;
            border-radius: 4px;
        }

        #filter-span .select2-container--default .select2-selection--single {
            border-radius: 0;
        }

        .me-1 {
            margin-right: 4px !important;
        }

        .btn-group>.btn {
            border-radius: 0.358rem !important;
            padding: 8px 9px !important;
        }

        .table> :not(caption)>*>* {
            padding: 10px 12px;
            border: 1px solid #e9e9e9 !important;
        }

        .btn-sm,
        .btn-group-sm>.btn {
            padding: 8px 9px;
        }

        #default-breadcrumb .group-btns a {
    background: #7367F0;
    border: none;
    color: #fff;
}
    </style>
@endpush
@section('content')
    <section id="default-breadcrumb">
        <div class="row" id="table-bordered">
            <div class="col-12">
                <!-- list and filter start -->
                <div class="card">
                    <div class="card-header d-flex justify-space-between">
                        <h4 class="card-title text-capitalize">Product</h4>
                        <div class="div">
                            <a href="{{ route('product.create') }}" id="" class="btn btn-sm btn-secondary me-1"
                                role="button">
                                create
                            </a>
                        </div>
                    </div>
                    <div class="group-btns mb-2">
                        <ul>
                            <li>
                                <a href="#" class="btns btn-tabs" data-type="1" role="button">
                                    All <span class="badge">{{ $counts['all'] }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btns btn-tabs" data-type="2" role="button">
                                    Online/Live <span class="badge">{{ $counts['online'] }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btns btn-tabs" data-type="3" role="button">
                                    Pending <span class="badge">{{ $counts['pendings'] }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btns btn-tabs" data-type="4" role="button">
                                    In Stocks <span class="badge">{{ $counts['in_stocks'] }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btns btn-tabs" data-type="5" role="button">
                                    Out Of Stock <span class="badge">{{ $counts['out_of_stocks'] }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btns btn-tabs" data-type="6" role="button">
                                    Suspended <span class="badge">{{ $counts['suspended'] }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" class="btns btn-tabs" data-type="7" role="button">
                                    Deleted <span class="badge">{{ $counts['deleted'] }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="bulk-action">
                        <a href="{{ route('product.get-import') }}" class="btn btn-sm btn-primary float-end me-1">Import</a>
                    </div>
                    <form action="{{ route('product.index') }}" id="filter-form" method="get">
                        <div class="table-filter">
                            <div class="qty-count">
                                <div class="form-group">
                                    <select name="per" id="per">
                                        <option value="10" {{ (int) $filters['per'] == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="25" {{ (int) $filters['per'] == 25 ? 'selected' : '' }}>25
                                        </option>
                                        <option value="100" {{ (int) $filters['per'] == 100 ? 'selected' : '' }}>100
                                        </option>
                                        <option value="all" {{ $filters['per'] == 'all' ? 'selected' : '' }}>All
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-filter-form">
                                <input type="text" id="type" name="type" value="{{ $filters['type'] ?? '1' }}"
                                    hidden>
                                <input type="text" id="filter-type" name="filter_type"
                                    value="{{ $filters['filter_type'] }}" hidden>
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" id="show-filter-type" aria-expanded="false">
                                        @if ((int) $filters['filter_type'] === 1)
                                            Category
                                        @elseif((int) $filters['filter_type'] === 2)
                                            Product ID
                                        @elseif((int) $filters['filter_type'] === 3)
                                            Product Name
                                        @elseif((int) $filters['filter_type'] === 4)
                                            Seller SKU
                                        @elseif((int) $filters['filter_type'] === 5)
                                            Seller
                                        @endif
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item filter-type" data-type="1" href="#">Category</a>
                                        </li>
                                        <li><a class="dropdown-item filter-type" data-type="2" href="#">Product
                                                ID</a></li>
                                        <li><a class="dropdown-item filter-type" data-type="3" href="#">Product
                                                Name</a></li>
                                        {{-- <li><a class="dropdown-item filter-type" data-type="4" href="#">Seller SKU</a></li> --}}
                                        <li><a class="dropdown-item filter-type" data-type="5" href="#">Seller</a>
                                        </li>
                                    </ul>
                                    <span id="filter-span">
                                        @if ((int) $filters['filter_type'] === 1)
                                            <select name="search_string" id="category"
                                                class="form-control form-control-sm select2">
                                                <option value="">Please Select</option>
                                                @foreach ($category as $areas)
                                                    <option value="{{ $areas->id }}"
                                                        {{ (int) $filters['search_string'] == $areas->id ? 'selected' : '' }}>
                                                        {{ $areas->title }}
                                                        ({{ count($areas->products) ?? 0 }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif((int) $filters['filter_type'] === 5)
                                            <select name="search_string" id="category"
                                                class="form-control form-control-sm select2">
                                                <option value="">Please Select</option>
                                                @foreach ($sellers as $seller)
                                                    <option value="{{ $seller->id }}"
                                                        {{ (int) $filters['search_string'] == $seller->id ? 'selected' : '' }}>
                                                        {{ $seller->name }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" name="search_string"
                                                class="form-control form-control-sm"
                                                value="{{ $filters['search_string'] ?? null }}" />
                                        @endif
                                    </span>
                                    <button class="btn btn-primary" id="filter-btn" type="submit">Filter</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="card-body border-bottom p-0">
                        <div class="card-datatable table-responsive pt-0">
                            <table class="product-list-table table">
                                <thead class="table-light text-capitalize">
                                    <tr>
                                        {{-- <th><input type="checkbox" name="select_all" id="select_all"></th> --}}
                                        <th></th>
                                        <th style="width:30px;">S.N</th>
                                        <th>Image</th>
                                        <th>name</th>
                                        <th>category</th>
                                        <th>stock</th>
                                        <th>Publish</th>
                                        <th>Status</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-capitalize">
                                    @php
                                        $i = 1;
                                    @endphp
                                    @forelse($products as $index=>$product)
                                        <tr>
                                            <td><input type="checkbox" name="particular_select"
                                                    class="particular_select"></td>
                                            <td>{{ $i++ }}</td>
                                            <td>
                                                <a href="{{ route('product.show', $product->id) }}" title="">
                                                    <img src="{{ getFeaturedImage($product) ?? asset('dummyimage.png')}}" alt='Image Not Found'
                                                        class='img-fluid'>
                                                </a>
                                            </td>
                                            <td>
                                                {!! getProductName($product) !!}
                                            </td>
                                            <td>
                                                <a href="{{ route('category.showcat', $product->category->id ?? 0) }}">
                                                    {{ substr($product->category->title, 0, 10) }}...</a>
                                            </td>
                                            <td>
                                                {{ $product->getTotalStock() }}
                                            </td>
                                            <td>
                                                @if ($product->publishStatus)
                                                    <a href="{{ route('update.product.status', $product->id) }}"> <span
                                                            class="badge bg-primary success-status">Yes</span> </a>
                                                @else
                                                    <a href="{{ route('update.product.status', $product->id) }}"> <span
                                                            class="badge bg-danger pending-status" style="background: #ea5455 !important">No</span> </a>
                                                @endif
                                            </td>
                                            <td>
                                                {!! getStartupStatus($product) !!}
                                            </td>
                                            <td>
                                                {!! getProductFinalAction($product) !!}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>
                                                <center>No Record Found</center>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if ($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                <div class="mt-2">
                                    {{ $products->links() }}
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal fade" id="shareProject" tabindex="-1" aria-labelledby="shareProjectTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-transparent">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body px-sm-5 mx-50 pb-5">
                        <h1 class="text-center mb-1" id="addNewCardTitle">Are You Sure </h1>
                        <p class="text-center">Please Confirm</p>
                        <form class="row gy-1 gx-2 mt-75" method="post" action="{{ route('product.status') }}">
                            @csrf
                            @method('PATCH')
                            <div class="col-12" style="display: none" id="reason-box">
                                <label class="form-label" for="modalAddCardNumber">Reason/Remarks <span
                                        class="text-danger">*</span></label>
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
    reloadAction();
    function reloadAction() {
        
        $('.order-action').on('click', function() {
            var status=$(this).data('type');
            var product_id=$(this).data('product_id');
            $('#customer_id-input').val($(this).data('seller_id'));
            $('#type-input').val(status);
            $('#order_id-input').val(product_id);
        });
    }
</script>
    <script>
        $('#per').on('change', function(e) {
            e.preventDefault();
            let per = $(this).val();
            $('#filter-form').submit();
        });

        $('.btn-tabs').on('click', function(e) {
            e.preventDefault();
            $('#type').val($(this).data('type'));
            $('#filter-form').submit();
        });
        $('.filter-type').on('click', function(e) {
            $('#show-filter-type').text($(this).text());
            let filterType = $(this).data('type');
            $('#filter-type').val(filterType);
            html_string = "";
            if (parseInt(filterType) == 1) {
                html_string =
                    '<select name="search_string" class="form-control form-control-sm select2"><option value="" id="categoryvalue">Please Select</option>';
                @foreach ($category as $areas)
                    html_string += '<option value="{{ $areas->id }}">{{ $areas->title }}</option>';
                @endforeach
                html_string += '</select>';
            } else if (parseInt(filterType) == 5) {
                html_string =
                    '<select name="search_string" class="form-control form-control-sm select2"><option value="" id="categoryvalue">Please Select</option>';
                @foreach ($sellers as $seller)
                    html_string += '<option value="{{ $seller->id }}">{{ $seller->name }}</option>';
                @endforeach
                html_string += '</select>';
            } else {
                html_string = '<input type="text" name="search_string" class="form-control form-control-sm" />';
            }
            $('#filter-span').html(html_string);
        });
    </script>
@endpush