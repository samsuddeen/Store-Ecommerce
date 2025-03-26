<?php
namespace App\Http\Controllers;



use Exception;
use App\HBL\API\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Validator;

class HblController extends Controller
{
    public function store(Request $request)
    {
        try{
            $orderDetails=$request->session()->get('hblsessiondata');
            if(!$orderDetails || $orderDetails==null){
                throw new Exception('Something Went Wrong Plz Try Again !!');
            }
            $validator = Validator::make($request->all(), [
                'input_currency'=>'required',
                'input_amount'=>'required|numeric'
            ]);
            if($validator->fails()){
                throw new Exception('Something Went Wrong Plz Try Again !!');
            }
            session()->put('currency', $request->input_currency);
            session()->put('amount', $request->input_amount);
            $payment = new Payment();
            $currencyValue='USD';
            $amountValue=$request->input_amount;
            $refId=$orderDetails['order']['ref_id'];
            $cartItem=$orderDetails['cart_item'];
            $packageName='';
            foreach($cartItem as $itemName){
                $packageName.=$itemName->product_name.',';
            }
            // dd($packageName);
            $joseResponse = $payment->ExecuteFormJose($currencyValue,$amountValue,$refId,$packageName);
            $response_obj = json_decode($joseResponse);
            return redirect($response_obj->response->Data->paymentPage->paymentPageURL);
            exit();
        }catch(\Throwable $th){
            DB::rollBack();
            $request->session()->flash('error',$th->getMessage());
            return redirect()->route('index');
        }
    }

    public function storeGuest(Request $request)
    {
        try{
            $orderDetails=$request->session()->get('hblsessiondata');
            if(!$orderDetails || $orderDetails==null){
                throw new Exception('Something Went Wrong Plz Try Again !!');
            }
            $validator = Validator::make($request->all(), [
                'input_currency'=>'required',
                'input_amount'=>'required|numeric'
            ]);
            if($validator->fails()){
                throw new Exception('Something Went Wrong Plz Try Again !!');
            }
            session()->put('currency', $request->input_currency);
            session()->put('amount', $request->input_amount);
            $payment = new Payment();
            $currencyValue='USD';
            $amountValue=$request->input_amount;
            $refId=$orderDetails['order']['ref_id'];
            $cartItem=$orderDetails['cart_item'];
            $packageName='';
            foreach($cartItem as $itemName){
                $packageName.=$itemName['product_name'].',';
            }
            $joseResponse = $payment->executeFormJoseGuest($currencyValue,$amountValue,$refId,$packageName);
            $response_obj = json_decode($joseResponse);
            return redirect($response_obj->response->Data->paymentPage->paymentPageURL);
            exit();
        }catch(\Throwable $th){
            DB::rollBack();
            $request->session()->flash('error',$th->getMessage());
            return redirect()->route('index');
        }
    }

    public function storeDirect(Request $request)
    {
        try{

            $orderDetails=$request->session()->get('hblsessiondataDirect');
            if(!$orderDetails || $orderDetails==null){
                throw new Exception('Something Went Wrong Plz Try Again !!');
            }
            $validator = Validator::make($request->all(), [
                'input_currency'=>'required',
                'input_amount'=>'required|numeric'
            ]);
            if($validator->fails()){
                throw new Exception('Something Went Wrong Plz Try Again !!');
            }
            session()->put('currency', $request->input_currency);
            session()->put('amount', $request->input_amount);
            $payment = new Payment();
            $currencyValue='USD';
            $amountValue=$request->input_amount;
            $refId=$orderDetails['order']['ref_id'];
            $cartItem=$orderDetails['cart_item'];
            $packageName=$cartItem['product_name'];
            $joseResponse = $payment->ExecuteFormJoseDirectFromGuest($currencyValue,$amountValue,$refId,$packageName);
            $response_obj = json_decode($joseResponse);
            return redirect($response_obj->response->Data->paymentPage->paymentPageURL);
            exit();
        }catch(\Throwable $th){
            DB::rollBack();
            $request->session()->flash('error',$th->getMessage());
            return redirect()->route('index');
        }
    }

    public function saveData()
    {
        dd('biebk');
        // DB::beginTransaction();
        // try {
            $payment = \App\Models\Payment::create([
                'currency'=>session()->get('currency'),
                'amount'=>session()->get('amount'),
            ]);
            DB::commit();
            session()->flash('success', 'Successfully Paid');
            return redirect('/payment');
        // } catch (\Throwable $th) {
        //     DB::rollBack();
        //     session()->flash('error', 'Sorry your payment has not been completed');
        //     return back();
        // }
    }
}
