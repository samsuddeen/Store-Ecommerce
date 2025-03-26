<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/



Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('to-customer.{id}', function ($customer, $id) {
    return true;
});

Broadcast::channel('to-seller.{id}', function ($seller, $id) {
    return true;
});


Broadcast::channel('to-backend', function () {
    return true;
});


Broadcast::channel('push-notification-to-customer.{id}', function () {
    return true;
});


Broadcast::channel('push-notification-to-customer.0', function () {
    return true;
});
