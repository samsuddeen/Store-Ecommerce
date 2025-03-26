<?php

namespace App\Http\Controllers\WholeSeller;

use App\Models\Country;
use App\Models\Province;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Actions\WholeSeller\SignUpWholeSeller;

class AuthenticateController extends Controller
{
    public function login()
    {
        if (auth()->guard('wholeseller')->check()) {
            request()->session()->flash('success', 'Welcome Back !!');
            dd('whole seller dashboard');
            // return redirect()->route('Cdashboard');
        }
        return view('wholeseller.auth.login');
    }

    public function requestLogin(Request $request)
    {
        dd($request->all());
        if (auth()->guard('wholeseller')->check()) {
            request()->session()->flash('success', 'Welcome Back !!');
            dd('whole seller dashboard');
            // return redirect()->route('Cdashboard');
        }
        return view('wholeseller.auth.login');
    }

    public function register(Request $request)
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
        return view('wholeseller.auth.register', compact('countries', 'provinces', 'roles', 'collect'));
    }

    public function requestRegister(Request $request)
    {
        DB::beginTransaction();
        // try{
            $data=(new SignUpWholeSeller($request))->registerResponse();
            DB::commit();
            $request->session()->flash('success','Registration Completed Successfully !!');
            dd('success');
            return redirect()->back();
        // }catch(\Throwable $th){
        //     DB::rollBack();
        //     $request->session()->flash('error','Something Went Wrong !!');
        //     return redirect()->back();
        // }
    }
}
