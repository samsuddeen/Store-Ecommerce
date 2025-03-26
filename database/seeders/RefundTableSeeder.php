<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefundTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('refunds')->insert([
            [
                'return_id'=>1,
                'user_id'=>3,
                'is_new'=>true,
                'status'=>1,
            ],
            [
                'return_id'=>2,
                'user_id'=>3,
                'is_new'=>true,
                'status'=>1,
            ],
        ]);
    }
}
