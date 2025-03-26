<?php
namespace App\Data\Customer;

use Illuminate\Support\Arr;
use App\Models\New_Customer;
use App\Enum\Social\SocialEnum;
use Illuminate\Support\Facades\DB;
use App\Enum\Order\OrderStatusEnum;
use Illuminate\Support\Facades\Request;
use App\Enum\Customer\CustomerStatusEnum;

class CustomerData
{
    protected $filters;
    protected $data=[];
    protected $status = [];
    function __construct($filters=null)
    {
        $this->filters = $filters;
    }

    public function getData()
    {
        $customers = New_Customer::orderBy('name')->get();
        $this->data['customers'] = $customers;
        return $this->data;
    }

    public function getActiveCustomers()
    {
        $customers = New_Customer::orderBy('name')->where('status', 1)->get();
        $this->data['customers'] =  $customers;
        return $this->data;
    }

    public function getCustomers()
    {
        $customers = New_Customer::where('id','!=',63)->when(Arr::get($this->filters, 'type'), function($q, $value){
            $q->where('status', $value);
        })->latest();
        return $customers;
    }
    
    public function customerStatus($filters)
    {   
        $filters;
        $status = New_Customer::orderBy('name')->where('id',$filters['customer_id'])->first();
        $this->data['status'] = $status->status;
        
        switch ($filters['status']) {
            case 'active':
                $status_value = CustomerStatusEnum::Active;
                $status = "Active";
                break;
            case 'inactive':
                $status_value = CustomerStatusEnum::Inactive;
                $status = "Inactive";
                break;
            case 'blocked':
                $status_value = CustomerStatusEnum::Blocked;
                $status = "Blocked";
                break;
            default:
                # code...
                break;
        }
        if ($status_value !== null) {
            DB::table('tbl_customers')->where('id', $filters['customer_id'])->update(['status' => $status_value]);
            return redirect()->back()->with('success', 'Status updated successfully');
        } else {
            return redirect()->back()->with('error', 'Invalid status provided');
        }
        // return [ 
        //     'status'=>$status,
        //     'status_value'=>$status_value,
        // ];
    }

    public function getTitle()
    {
        // $title = '';
        // $messageEnum = new CustomerStatusEnum();
        // if(Arr::get($this->filters, 'type')){
        //     $title = $messageEnum->getSingleValue(Arr::get($this->filters, 'type'));
        // }
        // return $title;
        $customer = New_Customer::where('id','!=',63)->when(Arr::get($this->filters, 'type'), function($q, $value){
            $q->where('status', CustomerStatusEnum::Blocked);
        })->get();
        return $customer;
    }
    
    public function getSocial()
    {
        $social = New_Customer::where('id','!=',63)->when(Arr::get($this->filters, 'social'), function($q, $value){
            $q->where('social_provider', $value);
        })->get();
        return $social;
    }

    public function customerReport()
    {
        
        $customer = New_Customer::when(Arr::get($this->filters, 'year'), function($q, $value){
            $q->whereYear('created_at', $value);
        })
        ->when(Arr::get($this->filters,'month'),function($q ,$value){
            $q->whereMonth('created_at',$value);
        })
        ->when(Arr::get($this->filters,'area'),function($q,$value){
                $q->where('area',$value);
        })
        ->when(Arr::get($this->filters,'district'),function($q,$value){
                $q->where('district',$value);
        })                                                                                                                                                              
        ->when(Arr::get($this->filters,'province'),function($q,$value){
        $q->where('province',$value);
        })
        ->get();
    

        return $customer;
    }
    

    public function customerData()
    {
        $area = New_Customer::select('area')->first();
        return $area;
    }

   
}