<?php

namespace App\Http\Controllers\Customer;

use PDO;
use App\Models\Local;
use App\Models\seller;
use App\Models\Country;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use App\Models\New_Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Mail\CustomerConfirmation;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Actions\Customer\SignupSeller;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Session;
use App\Actions\Customer\SignupCustomer;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\SellerSignUpRequest;
use App\Models\Countrey;

class SignupController extends Controller
{
    use HasRoles;
    protected $seller = null;
    public function __construct(seller $seller)
    {
        $this->seller = $seller;
    }
    public function sellerVerify()
    {
        $countries = Country::where('status', 1)->get();
        $provinces = Province::get();
        $roles = Role::get();

        return view('frontend.seller.sellerverify', compact('countries', 'provinces', 'roles'));
    }

    public function getOtp(Request $request)
    {
        $phone = $request->phone;
        $validate = Validator::make($request->all(), [
            'phone' => 'required|integer|'
        ]);
        if ($validate->fails()) {
            $response = [
                'error' => true,
                'msg' => 'Plz Provide Valid Phone Number'
            ];
            return response()->json($response, 200);
        }

        try {
            $already_exists = seller::where('phone', $phone)->first();
            if ($already_exists) {
                $response = [
                    'error' => true,
                    'msg' => 'Your Phone Has Already Register'
                ];
                return response()->json($response, 200);
            }
            $otp = rand(100000, 999999);
            $message = "Your OTP for " . config('app.name') . " Seller Registration Verification is :" . $otp;
            $this->sendSMS($phone, $user = 'Seller', $message);
            $session_data = [
                'seller_otp' => $otp,
                'date' => Carbon::now(),
                'phone' => $phone
            ];
            session()->put('seller_otp_verify', $session_data);
            return view('frontend.seller.otpmodal', compact('phone'));
            // return view('frontend.seller.sellersignup', compact('phone'));
        } catch (\Throwable $th) {
            $response = [
                'error' => true,
                'msg' => $th->getMessage()
            ];
            return response()->json($response, 200);
        }

      
    }

