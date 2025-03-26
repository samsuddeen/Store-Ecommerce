<?php

use App\Models\RFQ;
use App\Models\Location;
use App\Models\AttributeCategory;
use Illuminate\Support\Facades\Route;
use App\Providers\NotificationCreated;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\VatTaxController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\CountreyController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HubController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\ReviewReplyController;
use App\Http\Controllers\RewardpointController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReturnController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ToggleController;
use App\Http\Controllers\BillDownlaodController;
use App\Http\Controllers\HubNearPlaceController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\Log\LogController;
use App\Http\Controllers\Admin\OurAreaController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Backup\BackupController;
use App\Http\Controllers\RewardSectionController;
use App\Http\Controllers\SearchKeywordController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EditAreaController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\DistrictUpdateController;
use App\Http\Controllers\Admin\SMTP\SMTPController;
use App\Http\Controllers\TopOfferProductController;
use App\Http\Controllers\Admin\LocalLevelController;
use App\Http\Controllers\Admin\MenuFilterController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\TaskActionController;
use App\Http\Controllers\CustomerCarePageController;
use App\Http\Controllers\Policy\AppPolicyController;
use App\Http\Controllers\Report\LogReportController;
use App\Http\Controllers\SellerCommissionController;
use App\Http\Controllers\Admin\Media\MediaController;
use App\Http\Controllers\Admin\ProductEditController;
use App\Http\Controllers\AttributeCategoryController;
use App\Http\Controllers\Admin\OrderSettingController;
use App\Http\Controllers\Admin\ProductStockController;
use App\Http\Controllers\CancellationReasonController;
use App\Http\Controllers\Report\OrderReportController;
use App\Http\Controllers\Report\SalesReportController;
use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\DeliveryStaffController;
use App\Http\Controllers\Admin\Marketing\SMSController;
use App\Http\Controllers\Admin\Refund\RefundController;
use App\Http\Controllers\Admin\Seller\SellerController;
use App\Http\Controllers\Backup\BackupPeriodController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\CategoryFilterController;
use App\Http\Controllers\Admin\Offer\TopOfferController;
use App\Http\Controllers\Admin\ProvinceUpdateController;
use App\Http\Controllers\Report\ProductReportController;
use App\Http\Controllers\RetailerOfferSectionController;
use App\Http\Controllers\Seller\SellerCommentController;
use App\Http\Controllers\EmailPhone\EmailPhoneController;
use App\Http\Controllers\Report\CustomerReportController;
use App\Http\Controllers\Seller\SellerDocumentController;
use App\Http\Controllers\FeaturedSectionProductController;
use App\Http\Controllers\SellerCancelOrderReasonController;
use App\Http\Controllers\Report\TransactionReportController;
use App\Http\Controllers\Seller\Order\SellerOrderController;
use App\Http\Controllers\Admin\Seller\SellerPayoutController;
use App\Http\Controllers\Admin\Category\TopCategoryController;
use App\Http\Controllers\Admin\Marketing\NewsLetterController;
use App\Http\Controllers\Admin\Message\MessageSetupController;
use App\Http\Controllers\Admin\Payment\PaymentMethodController;
use App\Http\Controllers\Admin\Setting\PayoutSettingController;
use App\Http\Controllers\Admin\Setting\SocialSettingController;
use App\Http\Controllers\RetailerOfferSectionProductController;
use App\Http\Controllers\Admin\Couppon\CustomerCouponController;
use App\Http\Controllers\Admin\Delivery\DeliveryRouteController;
use App\Http\Controllers\Admin\Payment\PaymentHistoryController;
use App\Http\Controllers\Admin\Setting\ContactSettingController;
use App\Http\Controllers\Admin\Delivery\DeliveryChargeController;
use App\Http\Controllers\Admin\Transaction\TransactionController;
use App\Http\Controllers\Admin\Returnable\AdminReturnAbleController;
use App\Http\Controllers\Admin\Notification\PushNotificationController;
use App\Http\Controllers\Admin\Product\Featured\FeaturedSectionController;

Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
Route::get('/', [AdminDashboardController::class, 'index']);
Route::get('/view-all-notifications', [PushNotificationController::class, 'viewNotifications'])->name('view.all-notifications');
Route::get('product/{product}/stock', [ProductStockController::class, 'index'])->name('stock.index');
Route::post('product/{product}/stock', [ProductStockController::class, 'store'])->name('stock.store');
Route::put('/update-password', [UserController::class, 'updatePassword'])->name('admin.updatePassword');
Route::get('/restore-product/{id}',[ProductController::class,'restore'])->name('seller-product.restore');
Route::get('/updateModeColor',[AdminDashboardController::class,'updateModeColor'])->name('changeColorMode');
Route::resources([
    'user' => UserController::class,
    'customer' => CustomerController::class,
    'color' => ColorController::class,
    'category' => CategoryController::class,
    'product' => ProductController::class,
    'brand' => BrandController::class,
    'role' => RoleController::class,
    'position' => PositionController::class,
    'advertisement' => AdvertisementController::class,
    'location' => LocationController::class,
    'delivery-route' => DeliveryRouteController::class,
    'delivery-charge' => DeliveryChargeController::class,
    'banner' => BannerController::class,
    'tag' => TagController::class,
    'coupon' => CouponController::class,
    'currency' => CurrencyController::class,
    'seller' => SellerController::class,
    'seller-document' => SellerDocumentController::class,
    'sellercommission'=>SellerCommissionController::class,
    'rewardsection'=>RewardpointController::class,
    'inquiry' => InquiryController::class,
    'countries'=>CountreyController::class
]);
Route::get('import-product/product', [ProductController::class, 'showImportForm'])->name('product.get-import');
Route::post('import-product/product', [ProductController::class, 'import'])->name('product.import');
Route::get('/admin/customer-order/{id}',[OrderController::class,'getCustomerOrder'])->name('admin.customerallorder');
Route::get('/reward-setup',[RewardpointController::class,'rewardsetupForm'])->name('rewardsetup');
Route::patch('/reward-setup/{id?}',[RewardpointController::class,'updateRewardsetupForm'])->name('rewardsetup');
Route::post('/add-brand-direct',[BrandController::class,'directBrandAdd'])->name('add-brand-direct');
Route::post('/add-tag-direct',[TagController::class,'directTagAdd'])->name('add-tag-direct');
Route::get('category/show/{id}',[CategoryController::class,'show'])->name('category.showcat');
Route::get('/replyreview',[ReviewReplyController::class,'getAllReview'])->name('replyreview.index');
Route::get('/delivery-feedback',[ReviewReplyController::class,'getAllDeliveryFeedbacks'])->name('delivery-feedback.index');
Route::post('/replyreview',[ReviewReplyController::class,'updateReviewStatus'])->name('replyreview.status_update');
Route::get('getIndividualReview',[ReviewReplyController::class,'getReview'])->name('getIndividualReview');
Route::post('/sendReview',[ReviewReplyController::class,'sendReview'])->name('sendReview');

