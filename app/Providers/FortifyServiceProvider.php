<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\LoginUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\LogActivity;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::loginView(function () {
            return view('auth.login');
        });


        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forget-password');
        });

        Fortify::resetPasswordView(function () {
            return view('auth.reset-password');
        });


        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            LogActivity::addActivity('login attempt in  ' . $request->email);
            return Limit::perMinute(5)->by($email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();
            if($user){

                if (!$user->status) {
                    throw ValidationException::withMessages(['email' => 'The user has been banned']);
                }

            }




            if (
                $user &&
                \Illuminate\Support\Facades\Hash::check($request->password, $user->password)
            ) {
                \App\Models\LogActivity::addActivity('userLoggedIn', $user->id);
                return $user;
            }
        });
    }
}
