<?php

namespace App\Actions\Customer;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ResetCustomerPassword
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function reset(Customer $customer, array $input)
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $customer->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