Route::get('/customercarepage',[CustomerCarePageController::class,'index'])->name('customercarepage.index');
Route::get('/customercarepage/create',[CustomerCarePageController::class,'create'])->name('customercarepage.create');
Route::post('/customercarepage/store',[CustomerCarePageController::class,'store'])->name('customercarepage.store');
Route::get('/customercarepage/edit/{id}',[CustomerCarePageController::class,'edit'])->name('customercarepage.edit');
Route::post('/customercarepage/{id}',[CustomerCarePageController::class,'update'])->name('customercarepage.update');
Route::get('/customercarepage/destroy/{id}',[CustomerCarePageController::class,'destroy'])->name('customercarepage.destroy');


// product status
Route::patch('product-status', [ProductController::class, 'updateStatusSecond'])->name('product.status');
Route::get('update.product.status/{id}', [ProductController::class, 'updateProductPublish'])->name('update.product.status');



Route::get('get-decendent', [ProductController::class, 'getDecendent'])->name('get-decendent');
Route::get('get-acendent', [ProductController::class, 'getAcendent'])->name('get-acendent');



// Seller Status
Route::patch('seller-status', [SellerController::class, 'updateStatus'])->name('seller.status');


// Blocked Customer

Route::get('blocked-customer', [CustomerController::class, 'blockedCustomer'])->name('blocked.customer');

// Login with Google

Route::get('google-customer', [CustomerController::class, 'googleCustomer'])->name('google.customer');

// Login with Facebook

Route::get('facebook-customer', [CustomerController::class, 'facebookCustomer'])->name('facebook.customer');

// Login with Github

Route::get('github-customer', [CustomerController::class, 'githubCustomer'])->name('github.customer');

//  Login with Web

Route::get('web-customer', [CustomerController::class, 'webCustomer'])->name('web.customer');

// Login with Android

Route::get('android-customer', [CustomerController::class, 'Android'])->name('android.customer');

// Login with Ios
Route::get('ios-customer', [CustomerController::class, 'Ios'])->name('ios.customer');

// Login with Other
Route::get('other-customer', [CustomerController::class, 'Other'])->name('other.customer');

//trash management
Route::get('trash', [TrashController::class, 'index'])->name('trash.index');
Route::patch('trash-restore/{trash}', [TrashController::class, 'restore'])->name('trash.restore');
Route::delete('trash/{trash}', [TrashController::class, 'destroy'])->name('trash.destroy');

