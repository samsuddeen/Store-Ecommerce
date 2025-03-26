<?php

namespace App\Providers;

use App\Models\Tag;
use App\Models\Cart;
use App\Models\Menu;
use App\Models\User;
use App\Models\Coupon;
use App\Models\footer;
use App\Models\header;
use App\Models\Seller;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\Category;
use App\Models\CartAssets;
use App\Models\footertitle;
use App\Models\Setting\Seo;
use App\Enum\Menu\MenuShowOn;
use App\Models\Advertisement;
use App\Helpers\BackendHelper;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Models\Setting\SocialSetting;
use App\Models\Setting\ContactSetting;
use Illuminate\Support\ServiceProvider;
use App\Models\Notification\Notification;
use App\Models\Admin\TopCategory\TopCategory;
use App\Models\City;
use App\Models\Local;
use App\Models\Notification\TaskNotification;
use App\Models\OrderTimeSetting;
use Illuminate\Support\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $setting_data = [];
        View::composer(['frontend*'], function ($view) {
            if (auth()->guard('customer')->user()) {
                foreach (Setting::get() as $data) {
                    $setting_data[$data->key] = $data->value;
                }
                $view->with('user', User::all())
                    ->with('meta_setting', $setting_data)
                    ->with('seo', Seo::first())
                    ->with('settings', Setting::get())
                    ->with('advertisements', Advertisement::get())
                    ->with('categories', Category::where('parent_id', '=', NULL)->where('status',1)->withCount(['ancestors', 'descendants'])->take(16)->with('children')->where('status', '1')->where('showOnHome', '1')->get())
                    ->with('total_quantity_on_cart', Cart::where('user_id', auth()->guard('customer')->user()->id)->value('total_qty'))
                    ->with('cart', Cart::where('user_id', auth()->guard('customer')->user()->id)->first())
                    ->with('footers', footer::orderBy('position')->get())
                    ->with('header', header::all()->take(3))
                    ->with('datas', footertitle::all()->take(5))
                    ->with('coupon', Coupon::where('publishStatus',1)->first())
                    ->with('menus', Menu::where('status',1)->get()->toTree())
                    ->with('footer_menus', Menu::where('status',1)->where('show_on', MenuShowOn::FOOTER)->orWhere('show_on', MenuShowOn::TOP_AND_FOOTER)->where('show_on', MenuShowOn::MAIN_AND_FOOTER)->where('show_on', MenuShowOn::ALL)->orderBy('position')->get())
                    ->with('tags', Tag::where('publishStatus', true)->orderBy('order', 'asc')->where('publishStatus', 1)->get())
                    ->with('top_category', TopCategory::where('status', 1)->limit(6)->orderBy('id', 'DESC')->get())
                    ->with('returnPolicy',Menu::where('slug','return-policy')->first() ?? null);
            } else {
                foreach (Setting::get() as $data) {
                    $setting_data[$data->key] = $data->value;
                }
                $view->with('user', User::all())
                    ->with('seo', Seo::first())
                    ->with('meta_setting', $setting_data)
                    ->with('settings', Setting::get())
                    ->with('advertisements', Advertisement::get())
                    ->with('total_quantity_on_cart', 0)
                    ->with('categories', Category::where('parent_id', '=', NULL)->where('status',1)->withCount(['ancestors', 'descendants'])->take(16)->with('children')->where('showOnHome', '1')->get())
                    ->with('cart', Cart::with('cartAssets')->where('user_id', 0)->first())
                    ->with('footers', footer::orderBy('position')->get())
                    ->with('header', header::all()->take(3))
                    ->with('datas', footertitle::all()->take(5))
                    ->with('coupon', Coupon::where('publishStatus',1)->first())
                    ->with('menus', Menu::where('parent_id', null)->where('status',1)->orderBy('position')->get())
                    ->with('footer_menus', Menu::where('status',1)->where('show_on', MenuShowOn::FOOTER)->orWhere('show_on', MenuShowOn::TOP_AND_FOOTER)->orWhere('show_on', MenuShowOn::MAIN_AND_FOOTER)->orWhere('show_on', MenuShowOn::ALL)->orderBy('position')->get())
                    ->with('tags', Tag::where('publishStatus', true)->orderBy('order', 'asc')->where('publishStatus', 1)->get())
                    ->with('top_category', TopCategory::where('status', 1)->limit(6)->orderBy('id', 'DESC')->get())
                    ->with('returnPolicy',Menu::where('slug','return-policy')->first() ?? null);
            }
        });
        
        View::composer(['admin.includes.topnav'], function ($view) {
            $admin_notifications = Notification::where('to_model', 'App\Models\User')->where('to_id', 1)->orderByDesc('created_at')->get();
            $notifications_count = Notification::where('to_model', 'App\Models\User')->where('to_id', 1)->where('is_read',0)->orderByDesc('created_at')->get();
            $view->with('admin_notifications', $admin_notifications)
            ->with('notifications_count',$notifications_count);
        });

        View::composer(['admin.includes.topnav'],function($view){
            $task_notifications = TaskNotification::where('notifiable_type','App\Models\User')->where('notifiable_id',Auth::user()->id)->latest()->get();
            $view->with('task_notifications',$task_notifications);
        });

        View::composer(['admin*'], function ($view) {
           $admin_seo=Seo::first();
          
            $view->with('admin_seo', $admin_seo);
        });

        View::composer(['components*'], function ($view) {
            $adminSettingSiteName=Setting::where('key','name')->first() ?? '';
            $adminSettingSiteLogo=Setting::where('key','logo')->first() ?? '';
             $view->with('adminSettingSiteName',$adminSettingSiteName)
             ->with('adminSettingSiteLogo',$adminSettingSiteLogo);
         });
        

        View::composer(['layouts.topnav'], function ($view) {
            if (auth()->guard('seller')->user() != null) {
                $seller_notifications = Notification::where('to_model', 'App\Models\Seller')->where('to_id', auth()->guard('seller')->user()->id)->get();
                $view->with('seller_notifications', $seller_notifications);
            }
        });

        View::composer(['frontend.layouts.app'], function ($view) {
            if (auth()->guard('customer')->user()) {
                $custmer_notifications = Notification::where('to_model', 'App\Models\New_Customer')->where('to_id', auth()->guard('customer')->user()->id)->where('is_read', false)->get();
                $view->with('customer_notifications', $custmer_notifications);
            }
        });

        View::composer(['frontend.traceOrder'], function ($view) {
            $setting = Setting::get();
            $setting_data = [
                'name' => collect($setting)->where('key', 'name')->value('value'),
                'email' => collect($setting)->where('key', 'email')->value('value'),
                'address' => collect($setting)->where('key', 'address')->value('value'),
                'phone' => collect($setting)->where('key', 'phone')->value('value'),
            ];
            $view->with('data', $setting_data);
        });;
    }
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        $system_login_logo = Setting::first();
        View::share('system_setting', $system_login_logo);
        $facIocn = Setting::where('key','favicon')->first();
        View::share('facIocn', $facIocn);
        $social_settings = SocialSetting::orderBy('created_at', 'ASC')->where('status', 1)->get();
        view::share('social_settings', $social_settings);
        $footer_contacts = ContactSetting::where('status',1)->get();
        view::share('footer_contacts', $footer_contacts);
        $cities = City::get();
        view::share('cities',$cities);
        $today = Carbon::now()->format('l');
        $order_timing_check = OrderTimeSetting::where('day', $today)->exists();
        if($order_timing_check)
        {
            $todays_order_time = OrderTimeSetting::where('day',$today)->first();
        }
        view::share('todays_order_time',$todays_order_time);

        Paginator::useBootstrap();
    }
}
