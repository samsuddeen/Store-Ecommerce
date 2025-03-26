<?php

namespace App\Actions\Customer;

use App\Models\User;
use App\Models\Local;
use App\Models\Seller;
use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Mail\CustomerConfirmation;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Traits\HasRoles;
use App\Actions\Notification\NotificationAction;

class SignupCustomer
{
    use HasRoles;
    protected $customers = null;

    public function construct(New_Customer $customers)
    {
        $this->customers = $customers;
    }

    public function toResponse(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:tbl_customers,email',
            'phone' => 'required|unique:tbl_customers,phone',
            'password' => 'required|confirmed|min:8',
            'wholeseller' => 'nullable|in:1',
            'g-recaptcha-response'=>'nullable',
            'bussiness_name'=>'nullable|string',
            'name'=>'nullable|string',
            'pan'=>'nullable|string'
        ]);

        if($request->wholeseller=='1'){
            $validated = $request->validate([
                'country' => 'required|exists:countreys,id',
            ]);
        }
        $role = Role::find(4);
        $province = Province::where("id", '=', $request->province)->value('eng_name');
        $district = District::where("dist_id", '=', $request->district)->value('np_name');
        $area = Local::where("id", '=', $request->area)->value('local_name');

        $agent = new Agent();
        $device = $agent->device();
        $platform = $agent->platform();
        $browser = $agent->browser();
        $desktop = $agent->isDesktop();
        $agent->isRobot();

        $info = [
            'Device' => $device,
            'Platform' => $platform,
            'Browser' => $browser,
            'Desktop' => $desktop,
        ];
        $collect = collect($info);
        $customer = New_Customer::updateOrCreate(
            [
                'email' => $request->email,
                'phone' => $request->phone
            ],
            [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'zip' => $request->zip,
                'province' => $province,
                'district' => $district,
                'area' => $area,
                'status' => '1',
                'data' => $collect,
                'password' => Hash::make($request->password),
                'verify_token' => Str::random(25),

                'verify_otp' => rand(100000, 999999),

                'wholeseller' => $request->wholeseller ?? 0,
                'bussiness_name'=>$request->bussiness_name ?? null,
                'country_id'=>$request->country ?? null,
                'pan_num'=>$request->pan ?? null
            ]
        );
        $message = "Your OTP for " . config('app.name') . " User Registration is :" . $customer->verify_otp;
        Mail::to($customer->email)->send(new CustomerConfirmation($customer));
        $this->sendsms($customer->phone, $customer->name, $message);

        $notification_data = [
            'from_model' => get_class($customer->getModel()),
            'from_id' => $customer->id,
            'to_model' => get_class(User::first()->getModel()) ?? null,
            'to_id' => User::first()->id,
            'title' => 'New Customer Registration',
            'summary' => 'Please See Detail',
            'url' => url('admin/customer', $customer->id),
            'is_read' => false,
        ];
        (new NotificationAction($notification_data))->store();

        $status = 'We have resent you the code again!';
        session()->put('customer_otp_form', 'show');
        return redirect()->route('customer.verify');
    }

    public function sendSMS($phone, $user, $message)
    {
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'Glass Pipe Nepal',
            'to'    =>  $phone,
            'text'  => 'Dear ' . $user . ',' . $message
        ));
        $url = "http://api.sparrowsms.com/v2/sms/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // Response
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }

    private function storeSeller($request)
    {
        $data = $request->except('password');
        $data['password'] = Hash::make($request->password);
        $data['province'] = Province::where('id', $request->province)->value('eng_name');
        $data['district'] = District::where('dist_id', $request->district)->value('np_name');
        $data['area'] = Local::where('id', $request->area)->value('local_name');
        $data['verify_otp'] = rand(100000, 999999);
        $data['email'] = $request->email;
        $data['verify_token'] = Str::random(25);
        $user = Seller::create($data);
        $user->assignRole('seller');
        $request->session()->regenerate();
        return $user;
        return redirect()->route('dashboard');
    }

    private function storeUser($request)
    {
        $data = $request->except('password');
        $data['password'] = Hash::make($request->password);
        $data['province'] = Province::where('id', $request->province)->value('eng_name');
        $data['district'] = District::where('dist_id', $request->district)->value('np_name');
        $data['area'] = Local::where('id', $request->area)->value('local_name');
        $user = User::create($data);
        $user->assignRole($request->role);
        $request->session()->regenerate();
        return $user;
        return redirect()->route('Cdashboard');
    }
}