Route::get('export-sample/location', [LocationController::class, 'exportSample'])->name('location-sample.export');
Route::get('export-location/location', [LocationController::class, 'export'])->name('location.export');
Route::get('import-location/location', [LocationController::class, 'showImportForm'])->name('location.get-import');
Route::post('import-location/location', [LocationController::class, 'import'])->name('location.import');


Route::post('/deletetrashbulk',[TrashController::class,'deleteBulkTrash'])->name('deletetrashbulk');
// for customer coupon
Route::get('/customer-coupon', [CustomerCouponController::class, 'index'])->name('customer-coupon.index');
Route::get('/customer-coupon/create', [CustomerCouponController::class, 'create'])->name('customer-coupon.create');
Route::post('/customer-coupon', [CustomerCouponController::class, 'store'])->name('customer-coupon.store');
Route::get('/customer-coupon/{customerCoupon}/edit', [CustomerCouponController::class, 'edit'])->name('customer-coupon.edit');
Route::patch('/customer-coupon/{customerCoupon}', [CustomerCouponController::class, 'update'])->name('customer-coupon.update');
Route::delete('/customer-coupon/{customerCoupon}', [CustomerCouponController::class, 'destroy'])->name('customer-coupon.destroy');

Route::resource('page', PageController::class)->except('destroy');
Route::get('page/destroy/{id}', [PageController::class, 'destroy'])->name('page.destroy');
//stock management

Route::post('toggle-status', [ToggleController::class, 'toggleStatus'])->name('toggle.status');
Route::get('/category-filter', [CategoryFilterController::class, 'filter'])->name('view.category.sortable');
Route::post('/updated-category-filter', [CategoryFilterController::class, 'categoryFilter'])->name('category.sortable');
Route::view('search-category', 'admin.category.category');
Route::controller(SettingController::class)->group(function () {
    Route::get('/settings', 'index')->name('settings.index');
    Route::post('/settings', 'syncSetting')->name('settings.update');
    Route::patch('seo/{seo?}', [SettingController::class, 'syncMetaData'])->name('seo.update');
});

Route::get('/social-setting', [SocialSettingController::class, 'index'])->name('social-setting.index');
Route::get('/social-setting/create', [SocialSettingController::class, 'create'])->name('social-setting.create');
Route::post('/social-setting', [SocialSettingController::class, 'store'])->name('social-setting.store');
Route::get('/social-setting/{socialSetting}', [SocialSettingController::class, 'edit'])->name('social-setting.edit');
Route::patch('/social-setting{socialSetting}', [SocialSettingController::class, 'update'])->name('social-setting.update');
Route::delete('/social-setting/{socialSetting}', [SocialSettingController::class, 'destroy'])->name('social-setting.destroy');
Route::patch('/status-social-setting', [SocialSettingController::class, 'updateStatus'])->name('social-setting.status');

Route::get('/contact-setting', [ContactSettingController::class, 'index'])->name('contact-setting.index');
Route::get('/contact-setting/create', [ContactSettingController::class, 'create'])->name('contact-setting.create');
Route::post('/contact-setting', [ContactSettingController::class, 'store'])->name('contact-setting.store');
Route::get('/contact-setting/{contactSetting}', [ContactSettingController::class, 'edit'])->name('contact-setting.edit');
Route::patch('/contact-setting/{contactSetting}', [ContactSettingController::class, 'update'])->name('contact-setting.update');
Route::delete('/contact-setting/{contactSetting}', [ContactSettingController::class, 'destroy'])->name('contact-setting.destroy');
Route::patch('/status-contact-setting', [ContactSettingController::class, 'updateStatus'])->name('contact-setting.status');


Route::get('smtp', [SMTPController::class, 'index'])->name('smtp.index');
Route::get('smtp/create', [SMTPController::class, 'create'])->name('smtp.create');
Route::post('smtp', [SMTPController::class, 'store'])->name('smtp.store');
Route::get('smtp/{smtp}', [SMTPController::class, 'edit'])->name('smtp.edit');
Route::patch('smtp/{sMtp}', [SMTPController::class, 'update'])->name('smtp.update');
Route::delete('smtp/{sMtp}', [SMTPController::class, 'destroy'])->name('smtp.destroy');

Route::get('payment-method', [PaymentMethodController::class, 'index'])->name('payment-method.index');
Route::get('payment-method/create', [PaymentMethodController::class, 'create'])->name('payment-method.create');
Route::post('payment-method', [PaymentMethodController::class, 'store'])->name('payment-method.store');
Route::get('payment-method/{paymentMethod}', [PaymentMethodController::class, 'edit'])->name('payment-method.edit');
Route::patch('payment-method/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-method.update');
Route::delete('payment-method/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-method.destroy');
Route::patch('payment-method-status', [PaymentMethodController::class, 'changeStatus'])->name('payment-method.status');


