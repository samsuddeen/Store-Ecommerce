<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DeliveryStaffController extends Controller
{
    public function getDeliveryReport(Request $request)
    {

        if($request->ajax())
        {
            $status = $request->status;
            $date = $request->date;
            if($status)
            {
                $allDeliveries = Order::join('tasks','orders.id','tasks.order_id')
                ->join('task_assigns','tasks.id','task_assigns.task_id')
                ->select('orders.*','tasks.status as t_status', 'tasks.id', 'tasks.updated_at as completed_at','task_assigns.assigned_to')
                ->where('tasks.status',$status)
                ->latest()
                ->paginate(25);
            }
            // elseif($date)
            // {
            //     $allDeliveries = Order::join('tasks','orders.id','tasks.order_id')
            //     ->join('task_assigns','tasks.id','task_assigns.task_id')
            //     ->select('orders.*','tasks.status as t_status', 'tasks.id', 'tasks.updated_at as completed_at','task_assigns.assigned_to')
            //     ->where('tasks.updated_at',Carbon::parse($date)->format('Y-m-d'))
            //     ->latest()
            //     ->paginate(25);
            // }


            $view = view('admin.delivery.partials.table-body',compact('allDeliveries'))->render();
            $pagination = $allDeliveries->links('admin.delivery.partials.pagination')->toHtml();
            return response()->json(['html'=>$view,'pagination'=>$pagination, 'allDeliveries'=>$allDeliveries]);
        }
        
        if(Auth::user()->hasRole('delivery'))
        {
            $allDeliveries = Order::join('tasks','orders.id','tasks.order_id')
            ->join('task_assigns','tasks.id','task_assigns.task_id')
            ->select('orders.*','tasks.status as t_status', 'tasks.id', 'tasks.updated_at as completed_at')
            ->where('task_assigns.assigned_to',Auth::user()->id)
            ->latest()
            ->paginate(25);
        }else{
            $allDeliveries = Order::join('tasks','orders.id','tasks.order_id')
            ->join('task_assigns','tasks.id','task_assigns.task_id')
            ->select('orders.*','tasks.status as t_status', 'tasks.id', 'tasks.updated_at as completed_at','task_assigns.assigned_to')
            ->latest()
            ->paginate(25);
        }
        return view('admin.delivery.report',compact('allDeliveries'));
    }
}
