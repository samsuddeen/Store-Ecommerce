<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TableDeleteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('categories')->truncate();
        DB::table('brands')->truncate();
        // Delete child records
        DB::table('products_back')->truncate();
        DB::table('product_attributes')->truncate();
        DB::table('product_cancel_reasons')->truncate();
        DB::table('refunds')->truncate();
        DB::table('refund_statuses')->truncate();
        DB::table('refund_orders')->truncate();
        DB::table('direct_refunds')->truncate();
        DB::table('reviews')->truncate();
        DB::table('review_replies')->truncate();
        DB::table('like_reviews')->truncate();
        DB::table('like_review_replies')->truncate();
        DB::table('question_answers')->truncate();


        DB::table('product_colors')->truncate();
        DB::table('product_dangerouses')->truncate();
        DB::table('product_features')->truncate();
        DB::table('product_images')->truncate();
        DB::table('product_seller_orders')->truncate();
        DB::table('product_sizes')->truncate();
        DB::table('product_stocks')->truncate();
        DB::table('product_tag')->truncate();
        DB::table('return_products')->truncate();
        DB::table('top_offer_products')->truncate();
        // Delete parent records
        DB::table('products')->truncate();

        // Delete child records
        DB::table('product_seller_orders')->truncate();
        DB::table('seller_commissions')->truncate();
        DB::table('seller_commission_brands')->truncate();
        DB::table('seller_commission_categories')->truncate();
        DB::table('seller_documents')->truncate();
        DB::table('seller_orders')->truncate();
        DB::table('seller_order_cancels')->truncate();
        DB::table('seller_order_statuses')->truncate();
        DB::table('seller_settings')->truncate();
        // Delete parent records
        DB::table('sellers')->truncate();
        DB::table('advertisements')->truncate();
        DB::table('advertisement_positions')->truncate();
        DB::table('top_categories')->truncate();
        DB::table('categories')->truncate();
        DB::table('tags')->truncate();
        DB::table('brands')->truncate();
        DB::table('sliders')->truncate();

        DB::table('order_assets')->truncate();
        DB::table('order_statuses')->truncate();
        DB::table('order_stocks')->truncate();
        DB::table('refund_orders')->truncate();
        DB::table('return_orders')->truncate();
        DB::table('seller_orders')->truncate();
        DB::table('seller_order_statuses')->truncate();
        DB::table('seller_order_cancels')->truncate();
        DB::table('product_seller_orders')->truncate();
        DB::table('orders')->truncate();

        DB::table('customer_all_used_coupons')->truncate();
        DB::table('customer_coupons')->truncate();
        DB::table('customer_statuses')->truncate();
        DB::table('customers')->truncate();
        DB::table('tbl_customers')->truncate();
        DB::table('products')->truncate();


        // Enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
