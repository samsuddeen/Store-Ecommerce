<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PermissionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([

            SettingSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            RoleHasPermissionTableSeeder::class,
            CountrySeeder::class,
            AuthSeeder::class,
            ReviewTableSeeder::class,
            RewardPointTableSeeder::class

        ]);
    }
}
