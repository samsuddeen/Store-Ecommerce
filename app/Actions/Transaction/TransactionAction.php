<?php
namespace App\Actions\Transaction;

use App\Models\Order;
use App\Models\Transaction\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionAction
{
    protected $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function store(Order $order)
    {
        Transaction::create([
            'transaction_no'=>$this->getTransactionNo(),
            'order_id'=>$order->id,
            'transaction_date'=>Carbon::now(),
            'created_by'=>auth()->user()->id,
        ]);
    }
    private function getTransactionNo()
    {
        $transaction = Transaction::latest()->first();
        if($transaction){
            return str_pad((int)$transaction->transaction_no + 1, 8, '0', STR_PAD_LEFT);
        }else{
            return str_pad(1, 8, '0', STR_PAD_LEFT);
        }
    }
}