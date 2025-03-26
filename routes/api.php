<?php

use App\Mail\OrderConfirm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GraphController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RefundController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\WishListController;
use App\Http\Controllers\Api\JustForYouController;
use App\Http\Controllers\Api\OrderTrackController;
use App\Http\Controllers\Api\CancelOrderController;
use App\Http\Controllers\Api\ProductFormController;
use App\Http\Controllers\Api\ReturnOrderController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\DeliveryStaffController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Api\DeliveryChargeController;
use App\Http\Controllers\Api\Offer\TopOfferController;
use App\Http\Controllers\Api\QuestionAnswerController;
use App\Http\Controllers\Api\CategoryAttributeController;
use App\Http\Controllers\Api\UserBillingAddressController;
use App\Http\Controllers\Api\UserShippingAddressController;
use App\Http\Controllers\Api\RecommendationProductController;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/register_country',[HomeController::class,'getRegisterCountryList']);
Route::get('today-order-timing',[HomeController::class,'todaysOrder'])->name('todays_order');
Route::get('/customercare',[ProductFormController::class,'getCustomerCare']);
Route::post('/categories', [ProductFormController::class, 'categories'])->name('categories');
Route::post('/categories/ancestors', [ProductFormController::class, 'getAllAncestors'])->name('categories.ancestors');
Route::get('/brands', [ProductFormController::class, 'brands'])->name('brands');
Route::get('/countries', [ProductFormController::class, 'countries'])->name('countries');
Route::get('/category-attribute', [CategoryAttributeController::class, 'attribute']);
Route::patch('/category-attribute/{categoryAttribute}', [CategoryAttributeController::class, 'deleteAttribute']);
Route::post('search-category', [CategoryController::class, 'category']);
Route::post('children-category/{id}', [CategoryController::class, 'childrenCategory']);
Route::get('/get-colors', [ProductFormController::class, 'getColors']);
Route::get('/get-attributes/{id}', [ProductFormController::class, 'getAttributes']);


Route::get('/selected-get-colors', [ProductFormController::class, 'getSelectedColors']);
// -----------------------------------Api-----------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::post('/updateLocation',[HomeController::class,'updateLocation'])->name('updateLocation');
Route::get('/product-by-city',[HomeController::class, 'getProductsByCity'])->name('getProductsByCity');

