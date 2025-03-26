<?php

use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GuestCartController;
use App\Http\Controllers\FooterMenuController;
use App\Http\Controllers\LikeReviewController;
use App\Http\Controllers\Ajax\FilterController;
use App\Http\Controllers\ReviewReplyController;
use App\Http\Controllers\Frontend\RFQController;
use App\Http\Controllers\Frontend\TestController;
use App\Http\Controllers\Ajax\TagFilterController;
use App\Http\Controllers\DirectCheckoutController;
use App\Http\Controllers\FrontAttributeController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\oneProductOrderController;
use App\Http\Controllers\Frontend\InvoiceController;
use App\Http\Controllers\GuestKhaltiOrderController;
use App\Http\Controllers\Checkout\CheckoutController;
use App\Http\Controllers\Datatables\CouponController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\Cart\CartController;
use App\Http\Controllers\Frontend\SubscribeController;
use App\Http\Controllers\Frontend\EsewaOrderController;
use App\Http\Controllers\Guest\GuestCheckoutController;
use App\Http\Controllers\Frontend\Seller\SellerController;
use App\Http\Controllers\Customer\BillingAddressController;
use App\Http\Controllers\Customer\ShippingAddressController;
use App\Http\Controllers\Guest\GuestCheckoutEsewaController;
use App\Http\Controllers\Guest\GuestEsewaSingleProductController;

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('/newmail', [FrontendController::class, 'newMail'])->name('newmail');
Route::get('/new-to-cart', [TestController::class, 'addToCart'])->name('new-to-cart');
Route::get('/search', [SearchController::class, 'finalSearch'])->name('final-search');
Route::get('/', [FrontendController::class, 'index'])->name('index');
Route::post('/updateLocation', [FrontendController::class, 'updateLocation'])->name('updateLocation');

Route::get('/autosearchtag', [FrontendController::class, 'autoSearchTag'])->name('autosearchtag');
Route::get('/autoSearchTagAdmin', [FrontendController::class, 'autoSearchTagAdmin'])->name('autoSearchTagAdmin');
Route::get('/show-province', [FrontendController::class, 'getProvince'])->name('show-province');
Route::get('/show-district', [FrontendController::class, 'getDistrictData'])->name('show-district');
Route::get('/show-area', [FrontendController::class, 'getAreaData'])->name('get-addtional-address');
Route::get('/show-area-customer', [FrontendController::class, 'getAreaDataCustomer'])->name('get-addtional-customer');
// address storing in session.
Route::get('popular-product-list', [FrontendController::class, 'popularProductList'])->name('popularproduct.list');
Route::get('special-offer-list', [FrontendController::class, 'specialProductList'])->name('specialoffer.list');
Route::get('new-product-list', [FrontendController::class, 'newProductList'])->name('newproduct.list');
Route::get('selected-product-list', [FrontendController::class, 'newSelectedList'])->name('selectedproduct.list');
Route::get('special-product-list', [FrontendController::class, 'specialOfferList'])->name('special_offer.list');
//for category wise
Route::get('/category/{slug}', [FrontendController::class, 'category_show'])->name('category.show');
//tagwise product
Route::get('/tag/{slug}', [FrontendController::class, 'tags_show'])->name('tags.show');
Route::post('/tag-to-cart', [CartController::class, 'tagToCart'])->name('tag-to-cart');
Route::post('/addToCart/directAdd-to-cart', [CartController::class, 'directAddToCart'])->name('directadd-to-cart');
Route::post('/deleteguestToCart/directdeleteguest-to-cart', [CartController::class, 'directGuestDeleteToCart'])->name('directguestdelete-to-cart');
Route::get('/guest/direct/checkout', [CartController::class, 'guestAllCheckout'])->name('directguestallcheckout-to-cart');
Route::get('/brands/{slug}', [FrontendController::class, 'brandIndex'])->name('brand-front.index');
Route::get('brand-multiple-filter', [FilterController::class, 'brandMultipleFilter'])->name('brand-multiple-filter');
// FOR PRODUCT DETAILS
Route::get('/filter-review', [ReviewReplyController::class, 'filterReview'])->name('filter-review');
Route::get('/product/{slug}', [FrontendController::class, 'getDetails'])->name('product.details');
Route::get('/updateAttributeData',[FrontendController::class, 'updateAttributeData'])->name('updateAttributeData');
Route::get('/category', [CategoryController::class, 'category'])->name('category');
Route::get('/glasspipe/inquiry/{slug}', [FrontendController::class, 'customerInquiryForm'])->name('customer.inquiry');
Route::get('test', [FrontendController::class, 'test']);
Route::post('trace-order', [FrontendController::class, 'traceOrder'])->name('traceOrder');
Route::post('/user/update-billing-address', [BillingAddressController::class, 'updateBillingAddress'])->name('update-billing-address');
Route::post('update-billing-address/{id}', [FrontendController::class, 'updateBillingAddressNew'])->name('updateBillingAddress');
// FOR CART
Route::get('/cart-count', [CartController::class, 'getCartCount'])->name('getCartCount');
Route::get('/guest-cart-count', [GuestCartController::class, 'getGuestCartCount'])->name('getGuestCartCount');
Route::post('/add-to-wishlist', [CartController::class, 'addToWishList'])->name('addToWishlist');
Route::get('/checkout/{slug}', [CartController::class, 'directBuy'])->name('directBuy');
Route::get('/price-calculation', [CartController::class, 'priceCalculation'])->name('priceCalculation');
Route::post('/update-cart', [CartController::class, 'updateCart'])->name('updateCart');
Route::get('/cart-Data', [CartController::class, 'getCartData'])->name('getCartData');
Route::get('/cart', [CartController::class, 'getCart'])->middleware('Customer')->name('cart.index');
Route::post('/checkout', [CheckoutController::class, 'getCheckout'])->name('pre-checkout.post');
Route::get('/checkout', [CartController::class, 'getCheckout'])->middleware('Customer')->name('cart.checkout');
Route::post('/product/{slug?}', [CartController::class, 'addToCart'])->name('cart.add-to-cart');

