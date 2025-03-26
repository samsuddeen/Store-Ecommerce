<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CancellationReasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reasons = [
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'Found cheaper elsewhere',
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'title' => 'Delivery Time is too long',
            ],

            [
                'id' => 3,
                'user_id' => 1,
                'title' => 'Duplicate order',
            ],

            [
                'id' => 4,
                'user_id' => 1,
                'title' => 'Change of delivery address',
            ],

            [
                'id' => 5,
                'user_id' => 1,
                'title' => 'Change payment method',
            ],

            [
                'id' => 6,
                'user_id' => 1,
                'title' => 'forgot to use voucher',
            ],

            [
                'id' => 7,
                'user_id' => 1,
                'title' => 'Change/Combine Order',
            ],


            [
                'id' => 8,
                'user_id' => 1,
                'title' => 'Other Reason',
            ],

        ];

        DB::table('cancellation_reasons')->insert($reasons);
    }
}
