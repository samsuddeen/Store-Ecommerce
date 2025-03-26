<?php
namespace App\Actions\Payment;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Payment\PaymentMethod;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PaymentMethodAction
{
    protected $request;
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function store()
    {   
        if($this->request->has('is_default')){
        DB::table('payment_methods')->update([
            'is_default'=>'0',
        ]);
    }
        $data = [
            'user_id'=>auth()->user()->id,
            'type'=>$this->request->type,
            'title'=>$this->request->title,
            'slug'=>Str::slug($this->request->title),
            '_token'=>$this->encrypt(),
            'is_default'=>$this->request->is_default ?? false,
        ];
        PaymentMethod::create($data);
    }
    public function update(PaymentMethod $paymentMethod)
    {
        $data = [
            'user_id'=>auth()->user()->id,
            'type'=>$this->request->type,
            'title'=>$this->request->title,
            'slug'=>Str::slug($this->request->title),
            '_token'=>$this->encrypt(),
            'is_default'=>$this->request->is_default ?? false,
        ];
        if($this->request->has('is_default')){
            PaymentMethod::where('id', '!=', $paymentMethod->id)->update([
                'is_default'=>0,
            ]);
        }
        $paymentMethod->update($data);
    }
    private function encrypt()
    {
        return Crypt::encryptString($this->request->token);
    }
}