// -------------------------Authentication------------------------
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/verify-register-user', [AuthController::class, 'verifyRegisterUser'])->name('verify-register-user');
Route::get('/resendregisterotp', [AuthController::class, 'resendRegisterOtp'])->name('resendregisterotp');
Route::post('/system/login', [AuthController::class, 'apiLogin'])->middleware(['guest:' . config('fortify.guard')])->name('login.api');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/sendotp', [AuthController::class, 'generateOtp']);
Route::post('/getotp', [AuthController::class, 'getOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/updateProfile', [AuthController::class, 'updateProfile'])->name('updateProfile')->middleware('auth:sanctum');
Route::get('/user/{user_id}', [AuthController::class, 'getUser'])->name('get-user');
Route::get('/my-detail/{user_id}', [AuthController::class, 'myDetails'])->name('get-user');
Route::post('deleteProfile',[AuthController::class,'deleteProfile'])->name('delete-profile')->middleware('auth:sanctum');
// Route::get('/task',[TaskController::class, 'index'])->name('task.index');

// -------------------------/Authentication------------------------


// ---------------------------------------Social Login-----------------------------------------

Route::post('/social/login', [AuthController::class, 'socailLogin'])->name('social.login');

// ---------------------------------------/Social Login-----------------------------------------

Route::get('/recommendation/product', [RecommendationProductController::class, 'recommendation']);
Route::get('/latest/products', [RecommendationProductController::class, 'latestProducts']);
Route::get('/special/offer/product', [RecommendationProductController::class, 'specialOfferProduct']);
// ---------------------------------------Slider -----------------------------------------

Route::get('/slider', [SliderController::class, 'slider'])->name('slider');
Route::get('/slider/{id}', [SliderController::class, 'singleSlider'])->name('slider');
Route::get('/location', [LocationController::class, 'location']);

// ---------------------------------------/Slider-----------------------------------------



// -----------------------------------Category----------------------------------


Route::get('/category', [CategoryController::class, 'getCategory']);
Route::get('/child-category/{slug}', [CategoryController::class, 'chilCategory']);
Route::get('/diff-category', [CategoryController::class, 'diffCat']);


Route::get('/attributes', [HomeController::class, 'attribute'])->name('attribute');
Route::get('/attributes/brand/{id}', [HomeController::class, 'getAttributeBrandProduct'])->name('brand.attribute');
Route::get('/attributes/color/{id}', [HomeController::class, 'getAttributeColorProduct'])->name('color.attribute');

Route::get('/sort', [ProductController::class, 'sortProduct'])->name('sort.product');
// -----------------------------------/Category----------------------------------

// -----------------------------------Product----------------------------------


Route::get('/anishsir',[HomeController::class,'anishSir']);

Route::get('/products', [ProductController::class, 'products']);
Route::get('/product-detail/{slug}', [ProductController::class, 'productDetails']);
Route::post('/product-attribute', [ProductController::class, 'productAttribute']);
Route::get('/featured-product', [ProductController::class, 'featuredProduct'])->name('featured.product');
Route::get('/specific-featured-product/{id}', [ProductController::class, 'getSpecialFeaturedProduct'])->name('specialfeatured.product');

Route::get('/product-detail-test/{slug}',[ProductController::class, 'testProductDetail']);
// -----------------------------------/Product----------------------------------


// ------------------------------------Review------------------------------
Route::get('/review/{product_id}', [ReviewController::class, 'getReview'])->name('getReview');
Route::post('/add-review', [ReviewController::class, 'addReview'])->name('add-review')->middleware('auth:sanctum');
Route::get('/reviews/user', [ReviewController::class, 'userReview'])->name('user.review')->middleware('auth:sanctum');
Route::get('/track-order',[OrderTrackController::class,'getOrderTrackDetail']);

Route::post('/cancel-order',[CancelOrderController::class,'cancelOrder'])->middleware('auth:sanctum');
Route::get('cancel-reason',[CancelOrderController::class,'getReason']);
Route::get('/getReview',[ReviewController::class,'getAllReview'])->name('getReview')->middleware('auth:sanctum');

Route::post('/delivery-feedback',[ReviewController::class,'deliveryFeedback'])->middleware('auth:sanctum');

// ------------------------------------/Review------------------------------

Route::post('/return-order',[ReturnOrderController::class,'returnOrder'])->name('returnuser-order')->middleware('auth:sanctum');

// ------------------------------------Advertisement------------------------------

Route::get('/view-completed-order',[ReturnOrderController::class,'getCompletedOrder'])->middleware('auth:sanctum');
Route::get('/user-reward',[ReturnOrderController::class,'userReward'])->middleware('auth:sanctum');

Route::get('/ads', [AdvertisementController::class, 'ads'])->name('ads');
Route::get('/skip-ad',[AdvertisementController::class,'skipAd'])->name('skipAd');


// ------------------------------------/Advertisement------------------------------

// ------------------------------------Contact------------------------------

Route::post('/contact-us', [ContactController::class, 'contactUs'])->name('contact-us');
Route::get('/site-detail', [SettingController::class, 'siteDetails'])->name('site-detail');

// ------------------------------------/Contact------------------------------


// ------------------------------------Question And Answer------------------------------

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/question', [QuestionAnswerController::class, 'question'])->name('question');
    Route::get('/userquestionlist/{product_id}', [QuestionAnswerController::class, 'questionList'])->name('question.list');
});
Route::get('/questionlist/{product_id}', [QuestionAnswerController::class, 'questionList'])->name('question.list');

// ------------------------------------/Question And Answer------------------------------

// ------------------------------------Shipping Address------------------------------

