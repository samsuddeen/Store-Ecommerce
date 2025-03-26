<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class SellerSidebarComponent extends Component
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
        // dd(Auth::guard('seller')->user()->getAllPermissions());
        if(Auth::guard('seller')->user())
        {
            return view('components.dashboard.sidebar1');
        }else{
        return view('components.dashboard.sidebar-component');
        }
    }

    protected function getData(): array
    {
        return [
            [
                'name' => 'home',
                'href' => route('dashboard'),
                'icon' => 'home',
                'permission' => [],
                'children' => []
            ],

            [
                'name' => 'authentication',
                'href' =>'#',
                'icon' => 'shield',
                'permission' => ['user-read','role-read'],
                'children' => [
                    [
                        'name' => 'users',
                        'href' => route('user.index'),
                        'icon' => 'users',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'role',
                        'href' => route('role.index'),
                        'icon' => 'shield-off',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                ]
            ],
            
            
            [
                'name' => 'Seller',
                'href' =>'#',
                'icon' => 'shield',
                'permission' => ['user-read','role-read'],
                'children' => [
                    [
                        'name' => 'Sellers',
                        'href' => route('seller.index'),
                        'icon' => 'users',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Create New Sellers',
                        'href' => route('seller.create'),
                        'icon' => 'shield-off',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                ]
            ],   


            [
                'name' => 'settings',
                'href' => '#',
                'icon' => 'tool',
                'permission' => ['settings-read'],
                'children' => [
                    [
                        'name' => 'Web Setting',
                        'href' => route('settings.index'),
                        'icon' => 'users',
                        'permission' => ['web-setting'],
                        'children' => []
                    ],
                    [
                        'name' => 'Policy Setting',
                        'href' => route('app-policy.index'),
                        'icon' => 'shield-off',
                        'permission' => ['policy-setting'],
                        'children' => []
                    ],
                    // [
                    //     'name' => 'Terms and Condition',
                    //     'href' => route('seller.create'),
                    //     'icon' => 'shield-off',
                    //     'permission' => ['term-and-condition-setting'],
                    //     'children' => []
                    // ],
                ]
            ],


            [
                'name' => 'Slider',
                'href' => route('slider.index'),
                'icon' => 'camera',
                'permission' => ['slider-read'],
                'children' => []
            ],


            [
                'name' => 'Location/Hub Setup',
                'href' => '#',
                'icon' => 'camera',
                'permission' => ['slider-read'],
                'children' => [
                    [
                        'name' => 'Hub',
                        'href' => route('hub.index'),
                        'icon' => 'feather',
                        'permission' => ['order-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Location',
                        'href' => route('location.index'),
                        'icon' => 'map',
                        'permission' => ['location-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Delivery Route',
                        'href' => route('delivery-route.index'),
                        'icon' => 'navigation-2',
                        'permission' => ['delivery-route-read'],
                        'children' => []
                    ],
                ]
            ],


            [
                'name' => 'Offer Setup',
                'href' => '#',
                'icon' => 'shield',
                'permission' => ['coupon-read'],
                'children' => [
                    [
                        'name' => 'Main Coupon',
                        'href' => route('coupon.index'),
                        'icon' => 'shield',
                        'permission' => ['user-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Customer Coupon',
                        'href' => route('customer-coupon.index'),
                        'icon' => 'shield-off',
                        'permission' => ['role-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Top Offer',
                        'href' => route('top-offer.index'),
                        'icon' => 'shopping-bag',
                        'permission' => ['product-read'],
                        'children' => []
                    ],
                ]
            ],

            [
                'name' => 'Product Setup',
                'href' => '#',
                'icon' => 'feather',
                'permission' => ['color-read'],
                'children' => [
                    [
                        'name' => 'tag',
                        'href' => route('tag.index'),
                        'icon' => 'link',
                        'permission' => ['tag-read'],
                        'children' => []
                    ],    
                    [
                        'name' => 'brand',
                        'href' => route('brand.index'),
                        'icon' => 'bar-chart-2',
                        'permission' => ['brand-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Color',
                        'href' => route('color.index'),
                        'icon' => 'feather',
                        'permission' => ['color-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'categories',
                        'href' => route('category.index'),
                        'icon' => 'layout',
                        'permission' => ['category-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'products',
                        'href' => route('product.index'),
                        'icon' => 'shopping-bag',
                        'permission' => ['product-read'],
                        'children' => []
                    ],
                    


                ]
            ],





            [
                'name' => 'Order',
                'href' => route('order.index'),
                'icon' => 'feather',
                'permission' => ['order-read'],
                'children' => []
            ],


            [
                'name' => 'Menu',
                'href' => route('menu.index'),
                'icon' => 'radio',
                'permission' => ['menu-menu'],
                'children' => [
                    [
                        'name' => 'View Menu',
                        'href' => route('menu.index'),
                        'icon' => 'radio',
                        'permission' => ['menu-view'],
                    ],
                    [
                        'name' => 'Create Menu',
                        'href' => route('menu.create'),
                        'icon' => 'radio',
                        'permission' => ['menu-create'],
                    ]
                ]
            ],
            [
                'name' => 'Content Setup',
                'href' => '#',
                'icon' => 'shopping-bag',
                'permission' => ['product-read'],
                'children' => [
                    [
                        'name' => 'featured Section',
                        'href' => route('featured-section.index'),
                        'icon' => 'shopping-bag',
                        'permission' => ['product-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Subscriber List',
                        'href' => route('subscriber.index'),
                        'icon' => 'book',
                        'permission' => ['subscriber-view'],
                        'children' => []
                    ],
                    [
                        'name' => 'Quote Request',
                        'href' => route('quote.index'),
                        'icon' => 'home',
                        'permission' => ['quote-view'],
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
                                'icon' => 'tv',
                                'permission' => ['advertisement-read'],
                            ],
                            [
                                'name' => 'Advertisement Position',
                                'href' => route('position.index'),
                                'icon' => 'tv',
                                'permission' => ['position-read'],
                            ]
                        ]
                    ],

                    [
                        'name' => 'Product Review',
                        'href' => route('review.index'),
                        'icon' => 'shopping-bag',
                        'permission' => ['review-read'],
                        'children' => []
                    ],
        
                    [
                        'name' => 'Product Return',
                        'href' => route('return.index'),
                        'icon' => 'shopping-bag',
                        'permission' => ['return-read'],
                        'children' => []
                    ],
                ]
            ], 
            
        ];
    }
}
