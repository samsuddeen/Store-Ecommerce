<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use App\Enum\Gender;
use App\Models\Local;
use App\Events\LogEvent;
use App\Models\District;
use App\Models\Province;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Data\Filter\FilterData;
use App\Data\Customer\CustomerData;
use App\Data\Filter\RetriveRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Enum\Customer\CustomerStatusEnum;
use App\Actions\Customer\CreateNewCustomer;
use App\Actions\Customer\UpdateCustomerProfileInformation;
use App\Models\City;
use App\Models\Order;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:customer-read'], ['only' => ['index']]);
        $this->middleware(['permission:customer-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:customer-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:customer-delete'], ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [
            'customerCount' => New_Customer::count(),
        ];

        $data['filters'] = (new FilterData($request))->getData();        
        return view('admin.customer.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customer = new New_Customer();
        $province = Province::get();
        $district = District::get();
        $area = City::get();
        // $genders = Gender::cases();
        return view('admin.customer.form', compact('customer','province','district','area'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try {
            (new CreateNewCustomer())->create($request->all());
            session()->flash('success', 'Customer Created Successfully');
            return redirect()->route('customer.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = New_Customer::findOrFail($id);
        $orders = Order::where('user_id',$user->id)->paginate(10);
        $order_count = Order::where('user_id',$user->id)->count();
        return view('admin.customer.show', compact('user','orders','order_count'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(New_Customer $customer)
    {
        // $genders = Gender::cases();
        $province = Province::get();
        $district = District::get();
        $area = City::get();
        return view('admin.customer.form', compact('customer','province','district','area'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, New_Customer $customer)
    {
        try {
            (new UpdateCustomerProfileInformation())->update($customer, $request->all());
            session()->flash('success', 'customer updated Successfully');
            return redirect()->route('customer.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, New_Customer $customer)
    {
        try {
            $customer->delete();
            session()->flash('success', 'customer deleted Successfully');
            return redirect()->route('customer.index');
        } catch (\Throwable $th) {
            session()->flash('error', $th->getMessage());
            return redirect()->back()->withInput();
        }
    }
    public function statusCustomerAction(Request $request)
    {  
        $filteration = (new FilterData($request))->getData();
        $status = (new CustomerData())->customerStatus($filteration);
        return $status;
    }

    public function blockedCustomer(Request $request)
    {   
        $filters = (new FilterData($request))->getData();
        $data['filters']=$filters;
        $customer = new CustomerData($filters);
        $data['title'] = $customer->getTitle();
        return view('admin.customer.index',$data);

    }

    public function googleCustomer(Request $request)
    {
        // dd($request->all());
       $filters = (new FilterData($request))->getData();
    //    dd($filters);
        $data['filters'] = $filters;
        $customer = new CustomerData($filters);
        $data['social'] = $customer->getSocial();
        // dd($data);
        return view('admin.customer.index',$data);
    }

    public function facebookCustomer(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $customer = new CustomerData($filters);
        $data['filters'] = $filters;
        $data['social'] = $customer->getSocial();
        return view('admin.customer.index',$data);

    }
    public function githubCustomer(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $customer = new CustomerData($filters);
        $data['filters'] = $filters;
        $data['social'] = $customer->getSocial();
        return view('admin.customer.index',$data);

    }

    public function webCustomer(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $customer = new CustomerData($filters);
        $data['social'] = $customer->getSocial();
        $data['filters'] = $filters;
        return view('admin.customer.index',$data);
    }

    public function Android(Request $request)
    {
        $filters = (new FilterData($request))->getData();
        $customer = new CustomerData($filters);
        $data['social'] = $customer->getSocial();
        $data['filters'] = $filters;
        return view('admin.customer.index',$data);
    }

    public function Ios(Request $request)
    {
        // dd($request->all());
        $filters = (new FilterData($request))->getData();
        $customer = new CustomerData($filters);
        $data['social'] = $customer->getSocial();
        $data['filters'] = $filters;
        return view('admin.customer.index',$data);
    }

    public function Other(Request $request)
    {
        // dd($request->all());
        $filters = (new FilterData($request))->getData();
        $customer = new CustomerData($filters);
        $data['social'] = $customer->getSocial();
        $data['filters'] = $filters;
        return view('admin.customer.index',$data);
    }
    

}
