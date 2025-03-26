<?php

namespace App\Actions\Customer;

use App\Models\City;
use App\Models\Local;
use App\Models\District;
use App\Models\Province;
use App\Models\New_Customer;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UpdateCustomerProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $customer
     * @param  array  $input
     * @return void
     */
    public function update(New_Customer $customer, array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('customers')->ignore($customer->id),
            ],
            'area' => ['required'],
            'phone' => ['nullable'],
            'district' => ['required'],
            'status' => ['nullable'],
            'province' => ['required']
        ])->validate();
        $province = Province::where("id",'=',$input['province'])->value('eng_name');
        $district = District::where("id",'=',$input['district'])->value('np_name');
        $area = City::where("id",'=',$input['area'])->value('city_name');

        if (
            $input['email'] !== $customer->email &&
            $customer instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($customer, $input);
        } else {
            $customer->forceFill([
                'name' => $input['name'],
                'email' => $input['email'],
                'phone' => $input['phone'] ?? null,
                'area' => $area ?? null,
                'province' => $province ?? null,
                'status' => empty($input['status']) ?  '0' : '1',
                // 'gender' => $input['gender'],
                'district' => $district,
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  mixed  $customer
     * @param  array  $input
     * @return void
     */
    protected function updateVerifiedUser($customer, array $input)
    {
        $customer->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'] ?? null,
            'address' => $input['address'] ?? null,
            'photo' => $input['photo'] ?? null,
            'email_verified_at' => null,
        ])->save();

        $customer->sendEmailVerificationNotification();
    }
}
