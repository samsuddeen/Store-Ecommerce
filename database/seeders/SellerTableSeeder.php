<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SellerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('users')->insert([
            [
                'name'=>'Seller 1',
                'email'=>'seller1@gmail.com',
                'password'=>Hash::make('password'),
            ],
            [
                'name'=>'Seller 2',
                'email'=>'seller2@gmail.com',
                'password'=>Hash::make('password'),
            ],
        ]);
        DB::table('sellers')->insert([
            [
                'name'=>'Seller 1',
                'email'=>'seller1@gmail.com',
                'phone'=>'9856352145',
                'address'=>'Sukhad Kailali',
                'status'=>1,
                'photo'=>null,
                'province'=>"Testing Province",
                'province_id'=>'1',
                'district'=>'Testing District',
                'district_id'=>'1',
                'area'=>'Testing Area',
                'zip'=>1234,
                'company_name'=>null,
                'password'=>Hash::make('password'),
                'is_new'=>false,
            ],
            [
                'name'=>'Seller 2',
                'email'=>'seller2@gmail.com',
                'phone'=>'98563521456',
                'address'=>'Sukhad Kailali',
                'status'=>1,
                'photo'=>null,
                'province'=>"Testing Province",
                'province_id'=>'1',
                'district'=>'Testing District',
                'district_id'=>'1',
                'area'=>'Testing Area',
                'zip'=>1234,
                'company_name'=>null,
                'password'=>Hash::make('password'),
                'is_new'=>false,
            ]
        ]);
    }
}
