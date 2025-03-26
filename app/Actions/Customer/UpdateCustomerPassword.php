<?php

namespace App\Actions\Customer;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UpdateCustomerPassword

{
    use PasswordValidationRules;

    /**
     * Validate and update the customer's password.
     *
     * @param  mixed  $customer
     * @param  array  $input
     * @return void
     */
    public function update(Customer $customer, array $input)
    {
        Validator::make($input, [
            'current_password' => ['required', 'string'],
            'password' => $this->passwordRules(),
        ])->after(function ($validator) use ($customer, $input) {
            if (!isset($input['current_password']) || !Hash::check($input['current_password'], $customer->password)) {
                $validator->errors()->add('current_password', __('The provided password does not match your current password.'));
            }
        })->validateWithBag('updatePassword');

        $customer->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
