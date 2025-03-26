<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\seller as Seller;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        $superAdmin =  User::create(
            [
                'name' => "Super Vendor",
                'email' => "nectardigit@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'address' => 'Sundhara-11, New Road',
                'phone' => '9813112182',
            ]
        );

        $superAdmin->assignRole('super admin');

        $admin =  User::create(
            [
                'name' => "Admin",
                'email' => "admin@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'address' => 'Sundhara-11, New Road',
                'phone' => '9813112182',
            ]
        );

        $admin->assignRole('admin');


        DB::table('sellers')->truncate();
        $seller =  Seller::create(
            [
                'name' => "Seller",
                'slug'=>Str::slug("Seller"),
                'email' => "seller@gmail.com",
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'address' => 'Sundhara-11, New Road',
                'phone' => '9813112182',
            ]
        );
        $seller->assignRole('seller');
        Schema::enableForeignKeyConstraints();
    }
}
