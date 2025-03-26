<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Mail\SendOtp;
use App\Models\Local;
use App\Models\District;
use App\Models\Province;
use App\Mail\Registermail;
use Illuminate\Support\Str;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use App\Models\Api\GenerateOtp;
use App\Traits\ReferalCodeTrait;
use App\Models\Api\PasswordReset;
use App\Mail\CustomerConfirmation;
use App\Models\UserBillingAddress;
use Illuminate\Support\Facades\DB;
use App\Models\UserShippingAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Actions\ReferalCode\ReferalCodeAction;
use App\Actions\Notification\NotificationAction;

use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    use ReferalCodeTrait;
    protected $user = null;
    protected $new_customer = null;
    protected $usershippingaddress = null;
    protected $customer = null;
    protected $userbillingaddress = null;
    public function __construct(User $user, UserShippingAddress $usershippingaddress, New_Customer $new_customer, UserBillingAddress $userbillingaddress)
    {
        $this->user = $user;
        $this->usershippingaddress = $usershippingaddress;
        $this->new_customer = $new_customer;
        $this->userbillingaddress = $userbillingaddress;
    }


    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:tbl_customers,email',
            'phone' => 'required|unique:tbl_customers,phone',
            'password' => 'required|confirmed|min:8',
            'wholeseller' => 'required|in:0,1',
        ]);
        if ($request->wholeseller == '1') {
            $validator = Validator::make($request->all(), [
                'bussinessName' => 'required|string',
                'address' => 'required|string',
                'country' => 'required|exists:countreys,id',
                'pan_num'=>'required|string'
            ]);
        }
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validator->errors()
            ];
            return response()->json($response, 200);
        }
        DB::beginTransaction();
        try {
            $customer_exists = $this->new_customer->where('email', $request->email)->first();
            if ($customer_exists) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Email Already Exists Plz Try With New Email Or Got To Login Page !!'
                ];
                return response()->json($response, 200);
            }
            $dataValue = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'zip' => $request->zip,
                'status' => '1',
                'password' => bcrypt($request->password),
                'verify_token' => Str::random(25),
                'verify_otp' => rand(100000, 999999),
                'wholeseller' => $request->wholeseller ?? 0,
                'bussiness_name' => $request->bussinessName ?? null,
                'country_id' => $request->country ?? null,
                'pan_num'=>$request->pan_num ?? null
            ];
            $this->new_customer->fill($dataValue);
            $status = $this->new_customer->save();
            if ($status) {
                $message = "Your OTP for " . config('app.name') . " User Registration is :" . $this->new_customer->verify_otp;
                Mail::to($this->new_customer->email)->send(new CustomerConfirmation($this->new_customer));
                $this->sendSMSApi($this->new_customer->phone,  $this->new_customer->name, $message);

                $notification_data = [
                    'from_model' => get_class($this->new_customer->getModel()),
                    'from_id' =>  $this->new_customer->id,
                    'to_model' => get_class(User::first()->getModel()) ?? null,
                    'to_id' => User::first()->id,
                    'title' => 'New Customer Registration',
                    'summary' => 'Please See Detail',
                    'url' => url('admin/customer',  $this->new_customer->id),
                    'is_read' => false,
                ];
                (new NotificationAction($notification_data))->store();
                DB::commit();
                $response = [
                    'error' => false,
                    'data' => $this->new_customer,
                    'otp' => $this->new_customer->verify_otp,
                    'msg' => 'Registration Completed Successfully !!'
                ];
                return response()->json($response, 200);
            } else {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry There Was A Problem While Registering Your Account! Plz Try Again !!'
                ];
                return response()->json($response, 200);
            }
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Something Went Wrong !!'
            ];
            return response()->json($response, 200);
        }
    }

    public function sendSMSApi($phone, $user, $message)
    {
        $args = http_build_query(array(
            // 'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'token' => 'v2_D6dwvR99byLEZwjvr2qc7upDQyn.g7y8',
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

    public function sendSms($customer)
    {
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'Glass Pipe',
            'to'    =>  (int)$customer->phone,
            'text'  => 'Dear, ' . $customer->name . ' Plz Use This Code To Verify Your Account ' . $customer->verify_otp
        ));
        $url = "https://api.sparrowsms.com/v2/sms";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
    }

    public function verifyRegisterUser(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|exists:tbl_customers,email',
            'otp' => 'required|string'
        ]);

        if ($validate->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validate->errors()
            ];

            return response()->json($response, 200);
        }
        DB::beginTransaction();
        try {
            $already_login = New_Customer::where('email', $request->email)->first();
            if (!$already_login) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Your Email Has Not Been Registe,Plz Register First!!'
                ];
                return response()->json($response, 200);
            }
            if ($already_login->email_verified_at != null) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Your Email Is Already Verified!!'
                ];
                return response()->json($response, 200);
            }
            $user = New_Customer::where('email', $request->email)->where('verify_otp', $request->otp)->first();
            if (!$user) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Email Or Otp Doesnot Match Plz Provide Valid Email Or Otp !!'
                ];

                return response()->json($response, 200);
            }
            $user->email_verified_at = Carbon::now();
            $user->verify_otp = null;
            $status = $user->save();
            if ($status) {
                $response = [
                    'error' => false,
                    'data' => $user,
                    'msg' => 'User Verified Successfully !!'
                ];
            } else {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry !! There Was A Problem While Verifying Your Email'
                ];
            }
            DB::commit();
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! Something Went Wrong !!'
            ];
            return response()->json($response, 200);
        }
    }


    public function resendRegisterOtp(Request $request)
    {
        // return $request->all();
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|exists:tbl_customers,email',
        ]);
        if ($validate->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validate->errors()
            ];

            return response()->json($response, 200);
        }
        DB::beginTransaction();
        try{
            $email = New_Customer::where('email', $request->email)->first();
            if (!$email) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Sorry Email Doesnot Exists In Our Database Plz Register First !!'
                ];
                return response()->json($response, 200);
            }
    
            if ($email->email_verified_at != null) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => 'Your Email Is Already Verified!!'
                ];
                return response()->json($response, 200);
            }
    
            $otp = rand(100000, 999999);
            $email->verify_otp = $otp;
            $status = $email->save();
            if ($status) {
                $this->sendSms($email);
                Mail::to($email->email)->send(new Registermail($email));
                $response = [
                    'error' => false,
                    'data' => null,
                    'message' => 'New Otp Sent Successfully !'
                ];
            } else {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => 'Sorry !! There Was A Problem While Generating New Otp '
                ];
            }
            DB::commit();
            return response()->json($response, 200);
        }catch(\Throwable $th){
            DB::rollBack();
            $response = [
                'error' => true,
                'data' => null,
                'message' => 'Sorry !! Something Went Wrong !! '
            ];
            return response()->json($response,200);
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required',
            'password' => 'required',
            'verify_from' => 'required|string'
        ]);
        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }
        
        if ($request->verify_from === 'email') {
            $existCustomerData=$user = New_Customer::withTrashed()->where('email', $request->email_or_phone)->first();
            if(Hash::check($request->password, $user->password)){
                $existCustomerData->deleted_at=null;
                $existCustomerData->save();
            }else{
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => 'Phone Or Password Doesnot Match !'
                ];
                return response()->json($response, 500);
            }
            if (Auth::guard('customer')->attempt(['email' => $request->email_or_phone, 'password' => $request->password])) {
                $user = Auth::guard('customer')->user();
                if ($user->referal_code == null) {
                    $referalCode = $this->generatereferalCode();
                    $user->update([
                        'referal_code' => $referalCode
                    ]);
                }
                if ($user->email_verified_at == null) {
                    $old_user = New_Customer::where('email', $request->email_or_phone)->first();

                    $old_user->verify_otp = rand(100000, 999999);
                    $old_user->save();
                    $this->sendSms($old_user);
                    Mail::to($old_user->email)->send(new Registermail($old_user));
                    $response = [
                        'error' => true,
                        'data' => null,
                        'message' => 'User Is Not Verified !! Otp Sent To Your Phone And Email'
                    ];

                    return response()->json($response, 200);
                }

                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['user'] = $user;
                $response = [
                    'error' => false,
                    'data' => $success,
                    'message' => 'Login Successfull !'
                ];

                return response()->json($response, 200);
            } else {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => 'Email Or Password Doesnot Match !'
                ];
                return response()->json($response, 500);
            }
        } else {
            $existCustomerData=$user = New_Customer::withTrashed()->where('phone', $request->email_or_phone)->first();
            if(Hash::check($request->password, $user->password)){
                $existCustomerData->deleted_at=null;
                $existCustomerData->save();
            }else{

                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => 'Phone Or Password Doesnot Match !'
                ];
                return response()->json($response, 500);
            }
            if (Auth::guard('customer')->attempt(['phone' => $request->email_or_phone, 'password' => $request->password])) {
                $user = Auth::guard('customer')->user();
                if ($user->email_verified_at == null) {
                    $old_user = New_Customer::where('phone', $request->email_or_phone)->first();
                    $old_user->verify_otp = rand(100000, 999999);
                    $old_user->save();
                    $this->sendSms($old_user);
                    Mail::to($old_user->email)->send(new Registermail($old_user));
                    $response = [
                        'error' => true,
                        'data' => null,
                        'message' => 'User Is Not Verified !! Otp Sent To Your Phone And Email'
                    ];

                    return response()->json($response, 200);
                }
                $success['token'] = $user->createToken('MyApp')->plainTextToken;
                $success['user'] = $user;
                $response = [
                    'error' => false,
                    'data' => $success,
                    'message' => 'Login Successfull !'
                ];

                return response()->json($response, 200);
            } else {
                $response = [
                    'error' => true,
                    'data' => null,
                    'message' => 'Phone Or Password Doesnot Match !'
                ];
                return response()->json($response, 500);
            }
        }
    }


    public function generateOtp(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 200);
        }



        $user = New_Customer::where('email', $request->email)->first();

        if ($user != null) {
            $code = rand(11111, 99999);

            $generateOtp = $code;
            $data = [
                'email' => $request->email,
                'otp' => $generateOtp
            ];
            $this->resetPasswordSms($user, $code);
            Mail::to($request->email)->send(new SendOtp($data));
            GenerateOtp::updateOrCreate([
                'email' => $request->email
            ], [
                'email' => $request->email,
                'otp' => $generateOtp
            ]);
            return response()->json($data, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry ! Your Email Has Not Been Register In Our Records !'
            ];
            return response()->json($response, 500);
        }
    }

    public function resetPasswordSms($customer, $code)
    {
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'InfoSMS',
            'to'    =>  $customer->phone,
            'text'  => 'Dear, ' . $customer->name . ' Plz Use This Code To Reset Your Password ' . $code
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


    public function getOtp(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string'
        ]);
        if ($validate->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validate->errors()
            ];
            return response()->json($response, 200);
        }
        $data = GenerateOtp::where('email', $request->email)->where('otp', $request->otp)->get();
        if (count($data) > 0) {
            $user = New_Customer::where('email', $data[0]->email)->firstOrFail();
            $token = Str::random(40);
            $datetime = Carbon::now()->format('Y-m-d H:i:s');

            PasswordReset::updateOrCreate(
                [
                    'email' => $user->email
                ],
                [
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => $datetime
                ]
            );
            GenerateOtp::where('email', $request->email)->delete();
            $response = [
                'error' => false,
                'data' => $user,
                'reset_token' => $token,
                'msg' => 'Otp Verified For Reset Password'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry ! Otp Doesnot Exit Or Expires !!'
            ];
            return response()->json($response, 500);
        }
    }

    
    public function resetPassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'reset_token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:5|confirmed'
        ]);

        if ($validate->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => $validate->errors()
            ];
            return response()->json($response, 200);
        }
        $reset = PasswordReset::where('token', $request->reset_token)->first();
        if (!$reset) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! Reset Token Does Not Match !!'
            ];
            return response()->json($response, 200);
        }
        $user = New_Customer::where('email', $request->email)->firstOrFail();
        if ($reset != null && $user != null) {
            $user->password = bcrypt($request->password);
            $user->save();

            $reponse = [
                'error' => false,
                'data' => null,
                'msg' => 'Password Reset Successfully !'
            ];

            PasswordReset::where('email', $user->email)->delete();

            return response()->json($reponse, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry ! Your Email Has No Access To Performed This Action'
            ];

            return response($response, 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $this->user = \Auth::user();
        if (!$this->user) {

            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! User Not Found In Records'
            ];
            return response()->json($response, 500);
        }
        $data = $request->except('photo', 'email');
        if ($request->hasFile('photo')) {
            $this->deleteExistingImage($this->user->photo); // Delete the old image

            $imagePath = $request->file('photo')->store('user', 'public'); // Upload and store the new image
            $data['photo'] = url('/') . '/storage/' . $imagePath;
        }
        $this->user->fill($data);
        $status = $this->user->save();

        if ($status) {
            $response = [
                'error' => false,
                'msg' => 'Your Profile Has been Updated Successfully !!'
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Sorry !! There Was A Problem While Updating Your Profile'
            ];
            return response()->json($response, 500);
        }
    }

    private function deleteExistingImage($imagePath)
    {
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    public function getUser(Request $request)
    {
        $this->user = New_Customer::with('UserShippingAddress')->find($request->user_id);

        if (!$this->user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Error !! User Not Found'
            ];

            return response()->json($response, 500);
        }
        $response = [
            'error' => false,
            'data' => $this->user,
            'msg' => 'User Details'
        ];
        return response()->json($response, 200);
    }

    public function getStaff(Request $request)
    {
        $staff = User::with('roles')->find($request->staff_id);

        if (!$staff) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Error !! User Not Found'
            ];

            return response()->json($response, 500);
        }
        $response = [
            'error' => false,
            'data' => $staff,
            'msg' => 'User Details'
        ];
        return response()->json($response, 200);
    }



    public function myDetails(Request $request)
    {

        $this->user = New_Customer::with('UserShippingAddress')->find($request->user_id);

        $province = Province::where('eng_name', $this->user->province)->first();
        $district = District::where('np_name', $this->user->district)->first();
        $area = Local::where('local_name', $this->user->area)->first();
        $this->user->province = $province->id ?? $this->user->province;
        $this->user->district = $district->id ?? $this->user->district;
        $this->user->area = $area->id ?? $this->user->area;
        if (!$this->user) {
            $response = [
                'error' => true,
                'data' => null,
                'msg' => 'Error !! User Not Found'
            ];

            return response()->json($response, 500);
        }
        $response = [
            'error' => false,
            'data' => $this->user,
            'msg' => 'User Details'
        ];
        return response()->json($response, 200);
    }

    public function socailLogin(Request $request)
    {
        $data = $request->all();
        if($request->email && $request->email !=null){
            $already_login = New_Customer::where('email', $request->email)->first();
        }else{
            $already_login = New_Customer::where('provider_id', $request->provider)->first();
        }
        
        if ($already_login) {
            //if user found 
            $already_login->social_provider = $request->social_provider;
            $already_login->provider_id = $request->provider;
            $already_login->verify_token = $request->access_token;
            $already_login->photo = $request->avatar;
            $already_login->email_verified_at = Carbon::now();
            $already_login->wholeseller = '1';
            if ($already_login->referal_code == null) {
                $referalCode = $this->generatereferalCode();
                $already_login->referal_code = $referalCode;
            }
            $already_login->save();
            return $this->successLogin($already_login);
        } else {
            //if not user record found 
            $validate = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'provider' => 'required',
                    'social_provider' => 'required|string',
                    'access_token' => 'nullable',
                    'avatar' => 'nullable'
                ]
            );
            if ($validate->fails()) {
                $response = [
                    'error' => true,
                    'data' => null,
                    'msg' => $validate->errors()
                ];
                return response()->json($response, 200);
            }

            $customer = new New_Customer();
            $data['verify_token'] = $request->provider;
            $data['photo'] = $request->avatar;
            $data['provider_id'] = $request->provider;
            $data['email_verified_at'] = Carbon::now();
            $data['password'] = bcrypt($data['verify_token']);
            $referalCode = $this->generatereferalCode();
            $data['referal_code'] = $referalCode;
            $data['wholeseller'] = '1';
            $customer->fill($data);
            $customer->save();
        }
        return $this->successLogin($customer);
    }

    protected function successLogin($data)
    {
        $success['token'] = $data->createToken('MyApp')->plainTextToken;
        $success['user'] = $data;
        $response = [
            'error' => false,
            'data' => $success,
            'msg' => 'Login Successfull'
        ];
        return response()->json($response, 200);
    }

    public function apiLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $response = [
                'error' => true,
                'data' => null,
                'message' => $validator->errors()
            ];
            return response()->json($response, 500);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $user = User::where('id', $user->id)->first();
            $user_role = $user->getRoleName();
            // Generate a unique bearer token
            $token = $user->createToken('MyApp')->plainTextToken;

            return response()->json(['status' => 200, 'error' => false, 'message' => 'Login successful', 'token' => $token, 'data' => $user, 'role' => $user_role]);
        }

        return response()->json(['status' => 401, 'error' => true, 'message' => 'Invalid Credentials']);
    }

    
    public function deleteProfile(Request $request)
    {
        $user = Auth::user();
        $customer = New_Customer::where('id', $user->id)->first();
        if ($customer) {
            $customer->delete();

            $response = [
                'status' => 200,
                'error' => false,
                'msg' => 'Your account has been deleted successfully!'
            ];

            return response()->json($response);
        } else {
            $response = [
                'status' => 404,
                'error' => false,
                'msg' => 'User not found'
            ];
            return response()->json($response);
        }
    }
}