// guest cart------------------------------
Route::post('/add-to-guest-cart', [GuestCartController::class, 'Cart'])->name('addto.guest.cart');
Route::post('/add-to-guest-cart-category-tag', [GuestCartController::class, 'CartFromCategory'])->name('addto.guest.cartFromCategory');
// Route::post('/delete-a-guestProduct-single', [GuestCartController::class, 'deleteGuestCartSingleProduct'])->name('delete.guest.a.singleProduct');
Route::get('delete-guest-singleProduct/{key}', [GuestCartController::class, 'deleteGuestCartSilngle'])->name('delete.guestcart.singleproduct');
Route::get('/delete-all-guestCart', [GuestCartController::class, 'CartDelete'])->name('guest.cartDelete');
Route::post('/store-inSession-forCheckout', [GuestCheckoutController::class, 'storeInSession'])->name('guest.checkoutInSession');
Route::get('/guest-direct-checkout', [GuestCheckoutController::class, 'GuestCheckout'])->name('guest-checkoutForm');
Route::get('/geust-all-checkout', [GuestCheckoutController::class, 'guestCheckoutAll'])->name('guest-checkout-all');
Route::get('/get-shipping-charge-ofAddress', [GuestCartController::class, 'guestShippingCharge'])->name('guestShipping_charge');
Route::get('guest-shipping-charge-for-singleProduc', [GuestCartController::class, 'guestShippingChargeForSingleProduct'])->name('guestShipping_charge_forSingleProduct');
Route::post('guest-singleProduct-Checkout', [GuestCheckoutController::class, 'checkoutSingle'])->name('guest.checkout.orderSuccess');
Route::post('/guest-allproduct-checkout', [GuestCheckoutController::class, 'checkoutAllProduct'])->name('guest.allcheckout.orderSuccess');
Route::post('/guest-singleProduct-saveGuestInfo', [GuestCartController::class, 'storeGuestInfoForSingleCheckout'])->name('guest.storeInfo.directCheckout');
Route::post('/guest-store-info-forAllProduct', [GuestCartController::class, 'storeGuestInfoForAllProduct'])->name('guest.storeInfo.allCheckout');
Route::get('/success-paid-for-singleProduct', [GuestEsewaSingleProductController::class, 'singleProductCheckout'])->name('guest.esewa.singleProduct.buy');
Route::get('/success-guest-all-order-esewa', [GuestCheckoutEsewaController::class, 'successGuestOrderEsewa'])->name('guest.checkoutEsewa.success');
Route::get('/get-guest-cart-item', [GuestCartController::class, 'getGuestCartItem'])->name('get-guest-cart-item');
// guest-khalti
Route::get('guest-singleProductKhalti', [GuestKhaltiOrderController::class, 'singleProductOrder'])->name('guest.khaltiSingleProduct');
Route::get('/guest-all-product-orderKhalti', [GuestKhaltiOrderController::class, 'allProductORder'])->name('guest.khaltiAllProduct');
// khalti of direct checkout
Route::get('/one-product-login-checkout-khalti', [oneProductOrderController::class, 'oneProductCheckout'])->name('direct.khalti.singleProduct');
Route::get('/one-product-login-checkout-esewa', [oneProductOrderController::class, 'oneProductEsewaCheckout'])->name('direct.esewa.singleProduct');
Route::get('/esewa-payment-success-single-product', [oneProductOrderController::class, 'oneProductEsewa'])->name('esewa-payment-success-single-login');

