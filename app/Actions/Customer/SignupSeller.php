<?php
namespace App\Actions\Customer;

use App\Models\User;
use App\Models\Local;
use App\Models\District;
use App\Models\Province;
use Illuminate\Support\Facades\Hash;

class SignupSeller
{
    public function toResponse($request)
    {
        dd($request->all());
        $credential = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'confirm_password' => 'required|same:password',
            'province' => ['required'],
            'district' => ['required'],
            'area' => ['required'],
            'company' => ['required'],
            'name' => ['required'],
            'phone' => ['required'],
            'agree' => ['required'],
        ]);

        $data = $request->except('password');
        $data['password'] = Hash::make($request->password);
        $data['province'] = Province::where('id',$request->province)->value('eng_name');
        $data['district'] = District::where('dist_id',$request->district)->value('np_name');
        $data['area'] = Local::where('id',$request->area)->value('local_name');
        $user = User::create($data);
        $user->assignRole($request->role);
        $request->session()->regenerate();
        return redirect()->route('Cdashboard');
}
}