    public function verifyOtpWithPhone(Request $request)
    {
        $session_value=session('seller_otp_verify');
        if(!$session_value)
        {
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'SomeThing Went Wrong !!'
            ];
            return response()->json($response,200);
        }
        $phone=$request->phone;
        $otp=$request->otp;
        $session_phone=$session_value['phone'];
        $session_otp=$session_value['seller_otp'];
        if($session_phone !=$phone || $session_otp!=$otp)
        {
            $response=[
                'error'=>true,
                'data'=>null,
                'msg'=>'Plz Provide Valid Otp !!'
            ];
            return response()->json($response,200);
        }
        // session()->forget('seller_otp_verify');
        return view('frontend.seller.sellersignup', compact('phone','otp'));
    }

    public function sellerSignup(Request $request)
    {
        $rules = array(
            'name' => 'required|string',
            'email' => 'required|email|unique:sellers,email',
            'phone' => 'required|integer|unique:sellers,phone',
            'otp' => 'required|integer',
            'password' => 'required|confirmed|min:6',
            'company_name' => 'required|string'
        );
        $v = Validator::make($request->all(), $rules);
        if (!$v->passes()) {
            $messages = $v->messages();

            foreach ($rules as $key => $value) {
                $verrors[$key] = $messages->first($key);
            }
            $response_values = array(
                'validate' => true,
                'validation_failed' => 1,
                'errors' => $verrors
            );
            return response()->json($response_values, 200);
        }
        $data = $request->all();
        $session_data = session('seller_otp_verify');
        if ($session_data['seller_otp'] != $data['otp']) {
            $response = [
                'otp' => true,
                'msg' => 'Verification Code Doesnot Match !!',
            ];
            return response()->json($response, 200);
        }
        if ($session_data['phone'] != $data['phone']) {
            $response = [
                'phone' => true,
                'msg' => 'Phone  Doesnot Match !!',
            ];
            return response()->json($response, 200);
        }
        try {
            $slug = $this->seller->getSlugs($request->company_name);
            $seller = Seller::create(
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'status' => '2',
                    'verify_otp' => $session_data['seller_otp'],
                    'email_verified_at' => Carbon::now(),
                    'company_name' => $request->company_name,
                    'slug' => $slug
                ]
            );
            $seller->assignRole('seller');
            $request->session()->flash('success', 'Your Registration Process Has Been Completed Successfully !!');
            $response = [
                'error' => false,
                'msg' => 'Success',
                'url' => route('sellerLogin')
            ];
            return response()->json($response, 200);
        } catch (\Throwable $th) {
            $response = [
                'error' => true,
                'msg' => $th->getMessage(),
            ];
            return response()->json($response, 200);
        }
    }



    public function signup()
    {
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
        $countries = Country::where('status', 1)->get();
        $provinces = Province::get();
        $roles = Role::get();
        $allCountries=Countrey::where('status',"Active")->get();
        return view('frontend.customer.signup', compact('countries', 'provinces', 'roles', 'collect','allCountries'));
    }

    public function getAllCountryData(Request $request){
        $selectedCountry=Countrey::where('status',"Active")->where('id','92')->first();
        $response=[
            'error'=>false,
            'data'=>$selectedCountry
        ];
        return response()->json($response,200);
    }
    public function customerSignup(Request $request)
    {
        // dd($request->all());
        return (new SignupCustomer())->toResponse($request);
    }

    public function showOTPForm(Request $request)
    {
        $customer = New_Customer::where('email', $request->email_or_phone)->orWhere('phone', $request->email_or_phone)->first();
        if ($customer != null) {
            $customer->verify_otp = rand(100000, 999999);
            $message = "Your OTP for " . config('app.name') . " Customer Registration is :" . $customer->verify_otp;
            $this->sendSMS($customer->phone, $customer->name, $message);
            Mail::to($customer->email)->send(new CustomerConfirmation($customer));
            $customer->save();
            session()->put('customer_otp_form', 'show');
            $response = [
                'error' => false,
                'msg' => 'We sent you new OTP Code, Please check you email or phone.',
            ];
            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'msg' => 'The email or phone is not match with our records, Please sign up.',
            ];
            return response()->json($response, 200);
        }
    }

    public function customerVerify(Request $request)
    {
        $otp_form = $request->session()->get('customer_otp_form');
        if ($otp_form != null) {
            return view('auth.otp');
        } else {
            return redirect('/');
        }
    }


    public function showOTPFormSeller(Request $request)
    {
        $seller = seller::where('email', $request->email_or_phone)->orWhere('phone', $request->email_or_phone)->first();
        if ($seller == null) {
            $response = [
                'error' => true,
                'msg' => 'OPPs The Email or Phone is not match with our record, Please Sign Up.'
            ];
            return response()->json($response, 200);
        }

        if ($seller != null) {
            $status = 'Invalid OTP! Please check your message.';
            $seller->verify_otp = rand(100000, 999999);
            $message = "Your OTP for " . config('app.name') . " Customer Registration is :" . $seller->verify_otp;
            $this->sendSMS($seller->phone, $seller->name, $message);
            Mail::to($seller->email)->send(new CustomerConfirmation($seller));
            $status = $seller->save();
            $response = [
                'error' => false,
                'msg' => 'We sent OTP Code, Please check your email or phone.',
            ];
            session()->put('seller_otp_form', 'show');
            return response()->json($response, 200);
        } else {
            $response = [
                'error' => true,
                'msg' => 'The email or phone is not match with our records, Please try again',
            ];
            return response()->json($response, 200);
        }
    }

    public function sendSMS($phone, $user, $message)
    {
        $args = http_build_query(array(
            'token' => 'v2_cW6mkM6ZFC29LdP3NNDWyalAoZf.2SJT',
            'from'  => 'Glass Pipe',
            'to'    =>  $phone,
            'text'  => 'Dear ' . $user . ',' . $message
        ));

        // v2_qzDiIfZoYem9kVQXcwOPFWmGch2.8sm8
        # Make the call using API.
        $url = "http://api.sparrowsms.com/v2/sms/";

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


    public function verification(Request $request)
    {

        $customer = DB::table('tbl_customers')->where('verify_otp', $request->otp)->first();

        if ($customer != null) {
            if ($customer->email_verified_at == null) {
                New_Customer::where('verify_otp', $customer->verify_otp)->update(['email_verified_at' => Carbon::now()]);
                return redirect()->intended(route('Clogin'))->with('success', 'Your email has been verified. Please Log in to continue.');
            } else {
                $request->session()->flash('success', 'You are already verified');
                return redirect()->route('Clogin');
            }
        } else {
            $request->session()->flash('error', 'OOPs Your otp is not matched with our reecord');
            return redirect('/');
        }
    }


    public function sellerVerification(Request $request)
    {
        $seller = DB::table('sellers')->where('verify_otp', $request->otp)->first();
        if ($seller != null) {
            if ($seller->email_verified_at != null) {
                return redirect()->intended(route('sellerLogin'))->with('success', 'You are already verified, Please Log in.');
            } else {
                if ($seller->verify_otp == $request->otp) {
                    Seller::where('verify_otp', $seller->verify_otp)->update(['email_verified_at' => Carbon::now()]);
                    return redirect()->intended(route('sellerLogin'))->with('success', 'Your email has been verified. Please Log in to continue.');
                } else {
                    $status = 'Invalid OTP! Email could not be verified!';
                    return view('auth.sellerotp', compact('status', 'seller'));
                }
            }
        } else {
            // return back()->with('error', 'Your OTP is Not Matched with Our Record.');
            return redirect('/')->with('error', 'Your OTP is Not Matched with Our Record.');
        }
    }



    // public function signupsellers()
    // {

    //     $countries = Country::where('status', 1)->get();
    //     $provinces = Province::get();
    //     $roles = Role::get();

    //     return view('frontend.seller.signup', compact('countries', 'provinces', 'roles'));
    // }

  

    public function verifyToken(Request $request)
    {
        $show_form = $request->session()->get('seller_otp_form');
        if ($show_form != null) {
            return view('auth.sellerotp');
        } else {
            return redirect('/');
        }
    }
}
