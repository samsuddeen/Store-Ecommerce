<?php

namespace App\Data\Dashboard;


use App\Models\Order;
use App\Data\Date\DateData;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Enum\Reason\ReasonEnum;
use App\Enum\Order\OrderStatusEnum;
use App\Enum\Payment\PaymentTypeEnum;
use App\Models\OrderAsset;
use App\Models\Payment\PaymentMethod;
use App\Models\Payment\PaymentHistory;
use App\Models\Seller;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\Task\Task;
use Illuminate\Support\Facades\DB;

class DashboardData
{
    protected $orders;
    function __construct()
    {
    }
    public function getData($type)
    {
        $data['order_rs'] = $this->getOrderData();
        $data['profit_rs'] = $this->getProfit();
        $data['yearly_data'] = $this->getYearlyRevenue();
        $data['orders'] = $this->orders;
        $data['transactions'] = $this->getTransaction();
        $data['tasks'] = $this->getTasks($type);
        $data['top_cities'] = $this->getTopCity();
        $data['top_products'] = $this->getTopProducts();
        $data['productCounts'] = $data['top_products']->pluck('total_products');
        $data['regularCustomers'] = $this->getRegularCustomers();
        $data['orderCounts'] = $data['regularCustomers']->pluck('total_orders');
        $data['customerNames'] = $data['regularCustomers']->pluck('name');
        $data['topCategories'] = $this->getTopCategories();
        $data['categoryOrders'] = $data['topCategories']->pluck('total_orders');
        $data['categoryName'] = $data['topCategories']->pluck('title');
        $data['orderCount'] = $this->getOrderCount();
        if(Auth::user()->hasRole('delivery') || Auth::user()->hasRole('staff'))
        {
            $data['myTasks'] = $this->getMyAssignedTasks();
        }
        return $data;
    }

    public function getOrderCount()
    {
        return Order::count();
    }

    public function getSellerData()
    {


        // $data['order_rs'] = $this->getOrderData();
        // $data['profit_rs'] = $this->getProfit();
        // $data['yearly_data'] = $this->getYearlyRevenue();
        // $data['orders'] = $this->orders;
        $data = $this->getSellerTransaction();

        return $data;
    }


    private function getOrderData()
    {
        // $this->orders = Order::select(
        //     'user_id',
        //     'shipping_charge',
        //     'total_quantity',
        //     'total_price',
        //     'ref_id',
        //     'pending',
        //     'status',
        //     'payment_status',
        // )->where('pending', true)->where('status', '!=', OrderStatusEnum::DELIVERED)->latest()->get();

        $orders = Order::where('pending', true)->where('status', '!=', OrderStatusEnum::DELIVERED)->latest()->get();
        $order_rs = collect($orders)->pluck('total_price')->sum();
        $this->orders = Order::where('pending', true)->where('status', '!=', OrderStatusEnum::DELIVERED)->latest()->take(6)->get();
        return $order_rs;
    }
    private function getProfit()
    {
        $profit_rs = 1500;
        return $profit_rs;
    }
    private function getYearlyRevenue()
    {
        $months =  (new DateData())->getMonths();
        foreach ($months as $month) {
        }
    }

    private function getBrowserData()
    {
        return [];
    }



    private function getTransaction()
    {
        $payment_methods = PaymentMethod::orderBy('title', 'asc')->get();
        // dd($payment_methods);
        $transactions = [];
        foreach ($payment_methods as $payment_method) {
            $payment_histories = PaymentHistory::select(
                'method_model',
                'method_id',
                'title',
                'method',
                'reason_model',
                'reason_id',
            )->where('method_id', $payment_method->id)->where('method_model', get_class($payment_method->getModel()))->get();

            foreach ($payment_histories as $history) {
                $transactions[] = [
                    'method_enum' => $this->getMethodEnum($history),
                    'method' => $this->getMethod($history),
                    'reason_amount' => $this->getReasonModel($history->reason_model, $history->reason_id),
                ];
            }
        }
        $transaction = collect($transactions)->groupBy('method');
        // dd($transactions);
        return $transaction;
    }

    private function getSellerTransaction()
    {

        $payment_methods = PaymentMethod::orderBy('title', 'asc')->get();
        $transactions = [];
        foreach ($payment_methods as $payment_method) {
            $payment_histories = PaymentHistory::select(
                'method_model',
                'method_id',
                'title',
                'method',
                'reason_model',
                'reason_id',
            )->where('from_model', get_class(Seller::getModel()))
            ->where('from_id', auth()->guard('seller')->user()->id)
            ->where('to_model', get_class(Seller::getModel()))
            ->where('to_id', auth()->guard('seller')->user()->id)
            ->where('method_id', $payment_method->id)->where('method_model', get_class($payment_method->getModel()))->get();
            
            foreach ($payment_histories as $history) {
                $transactions[] = [
                    'method_enum' => $this->getMethodEnum($history),
                    'method' => $this->getMethod($history),
                    'reason_amount' => $this->getSellerReasonModel($history->reason_model, $history->reason_id),
                ];
            }
        }
        $transaction = collect($transactions)->groupBy('method');
        return $transaction;






        // $transaction = [];
        // $order = [];
        // $payment = Transaction::get();
        // foreach ($payment as $payments) {
        //     array_push($transaction, $payments->sellerOrder);
        // }

        // $new = collect($transaction);

        // foreach ($new as $datas) {
        //     array_push($order, $datas->order);
        // }

        // $transaction = $order;
        // $payment_method['cod'] = collect($transaction)->where('payment_with', 'Cash On Delivery');
        // $payment_method['esewa'] = collect($transaction)->where('payment_with', 'Esewa');
        // $payment_method['khalti'] = collect($transaction)->where('payment_with', 'khalti');
        // return $payment_method;
    }

