<?php

namespace App\Actions\Fortify;

use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\LoginResponse;

class LoginUser implements LoginResponse
{
    public function toResponse($request)
    {
        $user = User::where('email', $request->email)->first();

        if (
            $user &&
            Hash::check($request->password, $user->password)
        ) {
            LogActivity::addActivity('User Logged In');

        }
    }
}
