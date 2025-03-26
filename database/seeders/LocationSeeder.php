<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('locations')->insert(
            [
                [
                    'title' => 'Tinkune',
                    'slug' => Str::slug('Tinkune'),
                ],
                [
                    'title' => 'Samakhusi',
                    'slug' => Str::slug('Samakhusi'),
                ],
                [
                    'title' => 'Balaju',
                    'slug' => Str::slug('Balaju'),
                ],
                [
                    'title' => 'Kalanki',
                    'slug' => Str::slug('Kalanki'),
                ],
                [
                    'title' => 'Sundhara',
                    'slug' => Str::slug('Sundhara'),
                ],
                [
                    'title' => 'Koteshowr',
                    'slug' => Str::slug('Koteshowr'),
                ],
                [
                    'title' => 'Lainchaur',
                    'slug' => Str::slug('Lainchaur'),
                ],
                [
                    'title' => 'Thamel',
                    'slug' => Str::slug('Thamel'),
                ],
                [
                    'title' => 'Ratna Park',
                    'slug' => Str::slug('Ratna Park'),
                ]
            ]
        );
    }
}