    private function getMethodEnum($history)
    {
        $enum = "";
        switch ($history->method) {
            case PaymentTypeEnum::WALET:
                $enum =  "Walet";
                break;
            case PaymentTypeEnum::BANK:
                $enum =  "Bank/Financial";
                break;
            default:
                # code...
                break;
        }
        return $enum;
    }
    private function getMethod($history)
    {
        $method = $history->method_model::find($history->method_id);
        if ($method) {
            return $method->title;
        } else {
            return null;
        }
    }
    private function getReasonModel($reason_name, $reason_id)
    {
        $reasons = [];
        $reason = $reason_name::find($reason_id);
        switch ($reason_name) {
            case ReasonEnum::ORDER:
                $reasons = [
                    'total' => $reason->total_price,
                    'type' => 'plus',
                ];
                break;
            case ReasonEnum::PAYOUT:
                $reasons = [
                    'total' => $reason->transaction->total ?? 0,
                    'type' => 'minus',
                ];
                break;
            case ReasonEnum::REFUND:
                $reasons = [
                    'total' => (@$reason->returnOrder->orderAsset->price ?? 0 * @$reason->returnOrder->qty ?? 0) ?? 0,
                    'type' => 'minus',
                ];
                break;
            default:
                # code...
                break;
        }
        return $reasons;
    }
    private function getSellerReasonModel($reason_name, $reason_id)
    {
        $reasons = [];
        $reason = $reason_name::find($reason_id);
        switch ($reason_name) {
            case ReasonEnum::PAYOUT:
                $reasons = [
                    'total' => $reason->transaction->total ?? 0,
                    'type' => 'plus',
                ];
                break;
            
            default:
                # code...
                break;
        }
        return $reasons;
    }

    private function getTasks($type)
    {   
        $tasksQuery = Task::query();

        if ($type) {
            switch ($type) {
                case 'All':
                    // No additional filtering needed for 'All' type
                    break;
    
                case 'Assigned':
                    $tasksQuery->where('status', 'Assigned');
                    break;
    
                case 'Pending':
                    $tasksQuery->where('status', 'Pending');
                    break;
    
                case 'In-Progress':
                    $tasksQuery->where('status', 'In-Progress');
                    break;
    
                case 'Completed':
                    $tasksQuery->where('status', 'Completed');
                    break;
    
                case 'Overdue':
                    $tasksQuery->where('status', 'Overdue');
                    break;
    
                case 'On-Hold':
                    $tasksQuery->where('status', 'On Hold');
                    break;
    
                case 'Cancelled':
                    $tasksQuery->where('status', 'Cancelled');
                    break;
    
                default:
                    // Handle unrecognized type
                    break;
            }
        }

        if(Auth::user()->hasRole('super admin'))
        {
            $tasks = $tasksQuery->latest()->paginate(10);
        }else{
            $tasks = $tasksQuery->whereIn('id', function ($query) {
                $query->select('task_id')
                    ->from('task_assigns')
                    ->where('assigned_to', Auth::user()->id);
            })
            ->orWhere('created_by', Auth::user()->id)
            ->orWhere('assigned_by', Auth::user()->id)
            ->latest()
            ->paginate(10);
        }

        return $tasks;
    }

    private function getTopCity()
    {
        $topCities = Order::select('area', DB::raw('COUNT(*) as total_orders'))
                            ->whereNotNull('area')
                            ->groupBy('area')
                            ->orderByDesc('total_orders')
                            ->limit(4)
                            ->get();

        return $topCities;
    }

    private function getTopProducts()
    {
        $topProducts = OrderAsset::select('product_name', DB::raw('COUNT(*) as total_products'))
                                ->groupBy('product_name')
                                ->orderByDesc('total_products')
                                ->limit(5)
                                ->get();
        return $topProducts;
    }

    private function getRegularCustomers()
    {
        $top_customers = Order::select('name' ,DB::raw('COUNT(*) as total_orders'))
                            ->groupBy('name')
                            ->orderByDesc('total_orders')
                            ->limit(5)
                            ->get();
        return $top_customers;
    }

    private function getTopCategories()
    {
        $top_categories = OrderAsset::join('products','order_assets.product_id','products.id')
                                    ->join('categories','products.category_id','categories.id')
                                    ->select('categories.title AS title',DB::raw('COUNT(*) as total_orders'))
                                    ->groupBy('title')
                                    ->orderByDesc('total_orders')
                                    ->limit(4)
                                    ->get();
        return $top_categories;
    }


    private function getMyAssignedTasks()
    {
        $tasks = Task::whereHas('assigns',function($q){
            $q->where('assigned_to',Auth::user()->id);
        })->latest()->paginate(10);

        return $tasks;
    }
}
