<?php
namespace App\Data\Seller;

use App\Models\Seller;
use Illuminate\Support\Arr;
use App\Models\Payout\Payout;

class SellerData
{
    protected $filters;
    protected $data;
    function __construct($filters)
    {
        $this->filters = $filters;
    }
    public function getData()
    {
        $this->initializeData();
        return $this->data;
    }
    private function initializeData()
    {
        $sellers = Seller::orderBy('created_at', 'desc')->get();
        $this->data = $sellers;
    }

    public function payoutSeller()
    {
        $sellerPayout = Payout::when(Arr::get($this->filters,'payouttype'),function($q , $value){
            $q->where('is_new',$value);
        })->get();
        return $sellerPayout;

    }
}