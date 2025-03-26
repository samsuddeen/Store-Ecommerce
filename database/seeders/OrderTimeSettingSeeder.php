<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderTimeSetting;

class OrderTimeSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        OrderTimeSetting::truncate();
        $settings = [
            [
                'day' => 'Sunday',
                'start_time' => '09:00',
                'end_time' => '17:00'
            ],
            [
                'day' => 'Monday',
                'start_time' => '09:00',
                'end_time' => '17:00'
            ],
            [
                'day' => 'Tuesday',
                'start_time' => '09:00',
                'end_time' => '17:00'
            ],
            [
                'day' => 'Wednesday',
                'start_time' => '09:00',
                'end_time' => '17:00'
            ],
            [
                'day' => 'Thursday',
                'start_time' => '09:00',
                'end_time' => '17:00'
            ],
            [
                'day' => 'Friday',
                'start_time' => '09:00',
                'end_time' => '14:00'
            ],
            [
                'day' => 'Saturday',
                'start_time' => '09:00',
                'end_time' => '17:00',
                'day_off' => true
            ]
        ];

        foreach($settings as $setting)
        {
            OrderTimeSetting::create($setting);
        }

    }
}
