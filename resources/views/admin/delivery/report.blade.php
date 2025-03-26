@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Order Delivery Report')
@push('style')
    <style>
        td img {
            height: 85px !important;
            display: block;
        }
    </style>
@endpush
@section('content')


    <section id="default-breadcrumb">
        @if(!Auth::user()->hasRole('delivery'))
            <form action="#" id="filter-form">
                <div class="row m-2">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select name="status" class="form-control" id="status">
                                <option value="">Select status</option>
                                <option value="Assigned">Assigned</option>
                                <option value="In-Progress">In-Progress</option>
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" class="form-control" name="location" id="location" placeholder="City Name">
                        </div>
                    </div> --}}
                    {{-- <div class="col-md-3">
                        <div class="form-group">
                            <input type="date" class="form-control" name="date" id="date">
                        </div>
                    </div> --}}
                </div>
            </form>
        @endif
        {{-- <div class="row me-2 ms-2">
            <form action="#" id="filter-form" method="get">
                <div class="row">
                    <div class="col-md-3 province">
                        <div class="form-group areas">
                            <label for="province">Province:</label>
                            <select name="province" id="province" class="form-control form-control-sm select2 province_dd">
                                <option value=" ">Please Select</option>
                                @foreach ($province as $provinces)
                                <option value="{{$provinces->id}}" data-type="{{ $provinces['province'] }}">{{$provinces->eng_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 district">
                        <div class="form-group">
                            <label for="Areas">District:</label>
                            <select name="district" id="district" class="form-control form-control-sm select2 district_dd">
                                <option value=" " selected>Please Select</option>
                                @foreach ($district as $districts)
                                <option value="{{$districts->id}}" data-type="{{ $districts['district'] }}">{{$districts->np_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 area">
                        <div class="form-group">
                            <label for="Areas">Area:</label>
                            <select name="area" id="area" class="form-control form-control-sm select2 area_dd">
                                <option value=" ">Please Select</option>
                                @foreach ($areas as $area)
                                <option value="{{$area->area}}" data-type="{{ $area['area'] }}">{{$area->area}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="users">Year:</label>
                            <select name="year" id="year" class="form-control form-control-sm select2">
                                <option value="">Please Select</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year }}" data-type="{{ $year }}" {{(@$filters['year'] == $year) ? 'selected' : ''}}>
                                        {{ $year }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="users">Month:</label>
                            <select name="month" id="month" class="form-control form-control-sm select2">
                                <option value="">Please Select</option>
                                @foreach ($months as $index => $month)
    
                                    <option value="{{ $month['value'] }}" data-type="{{ $month['value'] }}" {{(@$filters['month'] == $month['value']) ? 'selected' : ''}}>
                                        {{ $month['title'] }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm mt-2" id="filter-btn" type="button">Filter</button>

                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="d-flex">
                            <a href="{{route('orderreportexcel')}}" class="global-btn pdfbilled my-1" style="margin-left:auto;">Export
                                (CSV)</a>   
                        </div>
                    </div>

                </div>
            </form>
        </div> --}}
        <div class="row" id="table-bordered">
            <div class="card">
                <div class="card-body border-bottom">
                    <div class="card-datatable table-responsive pt-0">
                        <table class="orde-list-table table data-table">
                            <thead class="table-light text-capitalize">
                                <tr>
                                    <th>S.N</th>
                                    <th>Order ID</th>
                                    <th>Total Price</th>
                                    <th>Location</th>
                                    @if(!Auth::user()->hasRole('delivery'))
                                    <th>Assigned To</th>
                                    @endif
                                    <th>Delivery Status</th>
                                    <th>Completed Date / Time</th>

                                </tr>
                            </thead>
                            <tbody class="text-capitalize" id="reportList">
                                @include('admin.delivery.partials.table-body')
                            </tbody>
                        </table>
                        <div class="reportPagination">
                            @include('admin.delivery.partials.pagination')
                        </div>
                    </div>
                </div>


            </div>
            <!-- list and filter end -->
        </div>
    </section>

@endsection

@push('script')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>


    <script>
        $(document).ready(function() {

            $('.municipality').show();
            // $('.province').hide();
            // $('.district').hide();

            $('.address').on('change', function() {
                var datas = $(this).find(':selected').val();
                if (datas == 1) {
                    $('.municipality').show();
                    $('.province').hide();
                    $('.district').hide();


                } else if (datas == 2) {
                    $('.municipality').hide();
                    $('.province').hide();
                    $('.district').show();
                } else {
                    $('.municipality').hide();
                    $('.province').show();
                    $('.district').hide();
                }

            });
        });
    </script>

    <script>
        $.ajax({
            type: "GET",
            url: "{{ route('order-report.index') }}",
            success: function(response) {
                $.each(response, function(indexInArray, valueOfElement) {
                    console.log(valueOfElement[0]);
                });
            }
        });
    </script>

    <script>
        $(document).ready(function(){
            let status = '';
            let location = '';
            let date = '';
        
            $(document).on('change','#status', function(){
                status = $(this).val();
                if(status){
                    filterReport();
                }
            });

            $(document).on('change','#date', function(){
                date = $(this).val();
                if(date)
                {
                    filterReport();
                }
            });


            function filterReport(page = 1){
                if(status)
                {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('get_delivery_report') }}",
                        data:{
                            status:status
                        },
                        dataType: "json",
                        success: function(response)
                        {
                            $('#reportList').empty().html(response.html);
                            if(response.pagination)
                            {   
                                $('.reportPagination').empty().html(response.pagination);
                            }
                        }
                    });
                }
                // else if(date){
                //     $.ajax({
                //         type: "GET",
                //         url: "{{ route('get_delivery_report') }}",
                //         data:{
                //             date:date           
                //         },
                //         dataType: "json",
                //         success: function(response)
                //         {
                //             $('#reportList').empty().html(response.html);
                //             if(response.pagination)
                //             {   
                //                 $('.reportPagination').empty().html(response.pagination);
                //             }
                //         }
                //     }); 
                // }
            }
        });
    </script>
@endpush
