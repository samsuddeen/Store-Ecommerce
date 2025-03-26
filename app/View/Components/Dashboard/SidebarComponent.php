<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;
use App\Enum\Customer\CustomerStatusEnum;
use App\Enum\MessageSetup\MessageSetupEnum;
use App\Enum\Order\RefundStatusEnum;
use App\Enum\Social\SocialEnum;

class SidebarComponent extends Component
{
    public array $menus;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->menus = $this->getData();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        if (Auth::guard('seller')->user()) {
            return view('components.dashboard.sidebar1');
        } else {
            return view('components.dashboard.sidebar-component');
        }
    }

    protected function getData(): array
    {
        $menus = [
            [

                'name' => 'Catalog',
                'href' => '#',
                'icon' => 'aperture',
                'permission' => ['color-read'],
                'children' => [
                    [
                        'name' => 'Products',
                        'href' => '#',
                        'icon' => 'circle',
                        'permission' => ['product-read'],
                        'children' => [
                            [
                                'name' => 'All Products',
                                'href' => route('product.index'),
                                'icon' => 'circle',
                                'permission' => ['product-read'],
                                'children' => []
                            ],
                            [
                                'name' => 'Create Products',
                                'href' => route('product.create'),
                                'icon' => 'circle',
                                'permission' => ['product-read'],
                                'children' => []
                            ],
                        ]
    
                    ],

                   
                    // [
                    //     'name' => 'In-house Product',
                    //     'href' => route('product.index', ['product_type' => 'in-house']),
                    //     'icon' => 'circle',
                    //     'permission' => ['product-read'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'Seller Products',
                    //     'href' => route('product.index', ['product_type' => 'seller-products']),
                    //     'icon' => 'circle',
                    //     'permission' => ['product-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Categories',
                        'href' => '#',
                        'icon' => 'circle',
                        'permission' => ['category-read'],
                        'children' => [
                            [
                                'name' => 'All Categories',
                                'href' => route('category.index'),
                                'icon' => 'circle',
                                'permission' => ['category-read'],
                                'children' => []
                            ],
                            [
                                'name' => 'Create Category',
                                'href' => route('category.create'),
                                'icon' => 'circle',
                                'permission' => ['category-read'],
                                'children' => []
                            ],
                        ]
                    ],
                    
                    [
                        'name' => 'Brands',
                        // 'href' =>'https://jhigu.store/admin/brand,https://jhigu.store/admin/brand/create',
                        'href' =>'#',
                        'icon' => 'circle',
                        'permission' => ['brand-read'],
                        'children' => [
                            [
                                'name' => 'All Brands',
                                'href' => route('brand.index'),
                                'icon' => 'circle',
                                'permission' => ['brand-read'],
                                'children' => []
                            ],
                            [
                                'name' => 'Create Brand',
                                'href' => route('brand.create'),
                                'icon' => 'circle',
                                'permission' => ['brand-read'],
                                'children' => []
                            ],
                        ]
                    ],

                    // [
                    //     'name' => 'Attributes',
                    //     'href' =>'#',
                    //     'icon' => 'circle',
                    //     'permission' => ['product-read'],
                    //     'children' => [
                    //         [
                    //             'name' => 'All Attributes',
                    //             'href' => route('attribute-category.index'),
                    //             'icon' => 'circle',
                    //             'permission' => ['attribute-category-read'],
                    //             'children' => []
                    //         ],
                    //         [
                    //             'name' => 'Create Attribute',
                    //             'href' => route('attribute-category.create'),
                    //             'icon' => 'circle',
                    //             'permission' => ['brand-read'],
                    //             'children' => []
                    //         ],
                    //     ]
                    // ],
                    
                    [
                        'name' => 'Tags',
                        'href' => '#',
                        'icon' => 'circle',
                        'permission' => ['tag-read'],
                        'children' => [
                            [
                                'name' => 'All Tags',
                                'href' => route('tag.index'),
                                'icon' => 'circle',
                                'permission' => ['tag-read'],
                                'children' => []
                            ],
                            [
                                'name' => 'Create Tag',
                                'href' => route('tag.create'),
                                'icon' => 'circle',
                                'permission' => ['tag-read'],
                                'children' => []
                            ],
                        ]
                        
                       
                    ],
                    
                    [
                        'name' => 'Colors',
                        'href' => '#',
                        'icon' => 'circle',
                        'permission' => ['color-read'],
                        'children' => [
                            [
                                'name' => 'All Colors',
                                'href' => route('color.index'),
                                'icon' => 'circle',
                                'permission' => ['color-read'],
                                'children' => []
                            ],
                            [
                                'name' => 'Create Color',
                                'href' => route('color.create'),
                                'icon' => 'circle',
                                'permission' => ['color-read'],
                                'children' => []
                            ],
                        ]
                    ],
                    
                    // [
                    //     'name' => 'Product Review',
                    //     'href' => route('review.index'),
                    //     'icon' => 'circle',
                    //     'permission' => ['seller-review-read'],
                    //     'children' => []
                    // ],
                ]
            ],

            [
                'name' => 'Sales',
                'href' => '#',
                'icon' => 'shopping-cart',
                'permission' => ['order-read', 'order-create'],
                'children' => [
                    [
                        'name' => 'All Orders',
                        'href' => route('order.index'),
                        'icon' => 'circle',
                        'permission' => ['order-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Inhouse Order',
                    //     'href' => route('inhouse.order'),
                    //     'icon' => 'circle',
                    //     'permission' => ['role-read'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'Seller Orders',
                    //     'href' => route('seller-order-list'),
                    //     'icon' => 'circle',
                    //     'permission' => ['role-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Transaction',
                        'href' => route('transaction.index'),
                        'icon' => 'circle',
                        'permission' => ['transaction-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'All Delivery',
                        'href' => route('alldelivery.index'),
                        'icon' => 'circle',
                        'permission' => ['order-read'],
                        'children' => []
                    ],
[
                        'name' => 'Payment Histories',
                        'href' => route('payment-history.index'),
                        'icon' => 'circle',
                        'permission' => ['order-read'],
                        'children' => []
                    ],
[
                        'name' => 'Cancel Order',
                        'href' => route('cancelOrderRequest'),
                        'icon' => 'circle',
                        'permission' => ['order-read'],
                        'children' => []
                    ],
                ]
            ],

            [
                'name' => 'Delivery',
                'href' => '#',
                'icon' => 'truck',
                'permission' => ['slider-read'],
                'children' => [
                    // [
                    //     'name' => 'All Delivery',
                    //     'href' => route('alldelivery.index'),
                    //     'icon' => 'circle',
                    //     'permission' => ['order-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Country',
                        'href' => route('countries.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Provinces',
                        'href' => route('province.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Districts',
                        'href' => route('district.index'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Locals',
                        'href' => route('local.index'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],

                    [
                        'name' => 'Hub',
                        'href' => route('hub.index'),
                        'icon' => 'circle',
                        'permission' => ['order-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Locations',
                        'href' => route('location.index'),
                        'icon' => 'circle',
                        'permission' => ['location-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Delivery Route',
                        'href' => route('delivery-route.index'),
                        'icon' => 'circle',
                        'permission' => ['delivery-route-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Add Delivery',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['order-read'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'Payment Histories',
                    //     'href' => route('payment-history.index'),
                    //     'icon' => 'circle',
                    //     'permission' => ['order-read'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'Collected History',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['order-read'],
                    //     'children' => []
                    // ],

                    // [
                    //     'name' => 'Cancel Order',
                    //     'href' => route('cancelOrderRequest'),
                    //     'icon' => 'circle',
                    //     'permission' => ['order-read'],
                    //     'children' => []
                    // ],
                ]
            ],
            [
                'name' => 'Comments',
                'href' => '#',
                'icon' => 'message-square',
                'permission' => ['slider-read'],
                'children' => [
                    // [
                    //     'name' => 'new Comments',
                    //     'href' => route('newcomments'),
                    //     'icon' => 'circle',
                    //     'permission' => ['order-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'All Comments',
                        'href' => route('productcomment'),
                        'icon' => 'circle',
                        'permission' => ['order-read'],
                        'children' => []
                    ],

                    
                ]
            ],

            [
                'name' => 'Reasons',
                'href' => '#',
                'icon' => 'message-square',
                'permission' => ['slider-read'],
                'children' => [
                    // [
                    //     'name' => 'new Comments',
                    //     'href' => route('newcomments'),
                    //     'icon' => 'circle',
                    //     'permission' => ['order-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Cancellation Reasons',
                        'href' => route('cancel-reason.index'),
                        'icon' => 'circle',
                        'permission' => ['order-read'],
                        'children' => []
                    ],

                    
                ]
            ],

            [
                'name' => 'Returned',
                'href' => '#',
                'icon' => 'corner-down-left',
                'permission' => ['return-read', 'return-delete'],
                'children' => [
                    [
                        'name' => 'Return Request',
                        'href' => route('returnable.index'),
                        'icon' => 'circle',
                        'permission' => ['return-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Approved Return',
                        'href' => route('returnable.index', ['status' => 'APPROVED']),
                        'icon' => 'circle',
                        'permission' => ['return-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Recieved Return',
                        'href' => route('returnable.index', ['status' => 'RECEIVED']),
                        'icon' => 'circle',
                        'permission' => ['return-read'],
                        'children' => []
                    ],
                ]
            ],

            [
                'name' => 'Refund',
                'href' => '#',
                'icon' => 'repeat',
                'permission' => ['user-read', 'role-read'],
                'children' => [
                    [
                        'name' => 'Refund Request',
                        'href' => route('refund.index'),
                        'icon' => 'circle',
                        'permission' => ['return-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Paid Refund Request',
                        'href' => route('refund.direct.index'),
                        'icon' => 'circle',
                        'permission' => ['return-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Approved Refund',
                    //     'href' => route('refund.index', ['type' => RefundStatusEnum::PAID]),
                    //     'icon' => 'circle',
                    //     'permission' => ['return-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Rejected refund',
                        'href' => route('refund.index', ['type' => RefundStatusEnum::REJECTED]),
                        'icon' => 'circle',
                        'permission' => ['return-read'],
                        'children' => []
                    ],

                    // [
                    //     'name' => 'Refund Setting ',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['role-read'],
                    //     'children' => []
                    // ],
                ]
            ],

            [
                'name' => 'Customers',
                'href' => '#',
                'icon' => 'users',
                'permission' => ['user-read', 'role-read'],
                'children' => [
                    [
                        'name' => 'Customer List',
                        'href' => route('customer.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Blocked Customer',
                        'href' => route('blocked.customer', ['type' => CustomerStatusEnum::Blocked]),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Facebook',
                        'href' => route('facebook.customer', ['social' => 'facebook']),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Google',
                        'href' => route('google.customer', ['social' => 'google']),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Github',
                    //     'href' => route('github.customer', ['social' => SocialEnum::Github]),
                    //     'icon' => 'circle',
                    //     'permission' => ['role-read'],
                    //     'children' => []
                    // ],

                    [
                        'name' => 'Web',
                        'href' => route('web.customer', ['social' => 'web']),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Android',
                        'href' => route('android.customer', ['social' => 'android']),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],

                    [
                        'name' => 'iOS',
                        'href' => route('ios.customer', ['social' => 'ios']),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Others',
                        'href' => route('other.customer', ['social' => 'other']),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Inquiry',
                        'href' => route('inquiry.index'),
                        'icon' => 'circle',
                        'permission' => ['customer-read'],
                        'children' => []
                    ],
                ]
            ],
            [
                'name' => 'Review Section',
                'href' => '#',
                'icon' => 'thumbs-up',
                'permission' => ['user-read', 'role-read'],
                'children' => [
                    [
                        'name' => 'All Review',
                        'href' => route('replyreview.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Delivery Feedbacks',
                        'href' => route('delivery-feedback.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    
                ]
            ],

            // [
            //     'name' => 'Sellers',
            //     'href' => '#',
            //     'icon' => 'shopping-bag',
            //     'permission' => ['user-read', 'role-read'],
            //     'children' => [
            //         [
            //             'name' => 'All Sellers',
            //             'href' => route('seller.index'),
            //             'icon' => 'circle',
            //             'permission' => ['user-read'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'Payouts',
            //             'href' => route('seller-payouts'),
            //             'icon' => 'circle',
            //             'permission' => ['user-read'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'Payout Request',
            //             'href' => route('seller-payouts', ['payouttype' => 1]),
            //             'icon' => 'circle',
            //             'permission' => ['user-read'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'Seller Commissions',
            //             'href' => route("sellercommission.index"),
            //             'icon' => 'circle',
            //             'permission' => ['user-read'],
            //             'children' => []
            //         ],
            //     ]
            // ],

            [
                'name' => 'Media Manager',
                'href' => '#',
                'icon' => 'camera',
                'permission' => ['user-read', 'role-read'],
                'children' => [
                    [
                        'name' => 'All Media',
                        'href' => route('media.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Seller Media',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['user-read'],
                    //     'children' => []
                    // ],
                ]
            ],

            [
                'name' => 'Reports',
                'href' => '#',
                'icon' => 'file-text',
                'permission' => ['user-read', 'role-read'],
                'children' => [
                    [
                        'name' => 'Sales',
                        'href' => route('sales-report.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Customers',
                        'href' => route('customer-report.index'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Orders',
                        'href' => route('order-report.index'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'User search Report',
                        'href' => route('search-keyword-report'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Products',
                        'href' => route('product-report.index'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Transactions',
                        'href' => route('transaction.index'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                   
                ]
            ],

            [
                'name' => 'Marketing',
                'href' => '#',
                'icon' => 'volume-2',
                'permission' => ['coupon-read'],
                'children' => [
                    [
                        'name' => 'Latest Selected Product',
                        'href' => route('get.selectedProduct'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Top categories',
                        'href' => route('top-categories'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Flash Sales',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['user-read'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'Top Offer',
                    //     'href' => route('top-offer.index'),
                    //     'icon' => 'circle',
                    //     'permission' => ['product-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Retailer Offer',
                        'href' => route('retailer_offer.index'),
                        'icon' => 'circle',
                        'permission' => ['product-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Import Email-Phone',
                        'href' => route('importemail.index'),
                        'icon' => 'circle',
                        'permission' => ['product-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Coupons',
                        'href' => route('coupon.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Customer Coupons',
                        'href' => route('customer-coupon.index'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Newsletter',
                        'href' => route('news-letter.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'SMS',
                        'href' => route('sms.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Affiliate Marketing',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['user-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Reward points',
                        'href' => route('rewardsection.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                ]
            ],

            // [
            //     'name' => 'Delivery Report',
            //     'href' => route('get_delivery_report'),
            //     'icon' => 'bell',
            //     'permission' => ['task-read'],
            //     'children' => []
            // ],


            [
                'name' => 'Push Notifications',
                'href' => route('push-notification.index'),
                'icon' => 'bell',
                'permission' => ['user-read'],
                'children' => []
            ],

            [
                'name' => 'Web Management',
                'href' => '#',
                'icon' => 'monitor',
                'permission' => ['product-read'],
                'children' => [
                    [
                        'name' => 'Slider',
                        'href' => route('slider.index'),
                        'icon' => 'circle',
                        'permission' => ['slider-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Content',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['slider-read'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'Blog',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['slider-read'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'News',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['slider-read'],
                    //     'children' => []
                    // ],



                    [
                        'name' => 'featured Section',
                        'href' => route('featured-section.index'),
                        'icon' => 'circle',
                        'permission' => ['product-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Subscriber List',
                        'href' => route('subscriber.index'),
                        'icon' => 'circle',
                        'permission' => ['subscriber-view'],
                        'children' => []
                    ],
                    [
                        'name' => 'Quote Request',
                        'href' => route('quote.index'),
                        'icon' => 'circle',
                        'permission' => ['quote-view'],
                        'children' => []
                    ],
                    [
                        'name' => 'FAQ',
                        'href' => route('faq.index'),
                        'icon' => 'circle',
                        'permission' => 'menu-view',
                        'children' => []

                    ],

                    [
                        'name' => 'Advertisement',
                        'href' => route('advertisement.index'),
                        'icon' => 'tv',
                        'permission' => ['advertisement-read'],
                        'children' => [
                            [
                                'name' => 'Advertisement',
                                'href' => route('advertisement.index'),
                                'icon' => 'circle',
                                'permission' => ['advertisement-read'],
                                'children' => []
                            ],
                            [
                                'name' => 'Advertisement Position',
                                'href' => route('position.index'),
                                'icon' => 'circle',
                                'permission' => ['position-read'],
                                'children' => []
                            ]
                        ]
                    ],


                ]
            ],

            [
                'name' => 'Menus Management',
                'href' => '#',
                'icon' => 'menu',
                'permission' => ['menu-menu'],
                'children' => [
                    [
                        'name' => 'Create Menu',
                        'href' => route('menu.create'),
                        'icon' => 'circle',
                        'permission' => ['menu-create'],
                        'children' => []
                    ],
                    [
                        'name' => 'View Menu',
                        'href' => route('menu.index'),
                        'icon' => 'circle',
                        'permission' => ['menu-view'],
                        'children' => []
                    ]
                ]
            ],

            [
                'name' => 'User Management',
                'href' => '#',
                'icon' => 'user-plus',
                'permission' => ['user-read', 'role-read'],
                'children' => [
                    [
                        'name' => 'Create user',
                        'href' => route('user.create'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Users',
                        'href' => route('user.index'),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Create Role',
                        'href' => route('role.create'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Roles',
                        'href' => route('role.index'),
                        'icon' => 'circle',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                ]
            ],

            // [
            //     'name' => 'Task Management',
            //     'href' => '#',
            //     'icon' => 'user-plus',
            //     'permission' => ['task-read'],
            //     'children' => [
            //         [
            //             'name' => 'Create Action',
            //             'href' => route('task-action.create'),
            //             'icon' => 'circle',
            //             'permission' => ['task-action-create'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'View Action',
            //             'href' => route('task-action.index'),
            //             'icon' => 'circle',
            //             'permission' => ['task-action-read'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'Create Task',
            //             'href' => route('task.create'),
            //             'icon' => 'circle',
            //             'permission' => ['task-create'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'Tasks',
            //             'href' => route('task.index'),
            //             'icon' => 'circle',
            //             'permission' => ['task-read'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'Create Subtask',
            //             'href' => route('subtask.create'),
            //             'icon' => 'circle',
            //             'permission' => ['task-read'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'Subtasks',
            //             'href' => route('subtask.index'),
            //             'icon' => 'circle',
            //             'permission' => ['task-read'],
            //             'children' => []
            //         ],
            //         [
            //             'name' => 'Task Log',
            //             'href' => route('task_log'),
            //             'icon' => 'circle',
            //             'permission' => ['task-edit'],
            //             'children' => []
            //         ],
            //     ]
            // ],



            [
                'name' => 'Settings',
                'href' => '#',
                'icon' => 'settings',
                'permission' => ['settings-read'],
                'children' => [
                    [
                        'name' => 'General Settings',
                        'href' => route('settings.index'),
                        'icon' => 'circle',
                        'permission' => ['web-setting'],
                        'children' => []
                    ],
                    [
                        'name' => 'Order Settings',
                        'href' => route('order.settings'),
                        'icon' => 'circle',
                        'permission' => ['web-setting'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'App Policy',
                    //     'href' => route('app-policy.index'),
                    //     'icon' => 'circle',
                    //     'permission' => ['web-setting'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'Features Activation',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['policy-setting'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Payout Period',
                        'href' => route('payout-setting.index'),
                        'icon' => 'circle',
                        'permission' => ['policy-setting'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Currency',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['policy-setting'],
                    //     'children' => []
                    // ],
                    // [
                    //     'name' => 'VAT/TAX',
                    //     'href' => '#',
                    //     'icon' => 'circle',
                    //     'permission' => ['policy-setting'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Payment Methods',
                        'href' => route('payment-method.index'),
                        'icon' => 'circle',
                        'permission' => ['policy-setting'],
                        'children' => []
                    ],
                    [
                        'name' => 'Social Media',
                        'href' => route('social-setting.index'),
                        'icon' => 'circle',
                        'permission' => ['policy-setting'],
                        'children' => []
                    ],

                    [
                        'name' => 'Contact',
                        'href' => route('contact-setting.index'),
                        'icon' => 'circle',
                        'permission' => ['policy-setting'],
                        'children' => []
                    ],


                    [
                        'name' => 'Mail setting - SMTP',
                        'href' => route('smtp.index'),
                        'icon' => 'circle',
                        'permission' => ['policy-setting'],
                        'children' => []
                    ],
                    [
                        'name' => 'Backup Period',
                        'href' => route('backup-period.index'),
                        'icon' => 'circle',
                        'permission' => ['policy-setting'],
                        'children' => []
                    ],
                    [
                        'name' => 'Mobile Customer Care Section',
                        'href' => route('customercarepage.index'),
                        'icon' => 'circle',
                        'permission' => ['policy-setting'],
                        'children' => []
                    ],
                ]
            ],

            [
                'name' => 'Email Messages Setup',
                'href' => '#',
                'icon' => 'mail',
                'permission' => ['user-read', 'role-read'],
                'children' => [
                    [
                        'name' => 'Order Status Email Setup',
                        'href' => '#',
                        'icon' => 'mail',
                        'permission' => ['user-read', 'role-read'],
                        'children'=>[
                            [
                                'name' => 'Ready To Ship',
                                'href' => route('message-setup.index', ['title' => MessageSetupEnum::ORDER_READY_TO_SHIP]),
                                'icon' => 'circle',
                                'permission' => ['order-read'],
                                'children' => []   
                            ],
                            [
                                'name' => 'Dispatched',
                                'href' => route('message-setup.index', ['title' => MessageSetupEnum::DISPATCHED]),
                                'icon' => 'circle',
                                'permission' => ['order-read'],
                                'children' => []   
                            ],
                            [
                                'name' => 'Shipped',
                                'href' => route('message-setup.index', ['title' => MessageSetupEnum::SHIPED]),
                                'icon' => 'circle',
                                'permission' => ['order-read'],
                                'children' => []   
                            ],
                            [
                                'name' => 'Delivered',
                                'href' => route('message-setup.index', ['title' => MessageSetupEnum::DELIVERED]),
                                'icon' => 'circle',
                                'permission' => ['order-read'],
                                'children' => []   
                            ],
                            [
                                'name' => 'Cancel',
                                'href' => route('message-setup.index', ['title' => MessageSetupEnum::CANCEL]),
                                'icon' => 'circle',
                                'permission' => ['order-read'],
                                'children' => []   
                            ],
                            [
                                'name' => 'Rejected',
                                'href' => route('message-setup.index', ['title' => MessageSetupEnum::REJECTED]),
                                'icon' => 'circle',
                                'permission' => ['order-read'],
                                'children' => []   
                            ],
                            [
                                'name' => 'Email Message Section',
                                'href' => route('email.message'),
                                'icon' => 'circle',
                                'permission' => ['order-read'],
                                'children' => []   
                            ],
                        ],
                    ],
                    [
                        'name' => 'Order Place',
                        'href' => route('message-setup.index', ['title' => MessageSetupEnum::ORDER_PLACE]),
                        'icon' => 'circle',
                        'permission' => ['order-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Checkout',
                        'href' => route('message-setup.index', ['title' => MessageSetupEnum::CHECKOUT]),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Delivery',
                        'href' => route('message-setup.index', ['title' => MessageSetupEnum::DELIVERY]),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Order Status Changed',
                        'href' => route('message-setup.index', ['title' => MessageSetupEnum::ORDER_STATUS_CHANGED]),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Payout Request',
                        'href' => route('message-setup.index', ['title' => MessageSetupEnum::PAYOUT_REQUEST]),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Payout Request Status Changed',
                        'href' => route('message-setup.index', ['title' => MessageSetupEnum::PAYOUT_REQUEST_STATUS_CHANGED]),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Fund Request',
                        'href' => route('message-setup.index', ['title' => MessageSetupEnum::REFUND_REQUEST]),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Fund Request Status Changed',
                        'href' => route('message-setup.index', ['title' => MessageSetupEnum::REFUND_REQUEST_STATUS_CHANGED]),
                        'icon' => 'circle',
                        'permission' => ['user-read'],
                        'children' => []
                    ],

                    
                ]
            ],

            [
                'name' => 'Trash',
                'href' => '#',
                'icon' => 'trash-2',
                'permission' => ['transaction-read'],
                'children' => [
                    [
                        'name' => 'All Trash',
                        'href' => route('trash.index'),
                        'icon' => 'circle',
                        'permission' => ['trash-read'],
                        'children' => []
                    ],
                ]
            ],

            [
                'name' => 'Logs',
                'href' => '#',
                'icon' => 'edit',
                'permission' => ['transaction-read'],
                'children' => [
                    [
                        'name' => 'All Log',
                        'href' => route('log.index'),
                        'icon' => 'circle',
                        'permission' => ['transaction-read'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Seller',
                    //     'href' => route('log.index', ['type' => 'App\Models\Seller']),
                    //     'icon' => 'circle',
                    //     'permission' => ['transaction-read'],
                    //     'children' => []
                    // ],
                    [
                        'name' => 'Customers',
                        'href' => route('log.index', ['type' => 'App\Models\New_Customer']),
                        'icon' => 'circle',
                        'permission' => ['transaction-read'],
                        'children' => []
                    ],
                ]
            ],

            [
                'name' => 'Backup',
                'href' => route('backup.index'),
                'icon' => 'database',
                'permission' => ['transaction-read'],
                'children' => []
            ],
        ];
        $sellers = (new SellerMenu)->getData();

        if (Auth::guard('seller')->check()) {
            return $sellers;
        }
        if (Auth::guard('web')->check()) {
            return $menus;
        }
    }
}
