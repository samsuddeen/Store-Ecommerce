<?php

use App\Http\Middleware\middle;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Controllers\Customer\LoginController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ReturnController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\SignupController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\VoucherController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\Customer\CompletedController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Frontend\EsewaOrderController;
use App\Http\Controllers\Customer\AddressBookController;
use App\Http\Controllers\Customer\CancellationController;
use App\Http\Controllers\Customer\PaymentOptionController;
use App\Http\Controllers\Customer\RefundController;
use App\Http\Controllers\SocialController;


Route::post('/getwholesellercountrydata',[LoginController::class,'getwholesellercountrydata'])->name('getwholesellercountrydata');
Route::get('/login', [LoginController::class, 'login'])->name('Clogin');
Route::get('/customer-logout', [LoginController::class, 'logout'])->name('Clogout');
Route::post('/login', [LoginController::class, 'customerLogin'])->middleware('guest')->name('loginaccess');
Route::get('/sign-up', [SignupController::class, 'signup'])->name('signup');
Route::post('/sign-up', [SignupController::class, 'customerSignup'])->name('customerSignups');
Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware('Customer')->name('Cdashboard');
Route::get('/allcountrydata',[SignupController::class,'getAllCountryData'])->name('getallcountrydata');
// Route::get('/getAdminOrderdetails/{orderStatus}',[OrderController::class,'getAdminOrderdetails'])->name('getAdminOrderdetails');
// Route::get('/dashboards', [DashboardController::class, 'dashboards'])->name('dashboards')->middleware('role:customer');
Route::get('/getReason',[OrderController::class,'getReason'])->name('getReason');
Route::get('/getReject',[OrderController::class,'getReject'])->name('getReject');
Route::get('/profile', [ProfileController::class, 'profile'])->middleware('Customer')->name('Cprofile');
Route::post('/edit-customer-profile', [ProfileController::class, 'editProfile'])->middleware('Customer')->name('editCProfile');
Route::get('/customer-profile', [ProfileController::class, 'profile'])->middleware('Customer')->name('Cprofile');
Route::get('/order', [OrderController::class, 'order'])->middleware('Customer')->name('Corder');
Route::get('/get-order-product', [OrderController::class, 'allOrderProduct'])->middleware('customer')->name('allOrderProduct');
Route::get('/cancel-order/{id}', [OrderController::class, 'cancelOrder'])->middleware('Customer')->name('cancel.order');
Route::get('/delete-order-completed/{ref_id}', [OrderController::class, 'deleted'])->middleware('Customer')->name('deleteOrderCompleted');
// billing address
Route::get('/address-book', [AddressBookController::class, 'addressBook'])->middleware('Customer')->name('addressBook');
// shiping Address
Route::get('/shipping-address-book', [AddressBookController::class, 'shippingAddressBook'])->middleware('Customer')->name('shipping.address.book');
Route::get('/payment-option', [PaymentOptionController::class, 'paymentOption'])->middleware('Customer')->name('pamentOption');
Route::get('/voucher', [VoucherController::class, 'voucher'])->middleware('Customer')->name('voucher');
Route::get('/order-completed', [CompletedController::class, 'completed'])->middleware('Customer')->name('completed');
Route::get('/order-return', [ReturnController::class, 'return'])->middleware('Customer')->name('return');
Route::post('/return-product/{id}', [ReturnController::class, 'returnProduct'])->middleware('Customer')->name('return.product');
Route::get('/order-cancellation', [CancellationController::class, 'cancel'])->middleware('Customer')->name('cancel');
Route::get('/review', [ReviewController::class, 'review'])->middleware('Customer')->name('Creview');
Route::post('/save-review', [ReviewController::class, 'save'])->middleware('Customer')->name('save.review');
Route::get('/wishlist', [WishlistController::class, 'wishlist'])->middleware('Customer')->name('Cwishlist');
Route::get('/remove-wish-product/{id}', [WishlistController::class, 'removeWishlistProduct'])->middleware('Customer')->name('removeWishlistProduct');
Route::get('/delete-shipping-address/{id}', [AddressBookController::class, 'deleteShippingAddress'])->name('delete.shipping.address');
Route::get('/delete-billing-address/{id}', [AddressBookController::class, 'deleteBillingAddress'])->name('delete.billing.address');
Route::post('/store-esewa-orders', [EsewaOrderController::class, 'sessionStore'])->name('store-esewa-orders');
Route::post('/refund-request/{id}', [RefundController::class, 'refundRequest'])->name('refund.request');
Route::post('/refund-request-direct/{id}', [RefundController::class, 'refundDirectRequest'])->name('refund.direct.request');
Route::get('/getRejectedReason',[ReviewController::class,'getRejectedReason'])->name('getRejectedReason');
Route::get('/order-product-details/{id}', [OrderController::class, 'orderProduct'])->name('order.productDetails');
Route::get('/getreturnrejected',[ReturnController::class,'getreturnrejected'])->name('getreturnrejected');
// Route::get('/Customer-Order-again/{id}', [OrderController::class, 'orderAgain'])->middleware('customer')->name('order-again.of.cancelled');
Route::get('review-latest-order',[DashboardController::class,'reviewLatestOrder'])->name('review_latest_order');
Route::post('delivery-feedback',[DashboardController::class, 'deliveryFeedback'])->name('post_delivery_feedback');
Route::fallback(function () {
    return abort(404);
});
