<?php

namespace App\Handlers;

use UniSharp\LaravelFilemanager\Handlers\ConfigHandler;

class ConfigUsers extends ConfigHandler
{
    public function userField()
    {
        if (auth()->user()->hasRole('super admin')) {
            return '';
        } elseif (auth()->guard('seller')->check()) {
            return  'seller-'.auth()->guard('seller')->user()->id;
        }else{
            'user-'.auth()->id();
        }
    }
}



