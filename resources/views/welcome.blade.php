    @extends('layouts.app')
    @section('title', env('DEFAULT_TITLE') . ' | ' . 'Dashboard')
    <style>
        .dashboard-statics ul {
            display: flex;
            padding: 0;
            margin: 0;
            margin-bottom: 30px;
        }

        .dashboard-statics ul li {
            list-style: none;
            flex: 1;
        }

        .visit_graph {
            background-color: var(--white-color);
            padding: 15px;
            border-radius: var(--border-radius);
            box-shadow: 0 3px 30px 0 rgb(0 0 0 / 15%);
            display: flex;
            align-items: center;
        }

        .dashboard-statics ul li+li {
            margin-left: 15px;
        }

        .graph_art {
            margin-right: 12px;
        }

        .graph_art {
            height: 45px;
            width: 45px;
            line-height: 45px;
            text-align: center;
            border-radius: var(--border-radius);
            color: var(--white-color);
            position: relative;
        }

        .graph_art svg {
            height: 25px;
            width: 25px;
            color: var(--white-color);
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0;
            margin: auto;
        }

        .visit_graph h5 {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 0px;
        }

        .visit_graph h5 a {
            display: block;
            color: var(--dark-color);
        }

        .in_dp_flex span {
            display: block;
            font-size: 24px;
            font-weight: bold;
            line-height: normal;
            margin-top: 5px;
        }

        .first .graph_art {
            background: linear-gradient(45deg, #f53c5b, #fb768c);
        }

        .second .graph_art {
            background: linear-gradient(45deg, #3858f9, #8e79fd);
        }

        .third .graph_art {
            background: linear-gradient(to top, #0ba360 0%, #3cba92 100%);
        }

        .fourth .graph_art {
            background-image: linear-gradient(45deg, #ff5858 0%, #f09819 100%);
        }

        .fifth .graph_art {
            background-image: linear-gradient(45deg, #00cccc 0%, #6aefef 100%);
        }

        .first .in_dp_flex span {
            color: #f53c5b;
        }

        .second .in_dp_flex span {
            color: #3858f9;
        }

        .third .in_dp_flex span {
            color: #0ba360;
        }

        .fourth .in_dp_flex span {
            color: #f09819;
        }

        .fifth .in_dp_flex span {
            color: #00cccc;
        }

        .card-company-table th,
        .card-company-table td {
            font-size: 14px;
        }
        @media only screen and (max-width: 991px){
            .dashboard-box-all{
            display: block ! important;
        }
        .dashboard-box-all li{
            display: inline-flex ;
            padding: 10px 10px 10px 3px ! important;
        }
        .dashboard-statics ul li+li {
    margin-left: 0px;
}
        }
        @media only screen and (max-width: 650px){
            .dashboard-box-all li{
            display: block;
           
        }

        }
        
    </style>

    @section('content')
        <!-- BEGIN: Content-->
        <div class="content-header row">
        </div>
        <div class="content-body">
            <!-- /dashboard Ecommerce Starts -->
            <section id="dashboard-ecommerce" class="dashboard-statics">
                @if(!Auth::user()->hasRole('delivery'))
                    @if(Auth::user()->can('category-read') || Auth::user()->can('customer-read') || Auth::user()->can('sellers-read') || Auth::user()->can('product-read'))
                    <ul class="dashboard-box-all" >
                        @if(Auth::user()->can('category-read'))
                        <li>
                            <div class="visit_graph first">
                                <div class="graph_art">
                                    <i data-feather="grid" class="avatar-icon"></i>
                                </div>
                                <?php $category = App\Models\Category::all()->count(); ?>
                                <div class="in_dp_flex">
                                    <h5><a href="{{ route('category.index') }}">Category</a></h5>
                                    <span>{{ $category }}</span>
                                </div>
                            </div>
                        </li>
                        @endif
                        @if(Auth::user()->can('customer-read'))
                        <li>
                            <div class="visit_graph second">
                                <div class="graph_art">
                                    <i data-feather="users" class="avatar-icon"></i>
                                </div>
                                @php
                                    $customer = App\Models\New_Customer::all()->count();
                                @endphp
                                <div class="in_dp_flex">
                                    <h5><a href="{{ route('customer.index') }}">Customers</a></h5>
                                    <span>{{ $customer }}</span>
                                </div>
                            </div>
                        </li>
                        @endif
                        @if(Auth::user()->can('sellers-read'))
                        <li>
                            <div class="visit_graph third">
                                <div class="graph_art">
                                    <i data-feather="truck" class="avatar-icon"></i>
                                </div>
                                @php
                                    $seller = App\Models\User::with('roles')
                                        ->whereHas('roles', function ($q) {
                                            $q->whereIn('id', [2]);
                                        })
                                        ->get()
                                        ->count();
                                @endphp
                                <div class="in_dp_flex">
                                    <h5><a href="{{ route('user.index') }}">Orders</a></h5>
                                    <span>{{ $orderCount }}</span>
                                </div>
                            </div>
                        </li>
                        @endif
                        @if(Auth::user()->can('product-read'))
                        <li>
                            <div class="visit_graph fourth">
                                <div class="graph_art">
                                    <i data-feather="box" class="avatar-icon"></i>
                                </div>
                                <?php $products = App\Models\product::all()->count(); ?>
                                <div class="in_dp_flex">
                                    <h5><a href="{{ route('product.index') }}">Products</a></h5>
                                    <span>{{ $products }}</span>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="visit_graph fifth">
                                <div class="graph_art">
                                    <i data-feather="layers" class="avatar-icon"></i>
                                </div>
                                <?php $productstock = App\Models\ProductStock::all()->count(); ?>
                                <div class="in_dp_flex">
                                    <h5><a href="">Product Stock</a></h5>
                                    <span>{{ $productstock }}</span>
                                </div>
                            </div>
                        </li>
                        @endif
                    </ul>
                    @endif
                @endif

                @if(Auth::user()->hasRole('staff'))
                @include('admin.includes.staff_tasks')
                @endif
                
                @if(!Auth::user()->hasRole('delivery'))
                @include('admin.includes.charts')
                @endif

                @if(!Auth::user()->hasRole('delivery') && Auth::user()->can('order-read'))
                <div class="card card-company-table">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>SN.</th>
                                        <th>REF ID</th>
                                        <th>Order By</th>
                                        <th>City</th>
                                        <th>Qty</th>
                                        <th>Type</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th>Payment Status</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $index => $order)
                                        <tr>
                                            <td>
                                                {{ $index + 1 }}
                                            </td>
                                            <td>
                                                <a
                                                    href="{{ route('admin.viewOrder', $order->ref_id ?? '') }}">{{ $order->ref_id }}</a>
                                            </td>
                                            <td>
                                                {!! "<a href='#'>" . $order->user->name . '[' . $order->user->phone . ']</a>' !!}
                                            </td>
                                            <td>
                                                {{ $order->area }}
                                            </td>
                                            <td>
                                                {{ $order->total_quantity }}
                                            </td>
                                            <td>
                                                {{ $order->user->wholeseller ? 'Whole Seller':'Customer' }}
                                            </td>
                                            <td>
                                                {{ 'Rs. ' . formattedNepaliNumber($order->total_discount) }}
                                            </td>
                                            <td>
                                                {{ 'Rs. ' . formattedNepaliNumber($order->total_price) }}
                                            </td>
                                            <td>
                                                {{ (int) $order->payment_status == 1 ? 'Paid' : 'Not Paid' }}
                                            </td>
                                            <td class="text-nowrap">
                                                {!! (new \App\Datatables\OrderDatatables())->getStatus($order->status) !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if(!Auth::user()->hasRole('delivery'))
                <div class="row match-height">
                    @if(Auth::user()->can('order-read'))
                    <!-- Bar Chart - Orders -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card">
                            <div class="card-body pb-50">
                                <h6>Orders</h6>
                                <h2 class="fw-bolder mb-1">{{ '$. ' . formattedNepaliNumber($order_rs) }}</h2>
                                <div id="statistics-order-chart"></div>
                            </div>
                        </div>
                    </div>
                    <!--/ Bar Chart - Orders -->
                    @endif

                    @if(Auth::user()->can('transaction-read'))
                    <!-- Transaction Card -->
                    <div class="col-lg-4 col-md-6 col-12">
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
                                            </div>
                                        </div>
                                        <div class="fw-bolder text-danger">
                                            @php
                                                foreach ($transaction as $final_element) {
                                                    if ($final_element['reason_amount']['type'] == 'minus') {
                                                        $total = $total + $final_element['reason_amount']['total'];
                                                    } else {
                                                        $total = $total + $final_element['reason_amount']['total'];
                                                    }
                                                    
                                                }
                                            @endphp
                                            {{ 'Rs. ' . formattedNepaliNumber($total) }}
                                        </div>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                    <!--/ Transaction Card -->
                    @endif

                    @if(Auth::user()->can('customer-read') || Auth::user()->can('sellers-read'))
                    <!-- Browser States Card -->
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="card card-browser-states">
                            <div class="card-header">
                                <div>
                                    <h4 class="card-title">Browser States</h4>
                                    <p class="card-text font-small-2">Counter August 2020</p>
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
                                $customer = App\Models\New_Customer::get();
                                
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
                                    // $goal = App\Models\Transaction\Transaction::get();
                                } else {
                                    $chrome_percent = 0;
                                    $firefox_percent = 0;
                                    $opera_percent = 0;
                                    $edge_percent = 0;
                                    $safari_percent = 0;
                                }
                            @endphp

                            <div class="card-body">
                                <div class="browser-states">
                                    <div class="d-flex">
                                        <img src="/dashboard/images/icons/google-chrome.png" class="rounded me-1"
                                            height="30" alt="Google Chrome" />
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
                    @endif

                    {{-- @if(Auth::user()->can('task-read'))
                    <div class="group-btns mb-2">
                        <ul>

                            <li>
                                <a href="{{ route('dashboard', ['type' => 'All']) }}" class="btns  btn-secondary" data-type="All" role="button" style="padding: 8px;">
                                    All <span class="badge">{{ $tasks->count() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['type' => 'Assigned']) }}" class="btns btn-secondary " data-type="Assigned" role="button" style="padding: 8px;">
                                    Assigned <span class="badge">{{ $tasks->where('status','Assigned')->count() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['type' => 'Pending']) }}" class="btns btn-secondary" data-type="Pending" role="button" style="padding: 8px;">
                                    Pending <span class="badge">{{ $tasks->where('status','Pending')->count() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['type' => 'In-Progress']) }}" class="btns btn-secondary" data-type="In-Progress" role="button" style="padding: 8px;"> 
                                   In-Progress <span class="badge">{{ $tasks->where('status','In-Progress')->count() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['type' => 'Completed']) }}" class="btns btn-secondary" data-type="Completed" role="button" style="padding: 8px;">
                                    Completed <span class="badge">{{ $tasks->where('status','Completed')->count() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['type' => 'Overdue']) }}" class="btns btn-secondary" data-type="Overdue" role="button" style="padding: 8px;">
                                    Overdue <span class="badge">{{ $tasks->where('status','Overdue')->count() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['type' => 'On-Hold']) }}" class="btns btn-secondary" data-type="On Hold" role="button" style="padding: 8px;">
                                    On-Hold <span class="badge">{{ $tasks->where('status','On Hold')->count() }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('dashboard', ['type' => 'Cancelled']) }}" class="btns btn-secondary" data-type="Cancelled" role="button" style="padding: 8px;">
                                    Cancelled <span class="badge">{{ $tasks->where('status','Cancelled')->count() }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card card-company-table">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SN.</th>
                                            <th>Title</th>
                                            <th>Action</th>
                                            <th>Priority</th>
                                            <th>Status</th>
                                            <th>Assigned By</th>
                                            <th>Assigned To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tasks as $task)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $task->title }}</td>
                                                <td>{{ $task->action->title ?? '-' }}</td>
                                                <td>{{ $task->priority }}</td>
                                                <td>{{ $task->status }}</td>
                                                <td>{{ $task->createdBy->name }}</td>
                                                <td>
                                                    @foreach ($task->assigns as $member)
                                                    <p>{{ $member->name }}</p>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">No task(s) has been assigned till now</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $tasks->links() }}
                            </div>
                        </div>
                    </div>
                    @endif --}}
                    <!-- Line Chart - Profit -->
                    {{-- <div class="col-lg-6 col-md-3 col-6">
                                <div class="card card-tiny-line-stats">
                                    <div class="card-body pb-50">
                                        <h6>Profit</h6>
                                        <h2 class="fw-bolder mb-1">6,24k</h2>
                                        <div id="statistics-profit-chart"></div>
                                    </div>
                                </div>
                            </div> --}}
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
                @endif
                
                @if(Auth::user()->hasRole('delivery'))
                    @include('delivery-dashbard')
                @endif
                <!-- Revenue Report Card -->
                {{-- <div class="col-lg-8 col-12">
                        <div class="card card-revenue-budget">
                            <div class="row mx-0">
                                <div class="col-md-12 col-12 revenue-report-wrapper">
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
                            </div>
                        </div>
                    </div> --}}
        </div>

        <div class="row match-height">



            <!-- Goal Overview Card -->

            {{-- <div class="col-lg-6 col-md-6 col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">Goal Overview</h4>
                                <i data-feather="help-circle" class="font-medium-3 text-muted cursor-pointer"></i>
                            </div>
                            <div class="card-body p-0">
                                <div id="goal-overview-radial-bar-chart" class="my-2"></div>
                                <div class="row border-top text-center mx-0">
                                    <div class="col-6 border-end py-1">
                                        <p class="card-text text-muted mb-0">Completed</p>
                                        <h3 class="fw-bolder mb-0">{{count($goal)}}</h3>
                                    </div>
                                    <div class="col-6 py-1">
                                        <p class="card-text text-muted mb-0">In Progress</p>
                                        <h3 class="fw-bolder mb-0">13,561</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

            <!--/ Goal Overview Card -->
        </div>
        </section>
        <!-- /dashboard Ecommerce ends -->

        </div>
        <!-- END: Content-->
    @endsection

    @push('script')
        <script src="{{ asset('/dashboard/vendors/js/charts/apexcharts.min.js') }}"></script>
        <script src="{{ asset('/dashboard/js/scripts/pages//dashboard-ecommerce.min.js') }}"></script>
        <script>
            $(window).on("load", function() {
                "use strict";
                var o,
                    e,
                    r,
                    t,
                    a,
                    s,
                    i,
                    l,
                    n,
                    d,
                    h,
                    c = "#f3f3f3",
                    w = "#EBEBEB",
                    p = "#b9b9c3",
                    u = document.querySelector("#statistics-order-chart"),
                    g = document.querySelector("#statistics-profit-chart"),
                    b = document.querySelector("#earnings-chart"),
                    y = document.querySelector("#revenue-report-chart"),
                    m = document.querySelector("#budget-chart"),
                    f = document.querySelector("#browser-state-chart-primary"),
                    k = document.querySelector("#browser-state-chart-warning"),
                    x = document.querySelector("#browser-state-chart-secondary"),
                    C = document.querySelector("#browser-state-chart-info"),
                    A = document.querySelector("#browser-state-chart-danger"),
                    B = document.querySelector("#goal-overview-radial-bar-chart"),
                    S = "rtl" === $("html").attr("data-textdirection");
                setTimeout(function() {
                        toastr.success(
                            "You have successfully logged in to {{ env('APP_NAME') }}. Now you can start to explore!",
                            "👋 Welcome {{ auth()->user()->name }}!", {
                                closeButton: !0,
                                tapToDismiss: !1,
                                rtl: S
                            }
                        );
                    }, 2e3),
                    (o = {
                        chart: {
                            height: 70,
                            type: "bar",
                            stacked: !0,
                            toolbar: {
                                show: !1
                            },
                        },
                        grid: {
                            show: !1,
                            padding: {
                                left: 0,
                                right: 0,
                                top: -15,
                                bottom: -15
                            },
                        },
                        plotOptions: {
                            bar: {
                                horizontal: !1,
                                columnWidth: "20%",
                                startingShape: "rounded",
                                colors: {
                                    backgroundBarColors: [c, c, c, c, c],
                                    backgroundBarRadius: 5,
                                },
                            },
                        },
                        legend: {
                            show: !1
                        },
                        dataLabels: {
                            enabled: !1
                        },
                        colors: [window.colors.solid.warning],
                        series: [{
                            name: "2020",
                            data: [45, 85, 65, 45, 65]
                        }],
                        xaxis: {
                            labels: {
                                show: !1
                            },
                            axisBorder: {
                                show: !1
                            },
                            axisTicks: {
                                show: !1
                            },
                        },
                        yaxis: {
                            show: !1
                        },
                        tooltip: {
                            x: {
                                show: !1
                            }
                        },
                    }),
                    new ApexCharts(u, o).render(),
                    (e = {
                        chart: {
                            height: 70,
                            type: "line",
                            toolbar: {
                                show: !1
                            },
                            zoom: {
                                enabled: !1
                            },
                        },
                        grid: {
                            borderColor: w,
                            strokeDashArray: 5,
                            xaxis: {
                                lines: {
                                    show: !0
                                }
                            },
                            yaxis: {
                                lines: {
                                    show: !1
                                }
                            },
                            padding: {
                                top: -30,
                                bottom: -10
                            },
                        },
                        stroke: {
                            width: 3
                        },
                        colors: [window.colors.solid.info],
                        series: [{
                            data: [0, 20, 5, 30, 15, 45]
                        }],
                        markers: {
                            size: 2,
                            colors: window.colors.solid.info,
                            strokeColors: window.colors.solid.info,
                            strokeWidth: 2,
                            strokeOpacity: 1,
                            strokeDashArray: 0,
                            fillOpacity: 1,
                            discrete: [{
                                seriesIndex: 0,
                                dataPointIndex: 5,
                                fillColor: "#ffffff",
                                strokeColor: window.colors.solid.info,
                                size: 5,
                            }, ],
                            shape: "circle",
                            radius: 2,
                            hover: {
                                size: 3
                            },
                        },
                        xaxis: {
                            labels: {
                                show: !0,
                                style: {
                                    fontSize: "0px"
                                }
                            },
                            axisBorder: {
                                show: !1
                            },
                            axisTicks: {
                                show: !1
                            },
                        },
                        yaxis: {
                            show: !1
                        },
                        tooltip: {
                            x: {
                                show: !1
                            }
                        },
                    }),
                    new ApexCharts(g, e).render(),
                    (r = {
                        chart: {
                            type: "donut",
                            height: 120,
                            toolbar: {
                                show: !1
                            }
                        },
                        dataLabels: {
                            enabled: !1
                        },
                        series: [53, 16, 31],
                        legend: {
                            show: !1
                        },
                        comparedResult: [2, -3, 8],
                        labels: ["App", "Service", "Product"],
                        stroke: {
                            width: 0
                        },
                        colors: ["#28c76f66", "#28c76f33", window.colors.solid.success],
                        grid: {
                            padding: {
                                right: -20,
                                bottom: -8,
                                left: -20
                            }
                        },
                        plotOptions: {
                            pie: {
                                startAngle: -10,
                                donut: {
                                    labels: {
                                        show: !0,
                                        name: {
                                            offsetY: 15
                                        },
                                        value: {
                                            offsetY: -15,
                                            formatter: function(o) {
                                                return parseInt(o) + "%";
                                            },
                                        },
                                        total: {
                                            show: !0,
                                            offsetY: 15,
                                            label: "App",
                                            formatter: function(o) {
                                                return "53%";
                                            },
                                        },
                                    },
                                },
                            },
                        },
                        responsive: [{
                                breakpoint: 1325,
                                options: {
                                    chart: {
                                        height: 100
                                    }
                                }
                            },
                            {
                                breakpoint: 1200,
                                options: {
                                    chart: {
                                        height: 120
                                    }
                                }
                            },
                            {
                                breakpoint: 1045,
                                options: {
                                    chart: {
                                        height: 100
                                    }
                                }
                            },
                            {
                                breakpoint: 992,
                                options: {
                                    chart: {
                                        height: 120
                                    }
                                }
                            },
                        ],
                    }),
                    new ApexCharts(b, r).render(),
                    (t = {
                        chart: {
                            height: 230,
                            stacked: !0,
                            type: "bar",
                            toolbar: {
                                show: !1
                            },
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: "17%",
                                endingShape: "rounded"
                            },
                            distributed: !0,
                        },
                        colors: [window.colors.solid.primary, window.colors.solid.warning],
                        series: [{
                                name: "Earning",
                                data: [95, 177, 284, 256, 105, 63, 168, 218, 72],
                            },
                            {
                                name: "Expense",
                                data: [-145, -80, -60, -180, -100, -60, -85, -75, -100],
                            },
                        ],
                        dataLabels: {
                            enabled: !1
                        },
                        legend: {
                            show: !1
                        },
                        grid: {
                            padding: {
                                top: -20,
                                bottom: -10
                            },
                            yaxis: {
                                lines: {
                                    show: !1
                                }
                            },
                        },
                        xaxis: {
                            categories: [
                                "Jan",
                                "Feb",
                                "Mar",
                                "Apr",
                                "May",
                                "Jun",
                                "Jul",
                                "Aug",
                                "Sep",
                            ],
                            labels: {
                                style: {
                                    colors: p,
                                    fontSize: "0.86rem"
                                }
                            },
                            axisTicks: {
                                show: !1
                            },
                            axisBorder: {
                                show: !1
                            },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: p,
                                    fontSize: "0.86rem"
                                }
                            }
                        },
                    }),
                    new ApexCharts(y, t).render(),
                    (a = {

                        chart: {
                            height: 80,
                            toolbar: {
                                show: !1
                            },
                            zoom: {
                                enabled: !1
                            },
                            type: "line",
                            sparkline: {
                                enabled: !0
                            },
                        },
                        stroke: {
                            curve: "smooth",
                            dashArray: [0, 5],
                            width: [2]
                        },
                        colors: [window.colors.solid.primary, "#dcdae3"],
                        series: [{
                                data: [61, 48, 69, 52, 60, 40, 79, 60, 59, 43, 62]
                            },
                            {
                                data: [20, 10, 30, 15, 23, 0, 25, 15, 20, 5, 27]
                            },
                        ],
                        tooltip: {
                            enabled: !1
                        },
                    }),

                    // BIBEK
                    new ApexCharts(m, a).render(),
                    (s = {
                        chart: {
                            height: 30,
                            width: 30,
                            type: "radialBar"
                        },
                        grid: {
                            show: !1,
                            padding: {
                                left: -15,
                                right: -15,
                                top: -12,
                                bottom: -15
                            },
                        },
                        colors: [window.colors.solid.primary],
                        series: [{{ @$chrome_percent }}],
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: "22%"
                                },
                                track: {
                                    background: w
                                },
                                dataLabels: {
                                    showOn: "always",
                                    name: {
                                        show: !1
                                    },
                                    value: {
                                        show: !1
                                    },
                                },
                            },
                        },
                        stroke: {
                            lineCap: "round"
                        },
                    }),
                    new ApexCharts(f, s).render(),
                    (i = {
                        chart: {
                            height: 30,
                            width: 30,
                            type: "radialBar"
                        },
                        grid: {
                            show: !1,
                            padding: {
                                left: -15,
                                right: -15,
                                top: -12,
                                bottom: -15
                            },
                        },
                        colors: [window.colors.solid.warning],
                        series: [{{ @$firefox_percent }}],
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: "22%"
                                },
                                track: {
                                    background: w
                                },
                                dataLabels: {
                                    showOn: "always",
                                    name: {
                                        show: !1
                                    },
                                    value: {
                                        show: !1
                                    },
                                },
                            },
                        },
                        stroke: {
                            lineCap: "round"
                        },
                    }),
                    new ApexCharts(k, i).render(),
                    (l = {
                        chart: {
                            height: 30,
                            width: 30,
                            type: "radialBar"
                        },
                        grid: {
                            show: !1,
                            padding: {
                                left: -15,
                                right: -15,
                                top: -12,
                                bottom: -15
                            },
                        },
                        colors: [window.colors.solid.secondary],
                        series: [{{ @$safari_percent }}],
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: "22%"
                                },
                                track: {
                                    background: w
                                },
                                dataLabels: {
                                    showOn: "always",
                                    name: {
                                        show: !1
                                    },
                                    value: {
                                        show: !1
                                    },
                                },
                            },
                        },
                        stroke: {
                            lineCap: "round"
                        },
                    }),
                    new ApexCharts(x, l).render(),
                    (n = {
                        chart: {
                            height: 30,
                            width: 30,
                            type: "radialBar"
                        },
                        grid: {
                            show: !1,
                            padding: {
                                left: -15,
                                right: -15,
                                top: -12,
                                bottom: -15
                            },
                        },
                        colors: [window.colors.solid.info],
                        series: [{{ @$edge_percent }}],
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: "22%"
                                },
                                track: {
                                    background: w
                                },
                                dataLabels: {
                                    showOn: "always",
                                    name: {
                                        show: !1
                                    },
                                    value: {
                                        show: !1
                                    },
                                },
                            },
                        },
                        stroke: {
                            lineCap: "round"
                        },
                    }),
                    new ApexCharts(C, n).render(),
                    (d = {
                        chart: {
                            height: 30,
                            width: 30,
                            type: "radialBar"
                        },
                        grid: {
                            show: !1,
                            padding: {
                                left: -15,
                                right: -15,
                                top: -12,
                                bottom: -15
                            },
                        },
                        colors: [window.colors.solid.danger],
                        series: [{{ @$opera_percent }}],
                        plotOptions: {
                            radialBar: {
                                hollow: {
                                    size: "22%"
                                },
                                track: {
                                    background: w
                                },
                                dataLabels: {
                                    showOn: "always",
                                    name: {
                                        show: !1
                                    },
                                    value: {
                                        show: !1
                                    },
                                },
                            },
                        },
                        stroke: {
                            lineCap: "round"
                        },
                    }),
                    new ApexCharts(A, d).render(),
                    (h = {
                        chart: {
                            height: 245,
                            type: "radialBar",
                            sparkline: {
                                enabled: !0
                            },
                            dropShadow: {
                                enabled: !0,
                                blur: 3,
                                left: 1,
                                top: 1,
                                opacity: 0.1,
                            },
                        },
                        colors: ["#51e5a8"],
                        plotOptions: {
                            radialBar: {
                                offsetY: -10,
                                startAngle: -150,
                                endAngle: 150,
                                hollow: {
                                    size: "77%"
                                },
                                track: {
                                    background: "#ebe9f1",
                                    strokeWidth: "50%"
                                },
                                dataLabels: {
                                    name: {
                                        show: !1
                                    },
                                    value: {
                                        color: "#5e5873",
                                        fontSize: "2.86rem",
                                        fontWeight: "600",
                                    },
                                },
                            },
                        },
                        fill: {
                            type: "gradient",
                            gradient: {
                                shade: "dark",
                                type: "horizontal",
                                shadeIntensity: 0.5,
                                gradientToColors: [window.colors.solid.success],
                                inverseColors: !0,
                                opacityFrom: 1,
                                opacityTo: 1,
                                stops: [0, 100],
                            },
                        },
                        series: [83],
                        stroke: {
                            lineCap: "round"
                        },
                        grid: {
                            padding: {
                                bottom: 30
                            }
                        },
                    }),
                    new ApexCharts(B, h).render();
            });
        </script>

        <script>
            $(document).ready(function(){
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
            });
        </script>
    @endpush