// for payout period

Route::get('payout-setting', [PayoutSettingController::class, 'index'])->name('payout-setting.index');
Route::get('payout-setting/create', [PayoutSettingController::class, 'create'])->name('payout-setting.create');
Route::post('payout-setting', [PayoutSettingController::class, 'store'])->name('payout-setting.store');
Route::get('payout-setting/{payoutSetting}', [PayoutSettingController::class, 'edit'])->name('payout-setting.edit');
Route::patch('payout-setting/{payoutSetting}', [PayoutSettingController::class, 'update'])->name('payout-setting.update');
Route::delete('payout-setting/{payoutSetting}', [PayoutSettingController::class, 'destroy'])->name('payout-setting.destroy');
Route::patch('payout-setting-status', [PayoutSettingController::class, 'changeStatus'])->name('payout-setting.status');



// route for backup period
Route::get('backup-period', [BackupPeriodController::class, 'index'])->name('backup-period.index');
Route::get('backup-period/create', [BackupPeriodController::class, 'create'])->name('backup-period.create');
Route::post('backup-period', [BackupPeriodController::class, 'store'])->name('backup-period.store');
Route::get('backup-period/{backupPeriod}', [BackupPeriodController::class, 'edit'])->name('backup-period.edit');
Route::patch('backup-period/{backupPeriod}', [BackupPeriodController::class, 'update'])->name('backup-period.update');
Route::delete('backup-period/{backupPeriod}', [BackupPeriodController::class, 'destroy'])->name('backup-period.destroy');
Route::patch('backup-period-status', [BackupPeriodController::class, 'changeStatus'])->name('backup-period.status');

Route::post('search-user-location',[AdminDashboardController::class, 'searchUserLocation'])->name('searchUserLocation');




// Message Setup
Route::get('message-setup', [MessageSetupController::class, 'index'])->name('message-setup.index');
Route::get('test/message-setup', [MessageSetupController::class, 'test'])->name('message-setup.test');
Route::get('message-setup/create', [MessageSetupController::class, 'create'])->name('message-setup.create');
Route::post('message-setup', [MessageSetupController::class, 'store'])->name('message-setup.store');
Route::get('message-setup/{messageSetup}', [MessageSetupController::class, 'edit'])->name('message-setup.edit');
Route::patch('message-setup/{messageSetup}', [MessageSetupController::class, 'update'])->name('message-setup.update');
Route::delete('message-setup/{messageSetup}', [MessageSetupController::class, 'destroy'])->name('message-setup.destroy');

Route::group(['prefix' => 'policy-setup'], function () {
    Route::resource('app-policy', AppPolicyController::class);
});


// FOR FEATURED SECTION
Route::get('/featured-section', [FeaturedSectionController::class, 'index'])->name('featured-section.index');
Route::get('/featured-section/create', [FeaturedSectionController::class, 'create'])->name('featured-section.create');
Route::post('/featured-section', [FeaturedSectionController::class, 'store'])->name('featured-section.store');
Route::get('/featured-section/{featuredSection}/edit', [FeaturedSectionController::class, 'edit'])->name('featured-section.edit');
Route::patch('/featured-section/{featuredSection}', [FeaturedSectionController::class, 'update'])->name('featured-section.update');
Route::delete('/featured-section/{featuredSection}', [FeaturedSectionController::class, 'destroy'])->name('featured-section.destroy');

Route::get('latest/selected-product',[FeaturedSectionController::class,'setSelectedProduct'])->name('get.selectedProduct');
Route::post('latest/selected-product',[FeaturedSectionController::class,'storeSelectedProduct'])->name('store.selectedProduct');

// ROUTE FOR FEATURED PRODUCT


Route::get('/featured-product/{type?}/{id?}', [FeaturedSectionProductController::class, 'create'])->name('featured-product.create');
Route::post('/featured-product', [FeaturedSectionProductController::class, 'store'])->name('featured-section-product.store');
Route::get('/features-product/{featuredSection}', [FeaturedSectionProductController::class, 'index'])->name('featured-section-product.index');


Route::get('/subscriber-list', [SubscriberController::class, 'index'])->name('subscriber.index');
Route::delete('subscriber-delete/{id}', [SubscriberController::class, 'destroy'])->name('subscriber.destroy');

Route::get('/quote-request-list', [QuoteController::class, 'index'])->name('quote.index');
Route::delete('/quote-request-delete/{id}', [QuoteController::class, 'destroy'])->name('quote.destroy');

