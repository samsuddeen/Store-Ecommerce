<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        // if (! $request->expectsJson()) {
        //     return route('login');
        // }

        if (! $request->expectsJson()) {
            if($request->is('admin/*')||$request->is('admin')){
                return route('login');
            // }else if($request->is('seller/*')||$request->is('seller')){
            //     return route('sellerLogin');
            }else if($request->is('customer/*')||$request->is('customer')){
                return route('Clogin');
            }
            return route('login');
        }
    }
}
