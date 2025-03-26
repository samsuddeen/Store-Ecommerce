<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SellerPermission extends Seeder
{
    public function getData()
    {
        $seller_permissions = [

            ['name' => 'seller-product-read', 'guard_name' => 'seller'],
            ['name' => 'seller-product-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-product-own-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-product-create', 'guard_name' => 'seller'],
            ['name' => 'seller-product-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-product-own-delete', 'guard_name' => 'seller'],

            ['name' => 'seller-coupon-read', 'guard_name' => 'seller'],
            ['name' => 'seller-coupon-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-coupon-own-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-coupon-create', 'guard_name' => 'seller'],
            ['name' => 'seller-coupon-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-coupon-own-delete', 'guard_name' => 'seller'],

            //menu
            ['name' => 'seller-menu-view', 'guard_name' => 'seller'],
            ['name' => 'seller-menu-create', 'guard_name' => 'seller'],
            ['name' => 'seller-menu-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-menu-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-menu-update', 'guard_name' => 'seller'],

            //susbcriber
            ['name' => 'seller-subscriber-view', 'guard_name' => 'seller'],
            ['name' => 'seller-subscriber-delete', 'guard_name' => 'seller'],

            //Request for Quotes
            ['name' => 'seller-quote-read', 'guard_name' => 'seller'],
            ['name' => 'seller-quote-delete', 'guard_name' => 'seller'],

            //users
            ['name' => 'seller-user-read', 'guard_name' => 'seller'],
            ['name' => 'seller-user-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-user-create', 'guard_name' => 'seller'],
            ['name' => 'seller-user-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-user-update', 'guard_name' => 'seller'],

            //brand
            ['name' => 'seller-brand-read', 'guard_name' => 'seller'],
            ['name' => 'seller-brand-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-brand-create', 'guard_name' => 'seller'],
            ['name' => 'seller-brand-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-brand-update', 'guard_name' => 'seller'],

            //order
            ['name' => 'seller-order-read', 'guard_name' => 'seller'],
            ['name' => 'seller-order-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-order-create', 'guard_name' => 'seller'],
            ['name' => 'seller-order-update', 'guard_name' => 'seller'],
            ['name' => 'seller-order-delete', 'guard_name' => 'seller'],

            //category
            ['name' => 'seller-category-read', 'guard_name' => 'seller'],
            ['name' => 'seller-category-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-category-create', 'guard_name' => 'seller'],
            ['name' => 'seller-category-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-category-update', 'guard_name' => 'seller'],

            //role
            ['name' => 'seller-role-read', 'guard_name' => 'seller'],
            ['name' => 'seller-role-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-role-create', 'guard_name' => 'seller'],
            ['name' => 'seller-role-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-role-update', 'guard_name' => 'seller'],

            //position
            ['name' => 'seller-position-read', 'guard_name' => 'seller'],
            ['name' => 'seller-position-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-position-create', 'guard_name' => 'seller'],
            ['name' => 'seller-position-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-position-update', 'guard_name' => 'seller'],

            //advertisement
            ['name' => 'seller-advertisement-read', 'guard_name' => 'seller'],
            ['name' => 'seller-advertisement-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-advertisement-create', 'guard_name' => 'seller'],
            ['name' => 'seller-advertisement-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-advertisement-update', 'guard_name' => 'seller'],

            //color
            ['name' => 'seller-color-read', 'guard_name' => 'seller'],
            ['name' => 'seller-color-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-color-create', 'guard_name' => 'seller'],
            ['name' => 'seller-color-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-color-update', 'guard_name' => 'seller'],

            //slider
            ['name' => 'seller-slider-read', 'guard_name' => 'seller'],
            ['name' => 'seller-slider-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-slider-create', 'guard_name' => 'seller'],
            ['name' => 'seller-slider-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-slider-update', 'guard_name' => 'seller'],


            //location
            ['name' => 'seller-location-read', 'guard_name' => 'seller'],
            ['name' => 'seller-location-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-location-create', 'guard_name' => 'seller'],
            ['name' => 'seller-location-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-location-update', 'guard_name' => 'seller'],


            //  Delivery Route
            ['name' => 'seller-delivery-route-read', 'guard_name' => 'seller'],
            ['name' => 'seller-delivery-route-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-delivery-route-create', 'guard_name' => 'seller'],
            ['name' => 'seller-delivery-route-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-delivery-route-update', 'guard_name' => 'seller'],


            // Delivery Charge
            ['name' => 'seller-delivery-charge-read', 'guard_name' => 'seller'],
            ['name' => 'seller-delivery-charge-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-delivery-charge-create', 'guard_name' => 'seller'],
            ['name' => 'seller-delivery-charge-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-delivery-charge-update', 'guard_name' => 'seller'],

            // For Banner
            ['name' => 'seller-banner-read', 'guard_name' => 'seller'],
            ['name' => 'seller-banner-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-banner-create', 'guard_name' => 'seller'],
            ['name' => 'seller-banner-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-banner-update', 'guard_name' => 'seller'],

            // For Tag
            ['name' => 'seller-tag-read', 'guard_name' => 'seller'],
            ['name' => 'seller-tag-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-tag-create', 'guard_name' => 'seller'],
            ['name' => 'seller-tag-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-tag-update', 'guard_name' => 'seller'],

            // For Customer
            ['name' => 'seller-customer-read', 'guard_name' => 'seller'],
            ['name' => 'seller-customer-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-customer-create', 'guard_name' => 'seller'],
            ['name' => 'seller-customer-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-customer-update', 'guard_name' => 'seller'],

            // For Settings
            ['name' => 'seller-setting-read', 'guard_name' => 'seller'],

            // For Review
            ['name' => 'seller-review-read', 'guard_name' => 'seller'],
            ['name' => 'seller-review-delete', 'guard_name' => 'seller'],

            // For Review
            ['name' => 'seller-return-read', 'guard_name' => 'seller'],
            ['name' => 'seller-return-delete', 'guard_name' => 'seller'],

            // For comment
            ['name' => 'seller-comment-read', 'guard_name' => 'seller'],
            ['name' => 'seller-comment-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-comment-reply', 'guard_name' => 'seller'],

            // newly developed
            //sellers
            ['name' => 'seller-sellers-read', 'guard_name' => 'seller'],
            ['name' => 'seller-sellers-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-sellers-own-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-sellers-create', 'guard_name' => 'seller'],
            ['name' => 'seller-sellers-delete', 'guard_name' => 'seller'],


            // for hubs
            ['name' => 'seller-hubs-read', 'guard_name' => 'seller'],
            ['name' => 'seller-hubs-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-hubs-own-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-hubs-create', 'guard_name' => 'seller'],
            ['name' => 'seller-hubs-delete', 'guard_name' => 'seller'],

            // for featured section
            ['name' => 'seller-featured-section-read', 'guard_name' => 'seller'],
            ['name' => 'seller-featured-section-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-featured-section-own-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-featured-section-create', 'guard_name' => 'seller'],
            ['name' => 'seller-featured-section-delete', 'guard_name' => 'seller'],

            // for to offer
            ['name' => 'seller-top-offer-read', 'guard_name' => 'seller'],
            ['name' => 'seller-top-offer-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-top-offer-own-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-top-offer-create', 'guard_name' => 'seller'],
            ['name' => 'seller-top-offer-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-top-offer-delete-own-delete', 'guard_name' => 'seller'],

            // for Web Setting
            ['name' => 'seller-web-setting', 'guard_name' => 'seller'],
            // for Policy Setting
            ['name' => 'seller-policy-setting', 'guard_name' => 'seller'],
            // for Policy Setting
            ['name' => 'seller-term-and-condition-setting', 'guard_name' => 'seller'],

            // for Policy Setting
            ['name' => 'seller-policy-read', 'guard_name' => 'seller'],
            ['name' => 'seller-policy-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-policy-own-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-policy-create', 'guard_name' => 'seller'],
            ['name' => 'seller-policy-delete', 'guard_name' => 'seller'],

            // terms and condition
            ['name' => 'seller-terms-and-condtion-read', 'guard_name' => 'seller'],
            ['name' => 'seller-terms-and-condtion-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-terms-and-condtion-own-edit', 'guard_name' => 'seller'],
            ['name' => 'seller-terms-and-condtion-create', 'guard_name' => 'seller'],
            ['name' => 'seller-terms-and-condtion-delete', 'guard_name' => 'seller'],
            ['name' => 'seller-review', 'guard_name' => 'seller'],
        ];
        return $seller_permissions;
    }
}