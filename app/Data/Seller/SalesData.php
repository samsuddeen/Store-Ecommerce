<?php

namespace App\Data\Seller;

use App\Models\Order\Seller\SellerOrder;
use App\Models\Transaction\Transaction;
use Illuminate\Support\Arr;

class SalesData
{
    protected $filters;
    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function SellerSalesData($seller)
    {
        $seller_order = SellerOrder::where('seller_id', $seller->id)->where('status', 5)->get();
        $seller_order = $seller_order->map(function ($item) {
            return $item->order_id;
        });

        $seller_transaction = Transaction::whereIn('order_id', $seller_order)->get();
        $sales = $seller_transaction
            ->when(Arr::get($this->filters, 'month'), function ($q, $value) {
                return $q->filter(function ($item) use ($value) {
                    return date('m', strtotime($item->created_at)) == $value;
                });
            })
            ->when(Arr::get($this->filters, 'year'), function ($q, $value) {
                return $q->filter(function ($item) use ($value) {
                    return date('Y', strtotime($item->created_at)) == $value;
                });
            });


        return $sales;
    }
}
