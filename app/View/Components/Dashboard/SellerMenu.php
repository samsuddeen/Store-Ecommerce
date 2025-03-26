<?php

namespace App\View\Components\Dashboard;

class SellerMenu
{
    function getData()
    {
        
        $sellers = [
            [
                'name' => 'Catalog',
                'href' => 'https://jhigu.store/seller/product',
                'icon' => 'aperture',
                'permission' => ['seller-color-read'],
                'children' => [
                    [
                        'name' => 'products',
                        'href' => route('seller-product.index'),
                        'icon' => 'circle',
                        'permission' => ['seller-product-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Create Products',
                        'href' => route('seller-product.create'),
                        'icon' => 'circle',
                        'permission' => ['seller-product-read'],
                        'children' => []
                    ],
                ]
            ],


            [
                'name' => 'Sales',
                'href' => '#',
                'icon' => 'shopping-cart',
                'permission' => ['seller-order-read'],
                'children' => [
                    [
                        'name' => 'All Orders',
                        'href' => route('seller-order-index'),
                        'icon' => 'circle',
                        'permission' => ['seller-order-read'],
                        'children' => []
                    ],

                    [
                        'name' => 'Transaction',
                        'href' => route('seller-transaction.index'),
                        'icon' => 'circle',
                        'permission' => ['seller-order-read'],
                        'children' => []
                    ],
                    
                ]
                
            ],

            [
                'name' => 'Return Product',
                'href' => '#',
                'icon' => 'corner-down-left',
                'permission' => ['seller-product-read'],
                'children' => [
                    [
                        'name' => 'All return Product',
                        'href' => route('seller.return-order'),
                        'icon' => 'circle',
                        'permission' => ['seller-product-read'],
                        'children' => []
                    ]

                ]
            ],


            [
                'name' => 'Reports',
                'href' => '#',
                'icon' => 'file-text',
                'permission' => ['seller-order-read'],
                'children' => [
                    [
                        'name' => 'Sales',
                        'href' => route('sales-report'),
                        'icon' => 'circle',
                        'permission' => ['seller-order-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Customers',
                        'href' => route('customer.report'),
                        'icon' => 'circle',
                        'permission' => ['seller-order-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'User search Report',
                        'href' => route('seller.user-searchreport'),
                        'icon' => 'circle',
                        'permission' => ['seller-order-read'],
                        'children' => []
                    ],
                    [
                        'name' => 'Products',
                        'href' => route('seller.productreport'),
                        'icon' => 'circle',
                        'permission' => ['seller-order-read'],
                        'children' => []
                    ],
                   
                ]
            ],

            [
                'name' => 'review Section',
                'href' => '#',
                'icon' => 'mail',
                'permission' => ['seller-order-read'],
                'children' => [
                    [
                        'name' => 'All review',
                        'href' => route('seller-review.view'),
                        'icon' => 'circle',
                        'permission' => ['seller-order-read'],
                        'children' => []
                    ],
                ]
            ],


            [
                'name' => 'Menu',
                'href' => route('menu.index'),
                'icon' => 'radio',
                'permission' => ['seller-menu-menu'],
                'children' => [
                    [
                        'name' => 'View Menu',
                        'href' => route('menu.index'),
                        'icon' => 'radio',
                        'permission' => ['seller-menu-view'],
                    ],
                    [
                        'name' => 'Create Menu',
                        'href' => route('menu.create'),
                        'icon' => 'radio',
                        'permission' => ['seller-menu-create'],
                    ]
                ]
            ],

            [
                'name' => 'Comments',
                'href' => '#',
                'icon' => 'message-square',
                'permission' => ['seller-order-read'],
                'children' => [
                    [
                        'name' => 'new Comments',
                        'href' => route('seller.comment'),
                        'icon' => 'circle',
                        'permission' => ['seller-order-read'],
                        'children' => []
                    ],

                   
                ]
            ],


            [
                'name' => 'Media Manager',
                'href' => route('seller-media.index'),
                'icon' => 'camera',
                'permission' => ['seller-product-read'],
                'children' => []
            ],

            [
                'name' => 'Setting',
                'href' => route('seller-setting.index'),
                'icon' => 'settings',
                'permission' => ['seller-product-read'],
                'children' => []
            ],
        ];
        return $sellers;
    }
}
