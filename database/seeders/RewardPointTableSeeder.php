<?php

namespace Database\Seeders;

use App\Models\RewardPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RewardPointTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[
            'point'=>10,
            'value'=>1,
            'currency'=>'nrs',
            'currency_value'=>1
        ];

        RewardPoint::insert($data);
    }
}