Route::get('/area-charge/{id}', [UserShippingAddressController::class, 'getChargeRoute'])->name('shipping-address');
Route::get('/shipping-address', [UserShippingAddressController::class, 'shippingaddress'])->name('shipping-address');
Route::get('/user-shipping-address', [UserShippingAddressController::class, 'getUserShippingAddress'])->name('user-shipping-address')->middleware('auth:sanctum');
Route::post('/add-shipping-address', [UserShippingAddressController::class, 'addShippingAddress'])->name('add-shipping-address')->middleware('auth:sanctum');
Route::post('/update-shipping-address/{id}', [UserShippingAddressController::class, 'updateShippingAddress'])->middleware('auth:sanctum');
Route::get('/delete-shipping-address/{id}', [UserShippingAddressController::class, 'deleteShippingAddress'])->middleware('auth:sanctum');

Route::get('/user-billing-address', [UserBillingAddressController::class, 'getUserBillingAddress'])->name('user-billing-address')->middleware('auth:sanctum');
Route::post('/add-billing-address', [UserBillingAddressController::class, 'addBillingAddress'])->name('add-billing-address')->middleware('auth:sanctum');
Route::post('/update-billing-address/{id}', [UserBillingAddressController::class, 'updateBillingAddress'])->middleware('auth:sanctum');
Route::get('/delete-billing-address/{id}', [UserBillingAddressController::class, 'deleteBillingAddress'])->middleware('auth:sanctum');
// ------------------------------------/Shipping Address------------------------------

// ---------------------------------------Search------------------------------------------
Route::get('/search/{searchitem}', [ProductController::class, 'search'])->name('search');
// --------------------------------------/Search------------------------------------------


// ---------------------------------------Page------------------------------------------

Route::get('/page', [PageController::class, 'page'])->name('page');
Route::get('/detail-page/{slug}', [PageController::class, 'detailPage'])->name('detail-page');

// ---------------------------------------/Page------------------------------------------


// -----------------------------------Cart----------------------------------


Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('add-to-cart')->middleware('auth:sanctum');
Route::get('/cart-item', [CartController::class, 'cartItem'])->name('cart-item')->middleware('auth:sanctum');
Route::post('/update-from-cart', [CartController::class, 'updateFromCart'])->name('update-from-cart')->middleware('auth:sanctum');
Route::post('/remove-from-cart', [CartController::class, 'removeFromCart'])->name('remove-from-cart')->middleware('auth:sanctum');
Route::get('/clear-user-cart', [CartController::class, 'clearCart'])->name('clear.cart')->middleware('auth:sanctum');


// -----------------------------------/Cart----------------------------------


// -----------------------------------WishList----------------------------------


Route::post('/add-to-wishlist', [WishListController::class, 'addToWishList'])->name('add-to-widhlist')->middleware('auth:sanctum');
Route::get('/wishlist', [WishListController::class, 'wishlist'])->name('wishlist')->middleware('auth:sanctum');
Route::post('/remove-from-wishlist', [WishListController::class, 'removeFromWishList'])->name('remove-from-wishlist')->middleware('auth:sanctum');
Route::get('/clear-user-wishlist', [WishListController::class, 'clearWishlist'])->name('clear.wishlist')->middleware('auth:sanctum');

// Route::post('/add-review',)->middleware('auth:sanctum');
Route::get('/default-shipping-charge',[OrderController::class,'getDefaultShippingCharge']);

// -----------------------------------/WishList-------------------------

// ---------------------------------------Payment------------------------------------------
Route::get('/order-rejected-reason',[OrderController::class,'orderRejectedreason']);
Route::get('/user-order', [OrderController::class, 'userOrder'])->name('user.order')->middleware('auth:sanctum');

Route::get('user-detail-order/{orderId}',[OrderController::class,'getDetailsOrder']);
Route::post('/cash-payment',[OrderController::class,'cashPayment'])->name('cashPayment')->middleware('auth:sanctum');
Route::post('/fonepay-payment',[OrderController::class,'fonepayPayment'])->name('fonepayPayment')->middleware('auth:sanctum');

Route::post('/esewa-payment', [OrderController::class, 'esewaPayment'])->name('esewa')->middleware('auth:sanctum');

Route::post('/khalti-payment', [OrderController::class, 'khaltiPayment'])->name('khalti')->middleware('auth:sanctum');

