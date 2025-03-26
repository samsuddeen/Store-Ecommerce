<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProvinceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'eng_name' => 'Province 1',
                'status' => 'active',
            ],
            [
                'eng_name' => 'Province 2',
                'status' => 'active',
            ],
            [
                'eng_name' => 'Bagmati',
                'status' => 'active',
            ],
            [
                'eng_name' => 'Gandaki',
                'status' => 'active',
            ],
            [
                'eng_name' => 'Province 5',
                'status' => 'active',
            ],
            [
                'eng_name' => 'Karnali',
                'status' => 'active',
            ],
            [
                'eng_name' => 'Province 7',
                'status' => 'active',
            ]
        ];
        \DB::table('provinces')->insert($data);
    }
}
