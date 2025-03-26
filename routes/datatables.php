<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VatTaxController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\OurAreaController;
use App\Http\Controllers\Datatables\HubController;
use App\Http\Controllers\Datatables\LogController;
use App\Http\Controllers\Datatables\SMSController;
use App\Http\Controllers\Datatables\TagController;
use App\Http\Controllers\Datatables\MenuController;
use App\Http\Controllers\Datatables\SMTPController;
use App\Http\Controllers\Datatables\TaskController;
use App\Http\Controllers\Datatables\UserController;
use App\Http\Controllers\Datatables\BrandController;
use App\Http\Controllers\Datatables\ColorController;
use App\Http\Controllers\Datatables\OrderController;
use App\Http\Controllers\Datatables\QuoteController;
use App\Http\Controllers\Datatables\TrashController;
use App\Http\Controllers\Datatables\BackupController;
use App\Http\Controllers\Datatables\BannerController;
use App\Http\Controllers\Datatables\CouponController;
use App\Http\Controllers\Datatables\RefundController;
use App\Http\Controllers\datatables\ReturnController;
use App\Http\Controllers\Datatables\ReviewController;
use App\Http\Controllers\Datatables\SellerController;
use App\Http\Controllers\Datatables\CountryController;
use App\Http\Controllers\Datatables\ProductController;
use App\Http\Controllers\Datatables\CategoryController;
use App\Http\Controllers\Datatables\CustomerController;
// use App\Http\Controllers\Datatables\LocationController;
use App\Http\Controllers\Datatables\LocationController;
use App\Http\Controllers\Datatables\PositionController;
use App\Http\Controllers\Datatables\TopOfferController;
use App\Http\Controllers\Datatables\RetailerOfferController;
use App\Http\Controllers\Datatables\NewsLetterController;
use App\Http\Controllers\Datatables\ReturnAbleController;
use App\Http\Controllers\Datatables\SubscriberController;
use App\Http\Controllers\Datatables\TransactionController;
use App\Http\Controllers\Datatables\BackupPeriodController;
use App\Http\Controllers\Datatables\AdvertisementController;
use App\Http\Controllers\Datatables\DeliveryRouteController;
use App\Http\Controllers\Datatables\PaymentMethodController;
use App\Http\Controllers\Datatables\PayoutSettingController;
use App\Http\Controllers\Datatables\SocialSettingController;
use App\Http\Controllers\Datatables\ContactSettingController;
use App\Http\Controllers\Datatables\CustomerCouponController;
use App\Http\Controllers\Datatables\DeliveryChargeController;
use App\Http\Controllers\Datatables\PaymentHistoryController;
use App\Http\Controllers\Datatables\FeaturedSectionController;
use App\Http\Controllers\Datatables\PushNotificationController;
use App\Http\Controllers\Datatables\SellerTransactionController;

Route::get('users', [UserController::class, 'index'])->name('users');
Route::get('categories', [CategoryController::class, 'index'])->name('users');
Route::get('brands', [BrandController::class, 'index'])->name('brands');
Route::get('products', [ProductController::class, 'index'])->name('products');
Route::get('advertisements', [AdvertisementController::class, 'index'])->name('advertisements');
Route::get('positions', [PositionController::class, 'index'])->name('positions');
Route::get('colors', [ColorController::class, 'index'])->name('colors');
Route::get('customers', [CustomerController::class, 'index'])->name('customers');
Route::get('locations', [LocationController::class, 'index'])->name('location');
Route::get('hubs', [HubController::class, 'index'])->name('hub');
Route::get('country_data', [CountryController::class, 'index'])->name('country_data');

Route::get('task',[TaskController::class, 'index'])->name('tasks');

