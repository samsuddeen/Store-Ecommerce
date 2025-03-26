<?php

use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\SellerReturnOrder;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Controllers\Seller\ReportController;
use App\Http\Controllers\SellerSettingController;
use App\Http\Controllers\Customer\LoginController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ReturnController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\SignupController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\VoucherController;
use App\Http\Controllers\Customer\WishlistController;
use App\Http\Controllers\SellerOrderCancelController;
use App\Http\Controllers\Customer\CompletedController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Seller\SalesReportController;
use App\Http\Controllers\Seller\SellerReviewController;
use App\Http\Controllers\Customer\AddressBookController;
use App\Http\Controllers\Seller\SellerCommentController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\Customer\CancellationController;
use App\Http\Controllers\Seller\ChangePasswordController;
use App\Http\Controllers\Seller\CustomerReportController;
use App\Http\Controllers\Customer\PaymentOptionController;
use App\Http\Controllers\Seller\Comment\CommentController;
use App\Http\Controllers\Seller\SellerUserSearchController;
use App\Http\Controllers\Seller\Media\SellerMediaController;
use App\Http\Controllers\Seller\Order\SellerOrderController;
use App\Http\Controllers\Seller\SellerProductEditController;
use App\Http\Controllers\Admin\Seller\SellerPayoutController;
use App\Http\Controllers\Seller\SellerProductReportController;
use App\Http\Controllers\Seller\Transaction\SellerTransactionController;

// Seller Login

// Route::get('/login', [LoginController::class, 'sellerLogin'])->name('sellerLogin')->middleware('guest:seller');
// Route::post('/login', [LoginController::class, 'sellerDashboardLogin'])->name('sellerLoginAccess');
Route::resource('profile', SellerProfileController::class)->middleware('auth:seller');
Route::patch('change-password', [ChangePasswordController::class, 'changePassword'])->name('profile.changePassword');
// Seller Registration
// Route::get('sign-up', [SignupController::class, 'sellerVerify'])->name('sellerVerify');
// Route::post('/seller-get-otp', [SignupController::class, 'getOtp'])->name('seller-get-otp');
// Route::get('sign-up', [SignupController::class, 'signupsellers'])->name('signupseller');
// Route::post('sign-up', [SignupController::class, 'sellerSignup'])->name('sellerSignup');
// Route::get('verify-otp', [SignupController::class, 'verifyToken'])->name('seller.otp.form');
// Seller Dashboard
Route::get('/sellerupdateModeColor',[DashboardController::class,'sellerupdateModeColor'])->name('sellerdarkmodedata');
Route::post('verify_phone_otp',[SignupController::class,'verifyOtpWithPhone'])->name('verify_phone_otp');
Route::get('seller-dashboard', [DashboardController::class, 'sellerDashboard'])->name('sellerDashboard')->middleware('auth:seller');
Route::get('seller-order-details/{orderStatus}',[SellerProductController::class,'getSellerDetailsData'])->name('getSellerOrderdetails');
// Seller Product
Route::get('seller-product-status/{id}/{status}',[SellerProductController::class,'updatePublishStatus'])->name('seller-product-status');
Route::get('product', [SellerProductController::class, 'index'])->name('seller-product.index')->middleware('auth:seller');
Route::get('product/create', [SellerProductController::class, 'create'])->name('seller-product.create')->middleware('auth:seller');
Route::get('product/{product}', [SellerProductController::class, 'show'])->name('seller-product.show')->middleware('auth:seller');
Route::get('product/{product}/edit', [SellerProductController::class, 'edit'])->name('seller-product.edit')->middleware('auth:seller');
Route::post('product', [SellerProductController::class, 'store'])->name('seller-product.store')->middleware('auth:seller');
Route::delete('product/{product}', [SellerProductController::class, 'destroy'])->name('seller-product.destroy')->middleware('auth:seller');

Route::patch('product-status', [SellerProductController::class, 'updateStatusSecond'])->name('seller-product.status')->middleware('auth:seller');


Route::get('review', [SellerReviewController::class, 'reviewList'])->name('seller-review.view')->middleware('auth:seller');
Route::post('/seller-send-review', [SellerReviewController::class, 'sendReview'])->name('seller-send.review')->middleware('auth:seller');

// Seller Product edit

