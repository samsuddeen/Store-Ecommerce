<?php

namespace App\Providers;

use App\Events\LogEvent;
use App\Events\NewsLetterEvent;
use App\Events\OrderPlacedEvent;
use App\Events\PaymentEvent;
use App\Events\PushNotificationEvent;
use App\Listeners\LogEventListener;
use App\Listeners\NewsLetterEventListener;
use App\Listeners\OrderPlacedListener;
use App\Listeners\PaymentEventListener;
use App\Listeners\PushNotificationEventListener;
use App\Models\Product;
use App\Models\Task\Task;
use App\Observers\ProductObserver;
use App\Observers\TaskObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        NotificationCreated::class => [
            SendNotificationToModel::class,
        ],



        PaymentEvent::class => [
            PaymentEventListener::class,
        ],



        //  for push notification
        PushNotificationEvent::class => [
            PushNotificationEventListener::class,
        ],

        // for news letter 
        NewsLetterEvent::class => [
            NewsLetterEventListener::class,
        ],

        OrderPlacedEvent::class=>[
            OrderPlacedListener::class
        ],

        'App\Models\Task' => [
            'App\Observers\TaskObserver',
        ],






        // for log
        LogEvent::class => [
            LogEventListener::class,
        ],



    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Product::observe(ProductObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