// ---------------------------------------/Payment------------------------------------------


Route::get('/get-refid', [CartController::class, 'generaterefId'])->middleware('auth:sanctum');

Route::post('/coupon', [OrderController::class, 'getCoupon']);

Route::get('/download-mobile-pdf-file',[WishListController::class,'downloadMobilePdfFile'])->middleware('auth:sanctum');
Route::post('/verify_coupon', [CartController::class, 'verifyCoupon'])->name('verify.token')->middleware('auth:sanctum');

Route::post('/refund-direct-apply',[RefundController::class,'apiRefundApply'])->middleware('auth:sanctum');
// ---------------------------------------WishList To Cart----------------------------------------------------------

Route::get('/return-order-list',[ReturnOrderController::class,'getReturnOrderData'])->middleware('auth:sanctum');

Route::post('refund-apply',[RefundController::class,'refundApply'])->middleware('auth:sanctum');

Route::get('/wishlist-to-cart', [WishListController::class, 'addToCart'])->name('wishlist.cart')->middleware('auth:sanctum');

Route::get('delivery-charge', [DeliveryChargeController::class, 'deliverCharge'])->name('delivery.charge')->middleware('auth:sanctum');

// ---------------------------------------/WishList To Cart----------------------------------------------------------

// Delivery API Routes
Route::group(['as'=>'system.', 'prefix'=>'system'], function(){
    Route::get('my-tasks',[DeliveryStaffController::class,'getAssignedTasks'])->middleware('auth:sanctum');
    Route::post('update-task-status',[DeliveryStaffController::class, 'updateTaskStatus'])->middleware('auth:sanctum');
    Route::get('order-status-graph-data',[GraphController::class, 'getOrderStatusGraphData'])->middleware('auth:sanctum');
    Route::post('current_location',[DeliveryStaffController::class, 'fetchCurrentLocation'])->middleware('auth:sanctum');
    Route::get('get_user_by_location',[DeliveryStaffController::class, 'getUserByLocation']);
    Route::get('task-actions',[TaskController::class, 'getTaskActions'])->middleware('auth:sanctum');
    Route::get('users',[TaskController::class,'getUsers'])->middleware('auth:sanctum');
    Route::get('orders',[TaskController::class,'getOrders'])->middleware('auth:sanctum');
    Route::resource('/task',TaskController::class)->middleware('auth:sanctum');
    Route::get('get-status-priority',[TaskController::class, 'getStatusPriority']);
    Route::post('reassign-task',[TaskController::class,'reassignTask']);
});
Route::get('get-staff/{staff_id}',[AuthController::class, 'getStaff']);

// top offer 
Route::get('/top-offer', [TopOfferController::class, 'index']);
Route::get('/top-offer/{slug}', [TopOfferController::class, 'products']);
Route::get('/just-for-you', [JustForYouController::class, 'index']);
Route::get('/just-for-you-for-test', [JustForYouController::class, 'index1']);
Route::get('/just-for-you/{slug}', [JustForYouController::class, 'getProduct']);
Route::get('/khalti-response',[FrontendController::class,'getKhaltiResponse'])->name('khalti-response');
Route::get('/menu',[MenuController::class,'getMenuData']);
Route::get('/social-site',[MenuController::class,'getSocialSite']);
Route::get('test-notify',[MenuController::class,'testNotify']);


// -----------------------------------/Feedback-----------------------------------


Route::fallback(function () {
    $response = [
        'error' => true,
        'data' => null,
        'msg' => 'Invalid Route'

    ];
    return response()->json($response, 500);
});

// -----------------------------------/Api-----------------------------------


/* return order api */
// Route::get('return/product',[ProductController::class,'returnOrder']);

// Route::group(['prefix' => 'api', 'as' => 'api.'], function () {
//     // API Login Route
//     Route::post('login', [AuthenticatedSessionController::class, 'apiLogin'])
//         ->middleware(['guest:' . config('fortify.guard')])
//         ->name('login.api');
// });

Route::get('/foo', function () {
    Artisan::call('storage:link');
});