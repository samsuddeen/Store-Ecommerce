<?php

use Jenssegers\Agent\Agent;
use App\Models\New_Customer;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\HblController;
use Illuminate\Support\Facades\Artisan;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\Admin\OrderController;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Http\Controllers\SearchKeywordController;
use App\Http\Controllers\Customer\LoginController;
use App\Http\Controllers\Customer\SignupController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\Cart\CartController;
use App\Http\Controllers\Support\AllLocationController;
use App\Http\Controllers\Api\CategoryAttributeController;
use App\Http\Controllers\Seller\ForgetPasswordController;
use App\Http\Controllers\Customer\ResetPasswordController;
use App\Http\Controllers\ProductAttributeDetailController;
use App\Http\Controllers\Customer\ForgotPasswordController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Customer\Auth\CustomerAuthForgotPasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/category-attribute-new', [CategoryAttributeController::class, 'attributeNew'])->name('update-new-cat');
Route::get('/category-attribute-new/data-value', [CategoryAttributeController::class, 'getLatestAttributeData'])->name('getLatestAttributeData');


Route::get('/sumit', function () {
    // Artisan::call('key:generate');
    // ------------------------Seed----------------------
    // Artisan::call('db:seed', [
    //     '--class' => 'YourSeederClass', // Replace with the actual seeder class name
    //     '--force' => true, // Use --force to run the seeder in production
    // ]);

    // // Get the output of the command
    // $output = Artisan::output();

    // // You can do something with the output if needed
    // return response()->json(['message' => $output]);
    // ----------------------Migrate--------------------------
    // defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));

    // $migrationPath = 'database/migrations/2023_03_23_132307_create_like_reviews_table.php';
    

    // // Run the migrate:refresh command
    // Artisan::call('migrate:refresh', [
    //     '--path'  => $migrationPath,
    //     '--force' => true,
    // ]);
    // // Get the command output
    // $output = Artisan::output();

    // return response()->json(['success' => true, 'message' => $output]);
    // // Artisan::call('storage:link');
    // //         return 'Storage link created successfully.';
    // // return view('welcome');
})->name('index');

Route::get('/admin/getAdminOrderdetails/{orderStatus}',[OrderController::class,'getAdminOrderdetails'])->name('getAdminOrderdetails');
Route::group(['prefix' => 'products'], function () {
});

Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth:seller,web']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});


Route::get('/login', [LoginController::class, 'login'])->name('Clogin');
Route::post('/login', [LoginController::class, 'customerLogin'])->middleware('guest')->name('loginaccess');

Route::get('/sign-up', [SignupController::class, 'signup'])->name('signup');
// customer
Route::post('/sign-up', [SignupController::class, 'customerSignup'])->name('customerSignups');
Route::get('customer/verify', [SignupController::class, 'customerVerify'])->name('customer.verify');
Route::get('/otp', [SignupController::class, 'showOTPForm'])->name('customers.otp');
Route::get('/sellers-otp', [SignupController::class, 'showOTPFormSeller'])->name('sellers.otp');
Route::post('/verification', [SignupController::class, 'verification'])->name('customer.verification');
Route::post('/seller-verification', [SignupController::class, 'sellerVerification'])->name('seller.verification');

Route::get('/customer/forget-passwords', [ForgotPasswordController::class, 'getEmail'])->name('customer.getpass');
Route::post('/customer/forget-passwords', [ForgotPasswordController::class, 'postEmail'])->name('customer.password.email');
Route::get('/customer/reset-password/{token}', [ResetPasswordController::class, 'getPassword'])->name('customer.password.reset');
Route::patch('/customer/reset-password/{token}', [ResetPasswordController::class, 'updatePassword'])->name('customer.password.updates');

Route::get('/seller/forget-password', [ForgetPasswordController::class, 'sellerFormPassword'])->name('sellerPassword.forget');
Route::post('/seller/forget-password', [ForgetPasswordController::class, 'selllerPasswordMail'])->name('seller.password.email');
Route::get('seller/reset-password/{token}', [ForgetPasswordController::class, 'resetPasswordForm'])->name('seller.password.reset');
Route::post('/seller/reset-password/{token}', [ForgetPasswordController::class, 'changePassword'])->name('seller.password.update');

Route::get('/product/attribute', [ProductAttributeDetailController::class, 'index'])->name('product.attribute.index');
// ROUTE FOR LOCATION
Route::get('get-districts', [AllLocationController::class, 'getDistrict'])->name('get-district');

// -----------------Social Login Routes-----------------

Route::get('/auth/google/login', function () {
    return Socialite::driver('google')->redirect();
})->name('google');
Route::get('/auth/google/callback',[LoginController::class,'loginWithGoogle']);

Route::get('/auth/facebook/login', function () {
    return Socialite::driver('facebook')->redirect();
})->name('facebook');

Route::get('/auth/facebook/callback/',[LoginController::class,'loginWithFacebook']);

Route::get('/facebook/verify-user-email',[LoginController::class,'verifyFacebookSessionData'])->name('facebook.verifyUser');
Route::post('/facebook/verify/post',[LoginController::class,'getFacebookSessionData'])->name('facebook.verifyUser.post');

Route::get('/auth/github/login', function () {
    return Socialite::driver('github')->redirect();
})->name('github');
Route::get('/auth/github/callback',[LoginController::class,'loginWithGithub']);

// -----------------/Social Login Routes-----------------


// for over all notification
Route::get('/show-notification/{notification}', [NotificationController::class, 'show'])->name('notification.show');
Route::get('/show-task-notification/{notification}', [NotificationController::class, 'showTask'])->name('task-notification.show');



Route::get('/payment', function () {
    return view('payment');
});




Route::post('/payment', [HblController::class, 'store'])->name('payment.store');
Route::post('/payment/guest', [HblController::class, 'storeGuest'])->name('paymentguest.store');

Route::post('/payment/direct', [HblController::class, 'storeDirect'])->name('payment.storeDirect');





Route::get('/payment/success', [PayPalController::class, 'hblSuccess']);
Route::get('/payment/success/guest', [PayPalController::class, 'hblSuccessGuest']);
Route::get('/payment/success/direct/guest', [PayPalController::class, 'hblSuccessGuestDirect']);


Route::get('/payment/failure/', function(Request $request){
    $request->session()->forget('hblsessiondata');
    $request->session()->flash('error','Something Went Wrong !!');
    return redirect()->route('index');
});



Route::get('/payment/cancel', function(Request $request){
    $request->session()->forget('hblsessiondata');
    $request->session()->flash('error','Something Went Wrong !!');
    return redirect()->route('index');
});


Route::get('/payment/backend', function(Request $request){
    $request->session()->forget('hblsessiondata');
    $request->session()->flash('error','Something Went Wrong !!');
    return redirect()->route('index');
});