Route::controller(ProductEditController::class)->prefix('product-edit')->group(function () {
    Route::patch('/{id}/basicedit', 'basicEdit')->name('products-edit.basicEdit');
    Route::patch('/{id}/priceandstock', 'priceAndStock')->name('product-edit.priceAndStock');
    Route::patch('/{id}/description', 'description')->name('product-edit.description');
    Route::patch('/{id}/serviceAndDelivery', 'serviceAndDelivery')->name('product-edit.serviceAndDelivery');
    Route::patch('/{id}/productAttribute', 'productAttribute')->name('product-edit.productAttribute');
    Route::patch('/{id}/seo-update', 'seoUpdated')->name('product-edit.seo');
    Route::post('/remove-color-image', 'removeImage')->name('remove-color-image');

    Route::patch('/{id}/update-category', 'updateCategory')->name('product.update-category');
});

Route::get('sliders/fetch', [SliderController::class, 'fetch'])->name('admin.slider.ajaxfetch');
Route::get('/slider/destroy/{id}', [SliderController::class, 'destroy'])->name('slider.destroy');
Route::get('/slider/index', [SliderController::class, 'index'])->name('slider.index');
Route::post('/slider/store', [SliderController::class, 'store'])->name('slider.store');
Route::post('/slider/update/{id}', [SliderController::class, 'update'])->name('slider.update');
Route::get('/slider/create', [SliderController::class, 'create'])->name('slider.create');
Route::get('/slider/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit');


Route::post('/slider-order/update', [SliderController::class, 'updateOrder'])->name('slider-order.update');

Route::resource('menu', MenuController::class);

Route::get('menu-filter', [MenuFilterController::class, 'filter'])->name('view.menu.sortable');
Route::post('/menu/updated-menu-filter', [MenuFilterController::class, 'categoryFilter'])->name('menu.sortable');

Route::post('/menu-order/update', [MenuFilterController::class, 'updateOrder'])->name('menu-order.update');

Route::get('admin/generate-barcode',[OrderController::class,'generateBarCode'])->name('admin.generateBarcode');



// Route::get('menu', [MenuController::class, 'index'])->name('menu.index');
// Route::get('menu/create', [MenuController::class, 'create'])->name('menu.create');
// Route::post('menu/store', [MenuController::class, 'store'])->name('menu.store');
// Route::get('menu/edit/{id}', [MenuController::class, 'edit'])->name('menu.edit');
// Route::put('menu/update/{id}', [MenuController::class, 'update'])->name('menu.update');
// Route::delete('menu/destroy/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
// Route::post('menu/updateMenu', [MenuController::class, 'updateMenuOrder'])->name('updateMenuOrder');
// Route::get('menu/link/course', [MenuController::class, 'menuLinkCourse'])->name('menuLinkCourse');
// Route::post('menu/saveMenuCategory', [MenuController::class, 'create_menuCategory'])->name('saveMenuCategory');
// 
Route::get('/get-menu-type-content', [MenuController::class, 'getContent'])->name('menu_type_content.get');

//for order
Route::get('/cancel-order',[OrderController::class,'orderCancelRequest'])->name('cancelOrderRequest');
Route::get('order', [OrderController::class, 'index'])->name('order.index');
Route::get('/inhouse-order', [OrderController::class, 'inHouseOrder'])->name('inhouse.order');
Route::get('/seller-order-list', [OrderController::class, 'sellerOrderView'])->name('seller-order-list');
Route::get('/seller-order-view/{id}', [OrderController::class, 'sellerOrderViewList'])->name('admin.sellerOrder');
// Route::get('apply-approve', [OrderController::class, 'apply'])->name('apply');
Route::patch('status-action', [OrderController::class, 'statusAction'])->name('order.status');
Route::get('all-delivery', [OrderController::class, 'alldeliveryIndex'])->name('alldelivery.index');

Route::get('comment', [CommentController::class, 'index'])->name('productcomment');
Route::patch('comment-status', [CommentController::class, 'commentStatus'])->name('comment.status');
Route::get('/newcomments', [CommentController::class, 'newComment'])->name('newcomments');
// Route::patch('status-action', function(){

//     // $data = event(new NotificationCreated("This is for test"));
//     NotificationCreated::dispatch("Hello From User");
//     // dd($data);
//     dd("Hello");
// })->name('order.status');

Route::get('/download-bill/{order}',[BillDownlaodController::class,'downloadBill'])->name('download-bill');
Route::get('/download-bill/{id}/{order}',[BillDownlaodController::class,'downloadSellerBill'])->name('seller.download-bill');
Route::patch('seller-status-action', [SellerOrderController::class, 'StatusAction'])->name('seller.order.status');

