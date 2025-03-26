<?php
namespace App\Data\Cart\Item;

use App\Models\CartAssets;
use Illuminate\Http\Request;

class CartItemCheckoutData
{
    protected $request;
    protected $data;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function getData()
    {
        $this->initializeData();
        return $this->data;
    }
    private function initializeData()
    {
        $items = [];
        foreach($this->request->items as $item_id=>$i){
            $items[] = CartAssets::find($item_id);
        }
        $this->data = $items;
    }
}