// direct checkout of single product----
Route::get('get-checkout-single-product', [DirectCheckoutController::class, 'singleCheckout'])->name('direct.Checkout');
Route::post('direct-checkout-singleProduct', [PaymentController::class, 'directCheckout'])->name('direct-checkout');
Route::post('guest-direct-checkout-singleProduct', [PaymentController::class, 'guestdirectCheckout'])->name('guest-direct-checkout');
Route::post('guest-direct-checkout-bulkProduct', [PaymentController::class, 'guestalldirectCheckout'])->name('guest-alldirect-checkout');

Route::get('esewa/response', [PaymentController::class, 'esewaResponse'])->name('esewaResponse');

Route::post('/add-single-product', [CartController::class, 'addSingleProductToCart'])->name('addSingleProductToCart');
Route::get('/wishlist-to-cart', [CartController::class, 'wishlistToCart'])->name('wishlistToCart');
Route::post('/remove-product-from-cart', [CartController::class, 'ajaxRemoveCart'])->middleware('Customer')->name('productRemove');
Route::get('remove-cart-product/{id}', [CartController::class, 'removeProduct'])->name('removeProduct');
Route::post('/add-to-sessionFor-checkoutUser', [PaymentController::class, 'storeOrderInSession'])->name('direct.checkoutOrderInsession');
Route::get('/orderplaced/{order}', [InvoiceController::class, 'index'])->name('invoice');
Route::get('/orderplaced/invoiceGuest/{order}', [InvoiceController::class, 'indexinvoiceGuest'])->name('invoiceGuest');
Route::get('/orderplaced-guest/{order}', [InvoiceController::class, 'indexGuest'])->name('invoice.new');
Route::get('/orderplaced-guest/invoicedata/{order}', [InvoiceController::class, 'indexGuestBulk'])->name('invoice.newguest');

// For Filter
Route::post('filterd-data/', [FilterController::class, 'product'])->name('filterData');
Route::get('/search-multiple-filter', [FilterController::class, 'searchMultipleFilter'])->name('search-multiple-filter');
Route::get('/tag-multiple-filter', [FilterController::class, 'tagMultipleFilter'])->name('tag-multiple-filter');
Route::get('/popular-multiple-filter', [FilterController::class, 'popularMultipleFilter'])->name('popular-multiple-filter');
Route::get('/new-multiple-filter', [FilterController::class, 'newMultipleFilter'])->name('new-multiple-filter');
Route::get('/special-offer-filter', [FilterController::class, 'newSpecialOfferMultipleFilter'])->name('special-offer-filter');
Route::get('/cat-multiple-filter', [FilterController::class, 'catMultipleFilter'])->name('cat-multiple-filter-data');
Route::get('/search-with-multiple-filter', [FilterController::class, 'searchWithMultipleFilter'])->name('search.multiple-filter-data');


Route::get('/khalti-return-callback', [PaymentController::class, 'khaltiCallBackAction'])->name('khalti-callback-return');
Route::get('/esewa-return-callback', [PaymentController::class, 'EsewaCallBackAction'])->name('esewa-callback-return');