Route::get('order-detail', [OrderController::class, 'productDetail'])->name('productDetail');

Route::get('transactions', [TransactionController::class, 'index'])->name('transaction.index');

Route::get('return-order', [AdminReturnAbleController::class, 'index'])->name('returnable.index');
Route::get('return-order/{id?}', [AdminReturnAbleController::class, 'show'])->name('returnable.show');
Route::get('refund-direact/{id?}', [AdminReturnAbleController::class, 'refunddireactShow'])->name('refunddireact.show');
Route::post('return-status-action/', [AdminReturnAbleController::class, 'changeStatus'])->name('returnable.status');

    
Route::get('refund', [RefundController::class, 'index'])->name('refund.index');
Route::get('/refund-paid-request/',[RefundController::class,'refundpaidRequest'])->name('refund.direct.index');
Route::post('refund-status-action/', [RefundController::class, 'changeStatus'])->name('refund.status');
Route::post('refunddirect-status-action/', [RefundController::class, 'changeRefundDirectStatus'])->name('refunddirect.status');
Route::get('/getSellerReason',[SellerCancelOrderReasonController::class,'getReason'])->name('getSellerReason');
Route::get('ready_to_ship/{ref_id}', [OrderController::class, 'readyToShip'])->name('admin.readytoship');
Route::get('view-order/{ref_id}', [OrderController::class, 'viewOrder'])->name('admin.viewOrder');
Route::get('shipped/{id}', [OrderController::class, 'shipped'])->name('admin.shipped');
Route::get('cancelled/{ref_id}', [OrderController::class, 'cancelled'])->name('admin.cancelled');
Route::post('status/{id}', [OrderController::class, 'status'])->name('status.pending');

Route::post('/ordercancelrequest',[SellerCancelOrderReasonController::class,'changeOrderStatus'])->name('ordercancelrequest.confirm');

Route::patch('status-action-customer', [CustomerController::class, 'statusCustomerAction'])->name('customer.action.status');


//for review
// Route::get('product-review', [ReviewController::class, 'index'])->name('review.index');
Route::delete('delete-product-review/{id}', [ReviewController::class, 'destroy'])->name('review.delete');

//for Return Product
Route::get('return-product', [ReturnController::class, 'index'])->name('return.index');
Route::get('delete-product-review', [ReturnController::class, 'destroy'])->name('return.delete');

Route::post('/cart', [ProductController::class, 'addTocart'])->name('add-to-cart');

// Route::post('ckeditor', 'CkeditorController@store')->name('ckeditor.upload');
Route::resource('hub', HubController::class);

Route::get('/hub-near-place', [HubNearPlaceController::class, 'index'])->name('near-place.index');
Route::delete('/hub-near-place/{hubNearPlace}', [HubNearPlaceController::class, 'destroy'])->name('near-place.destroy');
// Route::get('/hub-near-place', [HubNearPlaceController::class, 'index'])->name('near-place.index');

// to get address for the hub creation
Route::get('/address-hub', [HubController::class, 'getAddress'])->name('hub.address');

// for top offer
// FOR FEATURED SECTION
Route::get('/top-offer', [TopOfferController::class, 'index'])->name('top-offer.index');
Route::get('/top-offer/create', [TopOfferController::class, 'create'])->name('top-offer.create');
Route::post('/top-offer', [TopOfferController::class, 'store'])->name('top-offer.store');
Route::get('/top-offer/{topOffer}/edit', [TopOfferController::class, 'edit'])->name('top-offer.edit');
Route::patch('/top-offer/{topOffer}', [TopOfferController::class, 'update'])->name('top-offer.update');
Route::delete('/top-offer/{topOffer}', [TopOfferController::class, 'destroy'])->name('top-offer.destroy');

Route::resource('retailer_offer',RetailerOfferSectionController::class);
Route::get('/retailer_offer/top-offer/product/{topOffer}', [RetailerOfferSectionProductController::class, 'index'])->name('retailer-top-offer-product.index');
Route::get('/retailer_offer/top-offer-product/{topOffer?}', [RetailerOfferSectionProductController::class, 'create'])->name('retailer-top-offer-product.create');
Route::post('/retailer_offer/top-offer-product', [RetailerOfferSectionProductController::class, 'store'])->name('retailer_offer-top-offer-product.store');
// ROUTE FOR FEATURED PRODUCT
Route::get('/top-offer-product/{topOffer?}', [TopOfferProductController::class, 'create'])->name('top-offer-product.create');
Route::post('/top-offer-product', [TopOfferProductController::class, 'store'])->name('top-offer-product.store');
Route::get('/top-offer/product/{topOffer}', [TopOfferProductController::class, 'index'])->name('top-offer-product.index');

