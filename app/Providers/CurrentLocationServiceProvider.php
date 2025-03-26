<?php

namespace App\Providers;

use App\Models\City;
use App\Models\Local;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class CurrentLocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        $ipAddress = $request->ip();
        $city = Session::get('city');
         if(!$city){
            $currentLocation = City::find(146);
            View::share('currentLocation',$currentLocation);
        }
        View::share('city',$city);
    }
}