Route::get('/category-filter-product', [FilterController::class, 'categoryWiseProduct'])->name('categoryproduct-filter');
Route::get('/category-filter-paginate', [FilterController::class, 'categoryWiseProductPaginate'])->name('categoryproduct-filterpaginate');
Route::get('/sort-tag-item', [TagFilterController::class, 'sortTagItem'])->name('sort.item');
Route::get('/paginate-item', [TagFilterController::class, 'paginateItem'])->name('paginate');
Route::post('tag-filtered-data/', [TagFilterController::class, 'product'])->name('tagFilterData');
Route::post('search-filtered-data/', [SearchController::class, 'searchFilterData'])->name('searchFilterData');
// General
Route::post('review/', [FrontendController::class, 'review'])->name('review');
Route::post('comment/', [FrontendController::class, 'comment'])->name('comment');
Route::post('shipping-address/', [PaymentController::class, 'cashOnDelivery'])->name('cash-on-delivery');
Route::get('/succesfully-paid-esewa/success-order', [EsewaOrderController::class, 'successOrderEsewa'])->name('esewa-payment-success');
Route::post('update-shipping-address/{id}', [FrontendController::class, 'updateshippingaddress'])->name('updateshippingaddress');
Route::post('one-shipping-address/', [FrontendController::class, 'oneShippingAddress'])->name('oneShippingAddress');
Route::post('billing-address/', [FrontendController::class, 'billingAddress'])->name('billingAddress');
// get district and local area through ajax
Route::post('get-district/', [FrontendController::class, 'getDistrict'])->name('getDistrict');
Route::post('get-local/', [FrontendController::class, 'getLocal'])->name('getLocal');
// for all  payment
Route::post('coupon', [PaymentController::class, 'coupon'])->name('coupon'); //coupon for normal buy
Route::post('direct-buy-coupon', [PaymentController::class, 'couponOne'])->name('couponOne'); //coupon for direct buy
Route::post('get-shipping-price/', [PaymentController::class, 'getShippingCharge'])->name('getShippingCharge');
//esewa
Route::post('payment/esewa', [PaymentController::class, 'esewa'])->name('esewa');
Route::get('esewa-success', [PaymentController::class, 'success'])->name('esewa.success');
Route::get('esewa-failure', [PaymentController::class, 'failure'])->name('esewa.failure');
// khalti
Route::get('/send-khalti', [PaymentController::class, 'sendKhalti'])->name('send-khalti');
Route::post('khalti-order', [PaymentController::class, 'khaltiOrder'])->name('khalti.order');
// Route::post('khalti-order-one', [PaymentController::class, 'khaltiOrderOne'])->name('khalti.order.one');
Route::get('home-search/', [SearchController::class, 'search'])->name('search');
Route::get('auto-complete-search', [SearchController::class, 'autoComplete'])->name('autoComplete');
Route::get('footer', [FooterController::class, 'index'])->name('footer');
Route::get('footer/create', [FooterController::class, 'create'])->name('footer.create');
Route::post('footer/store', [FooterController::class, 'store'])->name('footer.store');
Route::get('footer/edit/{id}', [FooterController::class, 'edit'])->name('footer.edit');
Route::put('footer/update/', [FooterController::class, 'update'])->name('footer.update');
Route::get('details/{id}', [FooterController::class, 'details'])->name('footer.details');
Route::delete('footer/delete/{id}', [FooterController::class, 'destroy'])->name('footer.delete');
Route::get('updatestatus/{id}', [FooterController::class, 'status']);
Route::get('footermenu', [FooterMenuController::class, 'index'])->name('footermenu');
Route::get('footermenu/create', [FooterMenuController::class, 'create'])->name('footermenu.create');
Route::post('footermenu/store', [FooterMenuController::class, 'store'])->name('footermenu.store');
Route::get('footermenu/edit/{id}', [FooterMenuController::class, 'edit'])->name('footermenu.edit');
Route::put('footermenu/update/', [FooterMenuController::class, 'update'])->name('footermenu.update');
Route::delete('footermenu/delete/{id}', [FooterMenuController::class, 'destroy'])->name('footermenu.delete');
Route::get('menustatus/{id}', [FooterMenuController::class, 'status']);
Route::get('header', [HeaderController::class, 'index'])->name('header');
Route::get('header/create', [HeaderController::class, 'create'])->name('header.create');
Route::post('header/store', [HeaderController::class, 'store']);
Route::get('header/edit/{id}', [HeaderController::class, 'edit'])->name('header.edit');
Route::put('header/update/', [HeaderController::class, 'update'])->name('header.update');
Route::delete('header/delete/{id}', [HeaderController::class, 'destroy'])->name('header.delete');
Route::get('status/{id}', [HeaderController::class, 'status']);
Route::post('request-for-quote', [RFQController::class, 'store'])->name('RFQ');
Route::post('subscribe', [SubscribeController::class, 'subscribe'])->name('subscribe');
Route::get('/get-shipping-charge', [ShippingAddressController::class, 'getShippingCharge'])->name('get-shipping-charge');
Route::get('/verify-coupon', [CouponController::class, 'verifyCoupon'])->name('verify-coupon');
Route::get('/verify-coupon-direct', [CouponController::class, 'verifyDirectCoupon'])->name('verify-coupon-direct');
Route::get('/add-shipping-address', [ShippingAddressController::class, 'addShippingAddress'])->name('add-shipping-address');
Route::get('/add-billing-address', [ShippingAddressController::class, 'addBillingAddress'])->name('add-billing-address');
Route::post('/update-shipping-address', [ShippingAddressController::class, 'updateShippingAddress'])->name('update-shipping-address');
Route::get('/default_shipping_address', [FrontendController::class, 'setDefaultAddress'])->name('default_shipping_address');
// -------------------------Seller Front Route----------------------------
Route::get('/seller/search-item', [SellerController::class, 'searchItem'])->name('seller-item.search');
Route::get('/seller/{slug}', [SellerController::class, 'sellerView'])->name('seller');
Route::get('/seller/products/{slug}/{q?}', [SellerController::class, 'ProductView'])->name('seller.products');
Route::get('/seller/profiles/{slug}', [SellerController::class, 'ProfileView'])->name('seller.profile');
Route::get('/seller-multiple-filter', [SellerController::class, 'sellerMultipleFilter'])->name('seller-multiple-filter-data');
Route::get('/seller-search-multiple-filter', [SellerController::class, 'sellerSearchMultipleFilter'])->name('seller-search-multiple-filter-data');
Route::get('/selleer/filter/product', [SellerController::class, 'productFilter'])->name('seller.filter');