Route::get('delivery-routes', [DeliveryRouteController::class, 'index'])->name('delivery-routes');
Route::get('delivery-charges', [DeliveryChargeController::class, 'index'])->name('delivery-charges');
Route::get('banners', [BannerController::class, 'index'])->name('banner');
// Route::get('pages', [PageController::class, 'index'])->name('pages');
Route::get('tags', [TagController::class, 'index'])->name('tags');
Route::get('subscribers', [SubscriberController::class, 'index'])->name('subscribers');
Route::get('quotes', [QuoteController::class, 'index'])->name('quotes');
Route::get('coupons', [CouponController::class, 'index'])->name('coupons');
Route::get('customer-coupon', [CustomerCouponController::class, 'index'])->name('customer-coupon');

Route::get('orders', [OrderController::class, 'index'])->name('a.orders');
Route::get('inhouse-orders', [OrderController::class, 'getInhouseOrder'])->name('inhouse.orders');
Route::get('seller-list-order',[OrderController::class, 'sellerListOrder'])->name('seller-list-order');
Route::get('seller-order-view',[OrderController::class, 'sellerOrderView'])->name('seller-order-view');
Route::get('sellerorderview/{id}',[OrderController::class, 'sellerOrderViewList'])->name('sellerorderview');
Route::get('transactions', [TransactionController::class, 'index']);
Route::get('reviews', [ReviewController::class, 'index'])->name('reviews');
Route::get('/alldelivery',[OrderController::class, 'allDelivery'])->name('alldelivery');

Route::get('return-order', [ReturnAbleController::class, 'index'])->name('return-order');
Route::get('seller-return-order', [ReturnAbleController::class, 'sellerReturnOrder'])->name('seller-return-order');
Route::get('refunds', [RefundController::class, 'index'])->name('return-order');
Route::get('refunds-paid', [RefundController::class, 'refundPaid'])->name('return-order');

// Route::get('returns', [ReturnController::class, 'index'])->name('returns');
Route::get('allcomment',[CommentController::class,'getAllComment'])->name('allcomment');
Route::get('apply-approve', [OrderController::class, 'apply'])->name('apply.name');
Route::get('update_status/{id}', [ProductController::class, 'updateStatus'])->name('updateStatus');


// FOR FEATURED SECTION
Route::get('featured-section', [FeaturedSectionController::class, 'index'])->name('featured-section');
Route::get('top-offer', [TopOfferController::class, 'index'])->name('top-offer');
Route::get('retailer-top-offer', [RetailerOfferController::class, 'index'])->name('top-offer');
Route::get('sellers', [SellerController::class, 'index'])->name('top-offer');



// for trash
Route::get('trashes', [TrashController::class, 'index'])->name('trashes');



// for smtp setting 
Route::get('smtp', [SMTPController::class, 'index'])->name('smtps');
Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods');
Route::get('payout-setting', [PayoutSettingController::class, 'index'])->name('payout-setting');
Route::get('social-setting', [SocialSettingController::class, 'index'])->name('social-setting');
Route::get('contact-setting', [ContactSettingController::class, 'index'])->name('contact-setting');

// for backup period
Route::get('backup-period', [BackupPeriodController::class, 'index'])->name('backup-period');







// for seller transaction
Route::get('seller-transactions', [SellerTransactionController::class, 'index'])->name('seller-trasnactions')->middleware('auth:seller');




// for payment histories
Route::get('payment-histories', [PaymentHistoryController::class, 'index'])->name('seller-trasnactions');


// for the push notificaiton
Route::get('push-notifications', [PushNotificationController::class, 'index'])->name('push-notification');


// for news letter
Route::get('news-letters', [NewsLetterController::class, 'index'])->name('news-letter');




// for sms marketting
Route::get('sms', [SMSController::class, 'index'])->name('sms');


// rote for the logs
Route::get('logs', [LogController::class, 'index'])->name('logs');



// route for backup
Route::get('backup', [BackupController::class, 'index'])->name('backups');






// route for menus
Route::get('menus', [MenuController::class, 'index'])->name('menus');


