<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        // DB::table('settings')->truncate();
        DB::table('settings')->insert($this->getValues());
        Schema::enableForeignKeyConstraints();
    }

    protected function getValues(): array
    {
        return [
            // [
            //     'key' => 'logo',
            //     'value' => asset('frontend/images/logo.png'),
            //     'type' => 'image',
            // ],
            // [
            //     'key' => 'name',
            //     'value' => 'eshopping',
            //     'type' => 'text',
            // ],
            // [
            //     'key' => 'phone',
            //     'value' => '980123456',
            //     'type' => 'text',
            // ],
            // [
            //     'key' => 'email',
            //     'value' => 'eshopping@gmail.com',
            //     'type' => 'email',
            // ],
            // [
            //     'key' => 'facebook',
            //     'value' => 'https://www.facebook.com/',
            //     'type' => 'url',
            // ],
            // [
            //     'key' => 'twitter',
            //     'value' => 'https://www.twitter.com/',
            //     'type' => 'url',
            // ],
            // [
            //     'key' => 'instagram',
            //     'value' => 'https://www.instagram.com/',
            //     'type' => 'url',
            // ],
            [
                'key' => 'whole_seller_minimum_price',
                'value' => '3000',
                'type' => 'number',
            ],
            [
                'key' => 'wholseller_shipping_charge',
                'value' => '1',
                'type' => 'number',
            ],
             [
                'key' => 'shippping_charge_per_kg',
                'value' => '50',
                'type' => 'number',
            ],
        ];
    }
}