Route::get('/get/ajax-catproduct/{slug}/{id?}', [FrontendController::class, 'loadMoreProduct']);
Route::get('/voice-search', [SearchController::class, 'voiceSearch'])->name('voice-search');
Route::get('/order-detail-pdfdownload/{refid}', [FrontendController::class, 'orderPdf'])->name('user-order-detail-pdfs');
Route::get('/order-detail-pdfdownloadall/{refid}', [FrontendController::class, 'orderPdfall'])->name('user-order-detail-pdfsall');
Route::get('/order-detail-pdfdownload-mobile/{refid}', [FrontendController::class, 'mobileOrderPdf1'])->name('user-order-detail-pdfs');
Route::get('/mobile-order-detail-pdfdownload/{refid}', [FrontendController::class, 'mobileOrderPdf'])->name('mobile-user-order-detail-pdfs');

Route::get('/live-search', [SearchController::class, 'liveSearch'])->name('live.search.all');

Route::get('/likeReview', [LikeReviewController::class, 'likeReview'])->name('likeReview');
Route::get('/likeReviewReply', [LikeReviewController::class, 'likeReviewReply'])->name('likeReviewReply');
// -------------------------/Seller Front Route----------------------------
Route::post('/inquiry', [FrontendController::class, 'sendInquiry'])->name('send_inquiry');
Route::post('/getinquiry', [FrontendController::class, 'sendgetinquiry'])->name('getinquirysend_inquiry');

Route::get('mailtrack/{refId}/{email}', [FrontendController::class, 'mailTrack'])->name('mailtrack');

Route::get('{slug?}', [FrontendController::class, 'general'])->name('general');


Route::get('/customer/paypal-success/{extraDetails?}', [PayPalController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('/customer/paypal-success/direct/payment/{extraDetails?}', [PayPalController::class, 'paypalSuccessDirect'])->name('paypal.success-direct');
Route::get('/guest/direct/payment/{extraDetails?}', [PayPalController::class, 'paypalGuestSuccessDirect'])->name('paypal.guest-success-direct');
Route::get('/customer/paypal-cancel', [PayPalController::class, 'paypalCancel'])->name('paypal.cancel');



Route::get('get-product-attributes/data/value', [FrontAttributeController::class, 'getProductAttribute'])->name('getAttributeValue');
Route::fallback(function () {
    return abort(404);
});