Route::controller(SellerProductEditController::class)->prefix('seller-product-edit')->group(function () {
    Route::patch('/{id}/basicedit', 'basicEdit')->name('seller-products-edit.basicEdit');
    Route::patch('/{id}/priceandstock', 'priceAndStock')->name('seller-product-edit.priceAndStock');
    Route::patch('/{id}/description', 'description')->name('seller-product-edit.description');
    Route::patch('/{id}/serviceAndDelivery', 'serviceAndDelivery')->name('seller-product-edit.serviceAndDelivery');
    Route::patch('/{id}/productAttribute', 'productAttribute')->name('seller-product-edit.productAttribute');
    Route::patch('/{id}/seo-update', 'seoUpdated')->name('seller-product-edit.seo');
    Route::post('/remove-color-image', 'removeImage')->name('seller-remove-color-image');
    Route::patch('/{id}/update-category', 'updateCategory')->name('seller-product.update-category');
});

Route::get('viewReturnOrder/{orderId}/{returnId}',[SellerReturnOrder::class,'viewReturnOrder'])->name('view-return-order')->middleware('auth:seller');
Route::get('seller-return-order',[SellerReturnOrder::class,'getSellerReturnOrder'])->name('seller.return-order')->middleware('auth:seller');
Route::post('/seller-order-cancel',[SellerOrderCancelController::class,'cancelOrder'])->name('seller-order-cancel.action');
Route::get('/seller-comment',[CommentController::class,'getComment'])->name('seller.comment')->middleware('auth:seller');
Route::post('/seller-send-comment',[CommentController::class,'sendComment'])->name('seller-send.comment');
Route::post('/seller-send-comment-update',[CommentController::class,'sendUpdateComment'])->name('seller-send.updatecomment');
// Seller Order
Route::get('/sellergetReason',[SellerOrderCancelController::class,'getSellerReason'])->name('sellergetReason');
Route::get('order', [SellerOrderController::class, 'index'])->name('seller-order-index')->middleware('auth:seller');
Route::get('order/create', [SellerOrderController::class, 'create'])->name('seller-order-create');
Route::post('order', [SellerOrderController::class, 'store'])->name('seller-order-post')->middleware('auth:seller');
Route::get('seller-order/{sellerOrder}', [SellerOrderController::class, 'show'])->name('seller-order.show')->middleware('auth:seller');
Route::patch('seller-order-status-action', [SellerOrderController::class, 'statusAction'])->name('seller-order-status.status')->middleware('auth:seller');
Route::get('generateBarcode',[SellerOrderController::class,'generatebarcodeBill'])->name('generateBarcode');
// for seller transaction
Route::get('seller-transaction', [SellerTransactionController::class, 'index'])->name('seller-transaction.index')->middleware('auth:seller');
Route::patch('seller-transaction-status-action', [SellerTransactionController::class, 'statusAction'])->name('seller-transaction-status.status')->middleware('auth:seller');

// for seller media manager
Route::get('seller-media', [SellerMediaController::class, 'index'])->name('seller-media.index')->middleware('auth:seller');

Route::get('seller-payout', [SellerPayoutController::class, 'index'])->name('seller-payouts');
Route::Patch('seller-payout-status', [SellerPayoutController::class, 'statusAction'])->name('seller-payout-action-status');
Route::resource('comments', SellerCommentController::class)->middleware('auth:seller')->except('destroy');
Route::get('delete-comments{id}', [SellerCommentController::class, 'delete'])->name('comments.delete');
Route::get('seller-comments', [SellerCommentController::class, 'index'])->name('seller-comments');
Route::patch('edit-comment-answer/{id}', [SellerCommentController::class, 'updateAnswer'])->name('edit.comment.answer');
Route::resource('seller-setting', SellerSettingController::class)->except('destroy')->middleware('auth:seller');
Route::get('review', [SellerReviewController::class, 'reviewList'])->name('seller-review.view')->middleware('auth:seller');
Route::post('/seller-send-review', [SellerReviewController::class, 'sendReview'])->name('seller-send.review')->middleware('auth:seller');
// Route::get('/seller/sa')->name('seller.sales-report.index');
Route::get('/sales-report', [SalesReportController::class, 'index'])->name('sales-report')->middleware('auth:seller');
Route::get('/seller-report-pdf', [SalesReportController::class, 'download'])->name('sellersalesreportexcel');
Route::get('/customer-report', [CustomerReportController::class, 'index'])->name('customer.report')->middleware('auth:seller');
Route::get('/customer-report-pdf', [CustomerReportController::class, 'download'])->name('seller.customerreportexcel');
Route::get('/seller-product-report', [SellerProductReportController::class, 'index'])->name('seller.productreport')->middleware('auth:seller');
Route::get('/seller-product-report-pdf', [SellerProductReportController::class, 'download'])->name('sellerproductreportexcel');
Route::get('/seller-usersearch-report', [SellerUserSearchController::class, 'index'])->name('seller.user-searchreport')->middleware('auth:seller');
Route::get('/seller-usersearch-report-pdf', [SellerUserSearchController::class, 'download'])->name('sellerusersearchreportexcel');