// media manager 
Route::get('media-manager', [MediaController::class, 'index'])->name('media.index');

// -------------------------Top Category Route---------------------------
Route::get('/top-categories', [TopCategoryController::class, 'index'])->name('top-categories');
Route::get('/top-categories/create', [TopCategoryController::class, 'create'])->name('top-category.create');
Route::post('/top-categories/store', [TopCategoryController::class, 'store'])->name('top-category.store');
Route::get('/top-categories/edit/{id}', [TopCategoryController::class, 'edit'])->name('top-category.edit');
Route::post('/top-categories/update/{id}', [TopCategoryController::class, 'update'])->name('top-category.update');
Route::delete('/top-categories/delete/{id}', [TopCategoryController::class, 'destroy'])->name('top-category.delete');
// -------------------------/Top Category Route---------------------------

// -------------------------Bulk email and phone number import---------------------------
Route::get('/import/phone/email', [EmailPhoneController::class, 'index'])->name('importemail.index');
Route::get('/import/phone/email/create', [EmailPhoneController::class, 'create'])->name('importemail.create');
Route::post('/import/phone/email/import', [EmailPhoneController::class, 'import'])->name('importemail.import');
Route::post('/import/phone/email/store', [EmailPhoneController::class, 'store'])->name('importemail.store');
Route::get('/import/phone/email/edit/{id}', [EmailPhoneController::class, 'edit'])->name('importemail.edit');
Route::post('/import/phone/email/update/{id}', [EmailPhoneController::class, 'update'])->name('importemail.update');
Route::get('/import/phone/email/delete/{id}', [EmailPhoneController::class, 'destroy'])->name('importemail.delete');
// -------------------------/Bulk email and phone number import---------------------------



// Route for payment histories
Route::get('payment-histories', [PaymentHistoryController::class, 'index'])->name('payment-history.index');

// route for push notification
Route::get('/push-notificaiton', [PushNotificationController::class, 'index'])->name('push-notification.index');
Route::get('/push-notificaiton/create', [PushNotificationController::class, 'create'])->name('push-notification.create');
Route::post('/push-notificaiton', [PushNotificationController::class, 'store'])->name('push-notification.store');
Route::get('/push-notificaiton/{pushNotification}/edit', [PushNotificationController::class, 'edit'])->name('push-notification.edit');
Route::get('/push-notificaiton/{pushNotification}/show', [PushNotificationController::class, 'show'])->name('push-notification.show');
Route::patch('/push-notificaiton/{pushNotification}/update', [PushNotificationController::class, 'update'])->name('push-notification.update');
Route::delete('/push-notificaiton/{pushNotification}', [PushNotificationController::class, 'destroy'])->name('push-notification.destroy');

// route for news letter
// route for push notification
Route::get('/news-letter', [NewsLetterController::class, 'index'])->name('news-letter.index');
Route::get('/news-letter/create', [NewsLetterController::class, 'create'])->name('news-letter.create');
Route::post('/news-letter', [NewsLetterController::class, 'store'])->name('news-letter.store');
Route::get('/news-letter/{newsLetter}/edit', [NewsLetterController::class, 'edit'])->name('news-letter.edit');
Route::get('/news-letter/{newsLetter}/show', [NewsLetterController::class, 'show'])->name('news-letter.show');
Route::patch('/news-letter/{newsLetter}/update', [NewsLetterController::class, 'update'])->name('news-letter.update');
Route::delete('/news-letter/{newsLetter}', [NewsLetterController::class, 'destroy'])->name('news-letter.destroy');

// route for sms marketting
Route::resource('sms', SMSController::class);

// for logs
Route::get('log', [LogController::class, 'index'])->name('log.index');
Route::delete('log/{log}', [LogController::class, 'destroy'])->name('log.destroy');

// route for backup
Route::resource('backup', BackupController::class)->except(['edit', 'update']);

Route::resource('vat-tax', VatTaxController::class)->except(['destroy']);
Route::get('vat-taxes', [VatTaxController::class, 'index'])->name('vat-taxes');
Route::get('vat-tax-delete/{id}', [VatTaxController::class, 'vatTaxDelete'])->name('vat-tax.delete');
// for Payout

Route::get('seller-payout', [SellerPayoutController::class, 'index'])->name('seller-payouts');
Route::get('seller-payout/{payout}', [SellerPayoutController::class, 'show'])->name('seller-payout.show');
Route::post('seller-payout-status', [SellerPayoutController::class, 'statusActi on'])->name('seller-payout-action-status');

