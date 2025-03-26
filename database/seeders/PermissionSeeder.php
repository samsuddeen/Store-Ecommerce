<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SellerPermission;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Permission::truncate();
        Permission::query()->insert(self::getPermissionData());
        Schema::enableForeignKeyConstraints();
    }

    private function getPermissionData(): array
    {
        $super_permissions =  [

            //products
            ['name' => 'product-read', 'guard_name' => 'web'],
            ['name' => 'product-edit', 'guard_name' => 'web'],
            ['name' => 'product-own-edit', 'guard_name' => 'web'],
            ['name' => 'product-create', 'guard_name' => 'web'],
            ['name' => 'product-delete', 'guard_name' => 'web'],
            ['name' => 'product-own-delete', 'guard_name' => 'web'],

            ['name' => 'coupon-read', 'guard_name' => 'web'],
            ['name' => 'coupon-edit', 'guard_name' => 'web'],
            ['name' => 'coupon-own-edit', 'guard_name' => 'web'],
            ['name' => 'coupon-create', 'guard_name' => 'web'],
            ['name' => 'coupon-delete', 'guard_name' => 'web'],
            ['name' => 'coupon-own-delete', 'guard_name' => 'web'],

            //menu
            ['name' => 'menu-view', 'guard_name' => 'web'],
            ['name' => 'menu-create', 'guard_name' => 'web'],
            ['name' => 'menu-edit', 'guard_name' => 'web'],
            ['name' => 'menu-delete', 'guard_name' => 'web'],
            ['name' => 'menu-update', 'guard_name' => 'web'],

            //susbcriber
            ['name' => 'subscriber-view', 'guard_name' => 'web'],
            ['name' => 'subscriber-delete', 'guard_name' => 'web'],

            //Request for Quotes
            ['name' => 'quote-read', 'guard_name' => 'web'],
            ['name' => 'quote-delete', 'guard_name' => 'web'],

            //users
            ['name' => 'user-read', 'guard_name' => 'web'],
            ['name' => 'user-edit', 'guard_name' => 'web'],
            ['name' => 'user-create', 'guard_name' => 'web'],
            ['name' => 'user-delete', 'guard_name' => 'web'],
            ['name' => 'user-update', 'guard_name' => 'web'],

            //Tasks
            ['name' => 'task-read', 'guard_name' => 'web'],
            ['name' => 'task-edit', 'guard_name' => 'web'],
            ['name' => 'task-create', 'guard_name' => 'web'],
            ['name' => 'task-delete', 'guard_name' => 'web'],
            ['name' => 'task-update', 'guard_name' => 'web'],


            //Task Actions
            ['name' => 'task-action-read', 'guard_name' => 'web'],
            ['name' => 'task-action-edit', 'guard_name' => 'web'],
            ['name' => 'task-action-create', 'guard_name' => 'web'],
            ['name' => 'task-action-delete', 'guard_name' => 'web'],
            ['name' => 'task-action-update', 'guard_name' => 'web'],

            //brand
            ['name' => 'brand-read', 'guard_name' => 'web'],
            ['name' => 'brand-edit', 'guard_name' => 'web'],
            ['name' => 'brand-create', 'guard_name' => 'web'],
            ['name' => 'brand-delete', 'guard_name' => 'web'],
            ['name' => 'brand-update', 'guard_name' => 'web'],

            //order
            ['name' => 'order-read', 'guard_name' => 'web'],
            ['name' => 'order-edit', 'guard_name' => 'web'],
            ['name' => 'order-create', 'guard_name' => 'web'],
            ['name' => 'order-update', 'guard_name' => 'web'],
            ['name' => 'order-delete', 'guard_name' => 'web'],



            // transactions
            ['name' => 'transaction-read', 'guard_name' => 'web'],
            ['name' => 'transaction-edit', 'guard_name' => 'web'],
            ['name' => 'transaction-create', 'guard_name' => 'web'],
            ['name' => 'transaction-update', 'guard_name' => 'web'],
            ['name' => 'transaction-delete', 'guard_name' => 'web'],

            //category
            ['name' => 'category-read', 'guard_name' => 'web'],
            ['name' => 'category-edit', 'guard_name' => 'web'],
            ['name' => 'category-create', 'guard_name' => 'web'],
            ['name' => 'category-delete', 'guard_name' => 'web'],
            ['name' => 'category-update', 'guard_name' => 'web'],

            //role
            ['name' => 'role-read', 'guard_name' => 'web'],
            ['name' => 'role-edit', 'guard_name' => 'web'],
            ['name' => 'role-create', 'guard_name' => 'web'],
            ['name' => 'role-delete', 'guard_name' => 'web'],
            ['name' => 'role-update', 'guard_name' => 'web'],

            //position
            ['name' => 'position-read', 'guard_name' => 'web'],
            ['name' => 'position-edit', 'guard_name' => 'web'],
            ['name' => 'position-create', 'guard_name' => 'web'],
            ['name' => 'position-delete', 'guard_name' => 'web'],
            ['name' => 'position-update', 'guard_name' => 'web'],

            //advertisement
            ['name' => 'advertisement-read', 'guard_name' => 'web'],
            ['name' => 'advertisement-edit', 'guard_name' => 'web'],
            ['name' => 'advertisement-create', 'guard_name' => 'web'],
            ['name' => 'advertisement-delete', 'guard_name' => 'web'],
            ['name' => 'advertisement-update', 'guard_name' => 'web'],

            //color
            ['name' => 'color-read', 'guard_name' => 'web'],
            ['name' => 'color-edit', 'guard_name' => 'web'],
            ['name' => 'color-create', 'guard_name' => 'web'],
            ['name' => 'color-delete', 'guard_name' => 'web'],
            ['name' => 'color-update', 'guard_name' => 'web'],

            //slider
            ['name' => 'slider-read', 'guard_name' => 'web'],
            ['name' => 'slider-edit', 'guard_name' => 'web'],
            ['name' => 'slider-create', 'guard_name' => 'web'],
            ['name' => 'slider-delete', 'guard_name' => 'web'],
            ['name' => 'slider-update', 'guard_name' => 'web'],


            //location
            ['name' => 'location-read', 'guard_name' => 'web'],
            ['name' => 'location-edit', 'guard_name' => 'web'],
            ['name' => 'location-create', 'guard_name' => 'web'],
            ['name' => 'location-delete', 'guard_name' => 'web'],
            ['name' => 'location-update', 'guard_name' => 'web'],


            //  Delivery Route
            ['name' => 'delivery-route-read', 'guard_name' => 'web'],
            ['name' => 'delivery-route-edit', 'guard_name' => 'web'],
            ['name' => 'delivery-route-create', 'guard_name' => 'web'],
            ['name' => 'delivery-route-delete', 'guard_name' => 'web'],
            ['name' => 'delivery-route-update', 'guard_name' => 'web'],


            // Delivery Charge
            ['name' => 'delivery-charge-read', 'guard_name' => 'web'],
            ['name' => 'delivery-charge-edit', 'guard_name' => 'web'],
            ['name' => 'delivery-charge-create', 'guard_name' => 'web'],
            ['name' => 'delivery-charge-delete', 'guard_name' => 'web'],
            ['name' => 'delivery-charge-update', 'guard_name' => 'web'],

            // For Banner
            ['name' => 'banner-read', 'guard_name' => 'web'],
            ['name' => 'banner-edit', 'guard_name' => 'web'],
            ['name' => 'banner-create', 'guard_name' => 'web'],
            ['name' => 'banner-delete', 'guard_name' => 'web'],
            ['name' => 'banner-update', 'guard_name' => 'web'],

            // For Tag
            ['name' => 'tag-read', 'guard_name' => 'web'],
            ['name' => 'tag-edit', 'guard_name' => 'web'],
            ['name' => 'tag-create', 'guard_name' => 'web'],
            ['name' => 'tag-delete', 'guard_name' => 'web'],
            ['name' => 'tag-update', 'guard_name' => 'web'],

            // For Customer
            ['name' => 'customer-read', 'guard_name' => 'web'],
            ['name' => 'customer-edit', 'guard_name' => 'web'],
            ['name' => 'customer-create', 'guard_name' => 'web'],
            ['name' => 'customer-delete', 'guard_name' => 'web'],
            ['name' => 'customer-update', 'guard_name' => 'web'],

            // For Settings
            ['name' => 'setting-read', 'guard_name' => 'web'],

            // For Review
            ['name' => 'review-read', 'guard_name' => 'web'],
            ['name' => 'review-delete', 'guard_name' => 'web'],

            // For Review
            ['name' => 'return-read', 'guard_name' => 'web'],
            ['name' => 'return-delete', 'guard_name' => 'web'],

            // For comment
            ['name' => 'comment-read', 'guard_name' => 'web'],
            ['name' => 'comment-delete', 'guard_name' => 'web'],
            ['name' => 'comment-reply', 'guard_name' => 'web'],

            // newly developed
            //sellers
            ['name' => 'sellers-read', 'guard_name' => 'web'],
            ['name' => 'sellers-edit', 'guard_name' => 'web'],
            ['name' => 'sellers-own-edit', 'guard_name' => 'web'],
            ['name' => 'sellers-create', 'guard_name' => 'web'],
            ['name' => 'sellers-delete', 'guard_name' => 'web'],


            // for hubs
            ['name' => 'hubs-read', 'guard_name' => 'web'],
            ['name' => 'hubs-edit', 'guard_name' => 'web'],
            ['name' => 'hubs-own-edit', 'guard_name' => 'web'],
            ['name' => 'hubs-create', 'guard_name' => 'web'],
            ['name' => 'hubs-delete', 'guard_name' => 'web'],

            // for featured section
            ['name' => 'featured-section-read', 'guard_name' => 'web'],
            ['name' => 'featured-section-edit', 'guard_name' => 'web'],
            ['name' => 'featured-section-own-edit', 'guard_name' => 'web'],
            ['name' => 'featured-section-create', 'guard_name' => 'web'],
            ['name' => 'featured-section-delete', 'guard_name' => 'web'],

            // for to offer
            ['name' => 'top-offer-read', 'guard_name' => 'web'],
            ['name' => 'top-offer-edit', 'guard_name' => 'web'],
            ['name' => 'top-offer-own-edit', 'guard_name' => 'web'],
            ['name' => 'top-offer-create', 'guard_name' => 'web'],
            ['name' => 'top-offer-delete', 'guard_name' => 'web'],
            ['name' => 'top-offer-delete-own-delete', 'guard_name' => 'web'],

            // for Web Setting
            ['name' => 'web-setting', 'guard_name' => 'web'],
            // for Policy Setting
            ['name' => 'policy-setting', 'guard_name' => 'web'],
            // for Policy Setting
            ['name' => 'term-and-condition-setting', 'guard_name' => 'web'],

            // for Policy Setting
            ['name' => 'policy-read', 'guard_name' => 'web'],
            ['name' => 'policy-edit', 'guard_name' => 'web'],
            ['name' => 'policy-own-edit', 'guard_name' => 'web'],
            ['name' => 'policy-create', 'guard_name' => 'web'],
            ['name' => 'policy-delete', 'guard_name' => 'web'],

            // terms and condition
            ['name' => 'terms-and-condtion-read', 'guard_name' => 'web'],
            ['name' => 'terms-and-condtion-edit', 'guard_name' => 'web'],
            ['name' => 'terms-and-condtion-own-edit', 'guard_name' => 'web'],
            ['name' => 'terms-and-condtion-create', 'guard_name' => 'web'],
            ['name' => 'terms-and-condtion-delete', 'guard_name' => 'web'],



            //  trash
            ['name' => 'trash-read', 'guard_name' => 'web'],
            ['name' => 'trash-edit', 'guard_name' => 'web'],
            ['name' => 'trash-create', 'guard_name' => 'web'],
            ['name' => 'trash-update', 'guard_name' => 'web'],
            ['name' => 'trash-delete', 'guard_name' => 'web'],



            //  for smtp setup
            ['name' => 'smtp-read', 'guard_name' => 'web'],
            ['name' => 'smtp-edit', 'guard_name' => 'web'],
            ['name' => 'smtp-create', 'guard_name' => 'web'],
            ['name' => 'smtp-update', 'guard_name' => 'web'],
            ['name' => 'smtp-delete', 'guard_name' => 'web'],




            //  for Payout setting
            ['name' => 'payout-read', 'guard_name' => 'web'],
            ['name' => 'payout-edit', 'guard_name' => 'web'],
            ['name' => 'payout-create', 'guard_name' => 'web'],
            ['name' => 'payout-update', 'guard_name' => 'web'],
            ['name' => 'payout-delete', 'guard_name' => 'web'],



            // for media manager
            ['name' => 'media-management', 'guard_name' => 'web'],
        ];

        $seller_permissions = $this->getSellerPermissions();
        return array_merge($super_permissions, $seller_permissions);
    }

    private function getSellerPermissions()
    {
        $seller_permissions = [
            //sellers
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

            // for media manager
            ['name' => 'seller-media-management', 'guard_name' => 'web'],
        ];
        return $seller_permissions;
    }
}
