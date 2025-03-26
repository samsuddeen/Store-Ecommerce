@extends('layouts.app')
@section('title', env('DEFAULT_TITLE') . ' | ' . 'Seller | Dashboard')
@section('content')
    <!-- BEGIN: Content-->
    <div class="content-header row">
    </div>
    <div class="content-body">
        <!-- /dashboard Ecommerce Starts -->
        <section id="/dashboard-ecommerce">
            <div class="row match-height">
                <div class="col-xl-8 col-md-6 col-12">
                    <div class="card card-statistics">
                        <div class="card-header">
                            <h4 class="card-title">Statistics</h4>
                            <div class="d-flex align-items-center">
                                <p class="card-text font-small-2 me-25 mb-0">Updated 1 month ago</p>
                            </div>
                        </div>
                        <div class="card-body statistics-body">
                            <div class="row">
                                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-primary me-2">
                                            <div class="avatar-content">
                                                <i data-feather="trending-up" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">{{ count($total_categories) }}</h4>
                                            <p class="card-text font-small-3 mb-0"><a href="#">Category</a></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-sm-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-danger me-2">
                                            <div class="avatar-content">
                                                <i data-feather="box" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">
                                                {{ count($total_products) }}</h4>
                                            <p class="card-text font-small-3 mb-0"><a href="#">Products</a></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-info me-2">
                                            <div class="avatar-content">
                                                <i data-feather="trending-up" class="avatar-icon"></i>
                                            </div>
                                        </div>

                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">{{ count($total_delivered) }}</h4>
                                            <p class="card-text font-small-3 mb-0"><a href="#">Delivered</a></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-3 col-sm-6 col-12 mb-2 mb-xl-0">
                                    <div class="d-flex flex-row">
                                        <div class="avatar bg-light-info me-2">
                                            <div class="avatar-content">
                                                <i data-feather="trending-up" class="avatar-icon"></i>
                                            </div>
                                        </div>
                                        <div class="my-auto">
                                            <h4 class="fw-bolder mb-0">{{ count($new_orders) }}</h4>
                                            <p class="card-text font-small-3 mb-0"><a href="#">New
                                                    Order</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Statistics Card -->
            </div>


            <div class="row match-height">
                <div class="col-lg-4 col-12">
                    <div class="row match-height">
                        <!-- Bar Chart - Orders -->
                        <div class="col-lg-6 col-md-3 col-6">
                            <div class="card">
                                <div class="card-body pb-50">
                                    <h6> Total Orders </h6>
                                    <h2 class="fw-bolder mb-1"> {{ formattedNepaliNumber(count($total_orders)) }} </h2>
                                </div>
                            </div>
                        </div>
                        <!--/ Bar Chart - Orders -->

                        <!-- Line Chart - Profit -->
                        <div class="col-lg-6 col-md-3 col-6">
                            <div class="card card-tiny-line-stats">
                                <div class="card-body pb-50">
                                    <h6>Total Income</h6>
                                    <h2 class="fw-bolder mb-1">{{ formattedNepaliNumber($total_income) }}</h2>
                                </div>
                            </div>
                        </div>
                        <!--/ Line Chart - Profit -->
                        <!-- Earnings Card -->
                        {{-- <div class="col-lg-12 col-md-6 col-12">
                            <div class="card earnings-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6">
                                            <h4 class="card-title mb-1">Earnings</h4>
                                            <div class="font-small-2">This Month</div>
                                            <h5 class="mb-1">$4055.56</h5>
                                            <p class="card-text text-muted font-small-2">
                                                <span class="fw-bolder">68.2%</span><span> more earnings than
                                                    last month.</span>
                                            </p>
                                        </div>
                                        <div class="col-6">
                                            <div id="earnings-chart"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <!--/ Earnings Card -->
                    </div>
                </div>

                <!-- Revenue Report Card -->
                {{-- <div class="col-lg-8 col-12">
                    <div class="card card-revenue-budget">
                        <div class="row mx-0">
                            <div class="col-md-8 col-12 revenue-report-wrapper">
                                <div class="d-sm-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title mb-50 mb-sm-0">Revenue Report</h4>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center me-2">
                                            <span class="bullet bullet-primary font-small-3 me-50 cursor-pointer"></span>
                                            <span>Earning</span>
                                        </div>
                                        <div class="d-flex align-items-center ms-75">
                                            <span class="bullet bullet-warning font-small-3 me-50 cursor-pointer"></span>
                                            <span>Expense</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="revenue-report-chart"></div>
                            </div>
                            <div class="col-md-4 col-12 budget-wrapper">
                                <div class="btn-group">
                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm dropdown-toggle budget-dropdown"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        2020
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">2020</a>
                                        <a class="dropdown-item" href="#">2019</a>
                                        <a class="dropdown-item" href="#">2018</a>
                                    </div>
                                </div>
                                <h4 class="mb-25">Total Budget Of The Year</h4>
                                <div class="d-flex justify-content-center">
                                    <span class="fw-bolder me-25"> Budget: </span>
                                    <span>56,800</span>
                                </div>
                                <div id="budget-chart"></div>
                                <button type="button" class="btn btn-primary">Increase Budget</button>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <!--/ Revenue Report Card -->
            </div>

            <div class="row match-height">

                <!-- Browser States Card -->
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="card card-browser-states">
                        <div class="card-header">
                            <div>
                                <h4 class="card-title">Our Customer</h4>
                                <p class="card-text font-small-2">All users from the browser</p>
                            </div>
                            <div class="dropdown chart-dropdown">
                                <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"
                                    data-bs-toggle="dropdown"></i>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Last 28 Days</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">Last Year</a>
                                </div>
                            </div>
                        </div>
                        @php
                            
                            $customer_count = $customer->count();
                            $chrome = [];
                            $firefox = [];
                            $edge = [];
                            $opera = [];
                            $safari = [];
                            
                            $vouchers = collect($customer)->map(function ($row, $index) {
                                return json_decode($row->data)->Browser ?? null;
                            });
                            
                            $new = collect($vouchers)->whereNotNull();
                            foreach ($new as $datas) {
                                if ($datas == 'Chrome' || $datas == 'chrome') {
                                    array_push($chrome, $datas);
                                } elseif ($datas == 'Firefox' || $datas == 'firefox') {
                                    array_push($firefox, $datas);
                                } elseif ($datas == 'Edge' || $datas == 'edge') {
                                    array_push($edge, $datas);
                                } elseif ($datas == 'Opera' || $datas == 'opera') {
                                    array_push($opera, $datas);
                                } elseif ($datas == 'Safari' || $datas == 'safari') {
                                    array_push($safari, $datas);
                                } else {
                                    echo 'Have a nice day!';
                                }
                            }
                            $count_chrome = count($chrome) ?? null;
                            $count_firefox = count($firefox) ?? null;
                            $count_edge = count($edge) ?? null;
                            $count_opera = count($opera) ?? null;
                            $count_safari = count($safari) ?? null;
                            if ($count_chrome != null || $count_firefox != null || $count_edge != null || $count_opera != null || $count_safari != null) {
                                $chrome_percent = number_format(($count_chrome / $customer_count) * 100, 2);
                                $firefox_percent = number_format(($count_firefox / $customer_count) * 100, 2);
                                $opera_percent = number_format(($count_opera / $customer_count) * 100, 2);
                                $edge_percent = number_format(($count_edge / $customer_count) * 100, 2);
                                $safari_percent = number_format(($count_safari / $customer_count) * 100, 2);
                            } else {
                                $chrome_percent = 0;
                                $firefox_percent = 0;
                                $opera_percent = 0;
                                $edge_percent = 0;
                                $safari_percent = 0;
                            }
                            
                            $goal = App\Models\Transaction\Transaction::get();
                        @endphp

                        <div class="card-body">
                            <div class="browser-states">
                                <div class="d-flex">
                                    <img src="/dashboard/images/icons/google-chrome.png" class="rounded me-1" height="30"
                                        alt="Google Chrome" />
                                    <h6 class="align-self-center mb-0">Google Chrome</h6>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{ $chrome_percent }}%</div>
                                    <div id="browser-state-chart-primary"></div>
                                </div>
                            </div>

                            <div class="browser-states">
                                <div class="d-flex">
                                    <img src="/dashboard/images/icons/mozila-firefox.png" class="rounded me-1"
                                        height="30" alt="Mozila Firefox" />
                                    <h6 class="align-self-center mb-0">Mozilla Firefox</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{ $firefox_percent }}% </div>
                                    <div id="browser-state-chart-warning"></div>
                                </div>
                            </div>
                            <div class="browser-states">
                                <div class="d-flex">
                                    <img src="/dashboard/images/icons/apple-safari.png" class="rounded me-1"
                                        height="30" alt="Apple Safari" />
                                    <h6 class="align-self-center mb-0">Apple Safari</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{ $safari_percent }}%</div>
                                    <div id="browser-state-chart-secondary"></div>
                                </div>
                            </div>
                            <div class="browser-states">
                                <div class="d-flex">
                                    <img src="/dashboard/images/icons/internet-explorer.png" class="rounded me-1"
                                        height="30" alt="Internet Explorer" />
                                    <h6 class="align-self-center mb-0">Microsoft Edge</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{ $edge_percent }}%</div>
                                    <div id="browser-state-chart-info"></div>
                                </div>
                            </div>
                            <div class="browser-states">
                                <div class="d-flex">
                                    <img src="/dashboard/images/icons/opera.png" class="rounded me-1" height="30"
                                        alt="Opera Mini" />
                                    <h6 class="align-self-center mb-0">Opera Mini</h6>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold text-body-heading me-1">{{ $opera_percent }}%</div>
                                    <div id="browser-state-chart-danger"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Browser States Card -->

                <!-- Goal Overview Card -->
                <div class="col-lg-4 col-md-6 col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Goal Overview</h4>
                            <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                        </div>
                        <div class="card-body p-0">
                            {{-- <div id="goal-overview-radial-bar-chart" class="my-2"></div> --}}
                            <div class="row border-top text-center mx-0">
                                <div class="col-4 py-1">
                                    <p class="card-text text-muted mb-0">New Order</p>
                                    <h3 class="fw-bolder mb-0">{{ formattedNepaliNumber(count($new_orders))}}</h3>
                                </div>
                                <div class="col-4 py-1">
                                    <p class="card-text text-muted mb-0">In Progress</p>
                                    <h3 class="fw-bolder mb-0">{{ formattedNepaliNumber(count($orders_in_progress)) }}</h3>
                                </div>
                                <div class="col-4 border-end py-1">
                                    <p class="card-text text-muted mb-0">Delivered</p>
                                    <h3 class="fw-bolder mb-0">{{ formattedNepaliNumber(count($total_delivered)) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Goal Overview Card -->

                <!-- Transaction Card -->
                {{-- <div class="col-lg-4 col-md-6 col-12">
                    <div class="card card-transaction">
                        <div class="card-header">
                            <h4 class="card-title">Transactions</h4>
                            <div class="dropdown chart-dropdown">
                                <i data-feather="more-vertical" class="font-medium-3 cursor-pointer"
                                    data-bs-toggle="dropdown"></i>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="#">Last 28 Days</a>
                                    <a class="dropdown-item" href="#">Last Month</a>
                                    <a class="dropdown-item" href="#">Last Year</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
        
                            @foreach ($transactions as $index => $transaction)
                                @php
                                    $total = 0;
                                @endphp
                                <div class="transaction-item">
                                    <div class="d-flex">
                                        <div class="avatar bg-light-primary rounded float-start">
                                            <div class="avatar-content">
                                                <i data-feather="pocket" class="avatar-icon font-medium-3"></i>
                                            </div>
                                        </div>
                                        <div class="transaction-percentage">
                                            <h6 class="transaction-title">{{ $index }}</h6>
                                            {{-- <small>Starbucks</small> --}}
                {{-- </div>
                                    </div>
                                    <div class="fw-bolder text-danger">
                                        @php
                                            foreach ($transaction as $final_element) {
                                                // dd($final_element);
                                                if ($final_element['reason_amount']['type'] == 'minus') {
                                                    $total = $total - $final_element['reason_amount']['total'];
                                                } else {
                                                    $total = $total + $final_element['reason_amount']['total'];
                                                }
                                            }
                                        @endphp
                                        {{ '$. ' . $total }}
                                    </div>
                                </div>
                            @endforeach --}}

                {{-- </div>
                    </div>
                </div>  --}}
                <!--/ Transaction Card -->
            </div>
        </section>
        <!-- /dashboard Ecommerce ends -->
    </div>
    <!-- END: Content-->
@endsection
@push('script')
    <script src="{{ asset('/dashboard/vendors/js/charts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('/dashboard/js/scripts/pages//dashboard-ecommerce.min.js') }}"></script>
@endpush