// Report
Route::get('customer-report', [CustomerReportController::class, 'index'])->name('customer-report.index');
Route::get('sales-report', [SalesReportController::class, 'index'])->name('sales-report.index');
Route::get('product-report', [ProductReportController::class, 'index'])->name('product-report.index');
Route::get('order-report', [OrderReportController::class, 'index'])->name('order-report.index');
Route::get('sales-report-exportexcel', [SalesReportController::class, 'exportexcel'])->name('salesreportexcel');
Route::get('customer-report-exportexcel', [CustomerReportController::class, 'exportCustomer'])->name('customerreportexcel');
Route::get('product-report-exportexcel', [ProductReportController::class, 'exportProduct'])->name('productreportexcel');
Route::get('order-report-exportexcel', [OrderReportController::class, 'exportOrderReport'])->name('orderreportexcel');

Route::get('/transaction-report', [TransactionReportController::class, 'index'])->name('transaction.index');
Route::get('transaction-excel', [TransactionReportController::class, 'exportProduct'])->name('transactionexcel');
Route::get('search-report', [SearchKeywordController::class, 'index'])->name('search-keyword-report');
Route::get('search-reports', [SearchKeywordController::class, 'index'])->name('search-reports');
Route::get('search-keyword-export', [SearchKeywordController::class, 'exportexcel'])->name('search-keyword-export');
Route::get('log-report', [LogReportController::class, 'index'])->name('log.index');
Route::get('log-report/{id}/show',[LogReportController::class,'show'])->name('log.show');
// change province ,district & locals status
Route::get('provinces', [OurAreaController::class, 'province'])->name('province.index');
Route::get('province-list', [OurAreaController::class, 'province'])->name('provinces.index');
Route::get('province/{id}', [EditAreaController::class, 'provinceStatus'])->name('province.change.status');
Route::get('province-edit/{id}', [ProvinceUpdateController::class, 'edit'])->name('province.edit');
Route::patch('province-update/{id}', [ProvinceUpdateController::class, 'update'])->name('province.update');

Route::get('districts', [OurAreaController::class, 'district'])->name('district.index');
Route::get('district-list', [OurAreaController::class, 'district'])->name('districts.index');
Route::post('district', [EditAreaController::class, 'districtStatus'])->name('district.change.status');
Route::get('district-edit/{id}', [DistrictUpdateController::class, 'edit'])->name('district.edit');
Route::patch('district-update/{id}', [DistrictUpdateController::class, 'update'])->name('district.update');

Route::get('locals', [OurAreaController::class, 'local'])->name('local.index');
Route::get('local-edit/{id}', [LocalLevelController::class, 'edit'])->name('local.edit');
Route::patch('local-update/{id}', [LocalLevelController::class, 'update'])->name('local.update');
Route::get('local-list', [OurAreaController::class, 'local'])->name('locals.index');
Route::get('local', [EditAreaController::class, 'localStatus'])->name('local.change.status');

Route::get('/email/message',[MessageSetupController::class,'getMessage'])->name('email.message');
Route::put('/email/update-message/{id}',[MessageSetupController::class,'updateMessage'])->name('email.message.update');
Route::post('/email/store-message',[MessageSetupController::class,'storeMessage'])->name('email.message.store');

Route::resource('task-action',TaskActionController::class);
Route::resource('task',TaskController::class);
Route::get('download-task-pdf/{id}',[TaskController::class,'downloadTaskPdf'])->name('downloadTaskPdf');
Route::get('/task/{id}/add-subtask',[TaskController::class, 'addSubTask'])->name('add_subtask');
Route::resource('subtask',SubTaskController::class);
Route::get('order-setting',[OrderSettingController::class, 'index'])->name('order.settings');
Route::patch('order-setting/{id}',[OrderSettingController::class, 'update'])->name('order_settings.update');
Route::post('updateDayOff',[OrderSettingController::class, 'updateDayOff'])->name('updateDayOff');
Route::post('/reassign-task',[TaskController::class, 'reassignTask'])->name('task_reassign');
Route::get('/task-log',[TaskController::class, 'getTaskLogs'])->name('task_log');
Route::resource('faq',FaqController::class);
Route::resource('attribute-category',AttributeCategoryController::class);
Route::put('attr_value/{id}',[AttributeCategoryController::class, 'editValue'])->name('attr_value.edit');
Route::post('add-attr-values',[AttributeCategoryController::class,'addValues'])->name('attr_value.store');
Route::delete('attr_value/{id}',[AttributeCategoryController::class, 'deleteValue'])->name('attr_value.delete');

Route::resource('cancel-reason',CancellationReasonController::class);
Route::get('delivery-report', [DeliveryStaffController::class,'getDeliveryReport'])->name('get_delivery_report');


Route::get('/admin/report-data-pdf', [SalesReportController::class, 'exportexcel'])->name('admin.sellersalesreportexcel');