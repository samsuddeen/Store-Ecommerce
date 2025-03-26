<?php
namespace App\Actions\Admin\Order;


use App\Models\Order;
use Illuminate\Support\Arr;

 class AdminOrder{
    protected $filters;
    public function __construct($filters)
    {
        $this->filters=$filters;
    }

    public function getOrder()
    {
        $orders = Order::query()
            ->when(Arr::get($this->filters, 'refId'), function ($q, $value) {
                $q->where('ref_id', $value);
            })
            ->when(Arr::get($this->filters, 'customername'), function ($q, $value) {
                $q->whereHas('user', function ($item) use ($value) {
                    $item->where('name', 'like', '%' . $value . '%');
                });
            })
            ->when(Arr::get($this->filters, 'startDate'), function ($q, $value) {
                $q->whereDate('created_at', '>=', $value);
            })
            ->when(Arr::get($this->filters, 'endDate'), function ($q, $value) {
                $q->whereDate('created_at', '<=', $value);
            })
            ->when(Arr::get($this->filters,'filterCustomerId'),function($q,$value){
                $q->where('user_id',$value);
            });
    
        if (Arr::get($this->filters, 'type') != '9' && Arr::get($this->filters, 'type') != '10') {
            $orders->when(Arr::get($this->filters, 'type'), function ($q, $value) {
                $q->where('status', $value);
            });
        } else {
            $filterValue = Arr::get($this->filters, 'type') == '9' ? '10' : '1';
            $finalValue = $filterValue == '1' ? '1' : '0';
            $orders->when($filterValue, function ($q, $filterValue) use ($finalValue) {
                $q->whereHas('user', function ($userData) use ($finalValue) {
                    $userData->where('wholeseller', $finalValue);
                });
            });
        }
    
        return $orders->orderBy('id', 'DESC')->paginate(10);
    }
    
 }