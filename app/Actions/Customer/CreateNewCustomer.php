<?php

namespace App\Actions\Customer;

use App\Models\Local;
use App\Models\Customer;
use App\Models\District;
use App\Models\Province;
use App\Models\New_Customer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Actions\Fortify\PasswordValidationRules;
use Carbon\Carbon;

class CreateNewCustomer
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(New_Customer::class),
            ],
            'password' => $this->passwordRules(),
            'province' => ['required'],
            'phone' => ['nullable','numeric', 'unique:tbl_customers,phone'],
            'district' => ['required'],
            'area' => ['required'],
            'status' => ['nullable'],
            'zip' => ['nullable','numeric'],
            // 'birthday' => ['nullable']
        ])->validate();

        $province = Province::where("id",'=',$input['province'])->value('eng_name');
        $district = District::where("dist_id",'=',$input['district'])->value('np_name');
        $area = Local::where("id",'=',$input['area'])->value('local_name');
        $latest_date = Carbon::now()->toDateTimeString();
        // dd(empty($input['status']));
        return New_Customer::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'phone' => $input['phone'] ?? null,
            'area' => $area ?? null,
            'province' => $province ?? null,
            'status' => empty($input['status']) ?  false : true,
            'district' => $district,
            'zip' => $input['zip'],
            'email_verified_at' => $latest_date,
            // 'birthday' => $input['birthday']
        ]);
    }
}
