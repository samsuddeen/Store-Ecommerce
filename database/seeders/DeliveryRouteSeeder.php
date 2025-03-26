<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DeliveryRouteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('delivery_routes')->insert(
            [
                [
                    'from' => 1,
                    'to' => 2,
                ],
                [
                    'from' => 1,
                    'to' => 3,
                ],
                [
                    'from' => 1,
                    'to' => 4,
                ],
                [
                    'from' => 2,
                    'to' => 1,
                ],
                [
                    'from' => 2,
                    'to' => 3,
                ],
                [
                    'from' => 2,
                    'to' => 4,
                ],
                [
                    'from' => 3,
                    'to' => 1,
                ],
                [
                    'from' => 3,
                    'to' => 2,
                ],
                [
                    'from' => 3,
                    'to' => 4,
                ],
                [
                    'from' => 4,
                    'to' => 1,
                ],
                [
                    'from' => 4,
                    'to' => 2,
                ],
                [
                    'from' => 4,
                    'to' => 3,
                ]
            ]
        );
    }
}
