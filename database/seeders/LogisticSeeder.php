<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LogisticSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('logistics')->insert(
            [
                [
                    'logistic_name' => 'Bhariya',
                    'slug' => Str::slug('Bhariya'),
                ],
                [
                    'logistic_name' => 'Nepal Can Move',
                    'slug' => Str::slug('Nepal Can Move'),
                ],
                [
                    'logistic_name' => 'Vaccino',
                    'slug' => Str::slug('Vaccino'),
                ],
            ]
        );
    }
}
