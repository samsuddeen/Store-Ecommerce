<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GraphController extends Controller
{
    public function getOrderStatusGraphData()
    {   
        $userId = Auth::id();
        $deliveredOrders = Order::join('tasks','orders.id','tasks.order_id')
                            ->join('task_assigns','tasks.id','task_assigns.task_id')
                            ->select('orders.*','tasks.status as t_status', 'tasks.id')
                            ->where('task_assigns.assigned_to',$userId)
                            ->where('orders.status', 5)
                            ->get();
        $pendingOrders = Order::join('tasks','orders.id','tasks.order_id')
                            ->join('task_assigns','tasks.id','task_assigns.task_id')
                            ->select('orders.*','tasks.status as t_status', 'tasks.id')
                            ->where('task_assigns.assigned_to',$userId)
                            ->where('orders.status', 4)
                            ->get();
        $completedOrders = Order::join('tasks','orders.id','tasks.order_id')
                            ->join('task_assigns','tasks.id','task_assigns.task_id')
                            ->select('orders.*','tasks.status as t_status', 'tasks.id')
                            ->where('task_assigns.assigned_to',$userId)
                            ->where('tasks.status', 'Completed')
                            ->get();
        $totalOrders = Order::join('tasks','orders.id','tasks.order_id')
                            ->join('task_assigns','tasks.id','task_assigns.task_id')
                            ->select('orders.*','tasks.status as t_status', 'tasks.id')
                            ->where('task_assigns.assigned_to',$userId)
                            ->get();

        $response = [
            'status' => 200,
            'error' => false,
            'delivered_count' => $deliveredOrders->count(),
            'pending_count' => $pendingOrders->count(),
            'completed_count' => $completedOrders->count(),
            'my_delivery_task_count' => $totalOrders->count(),
            'message' => "Order Graph Data"
        ];

        return response()->json($response);
    }
}
