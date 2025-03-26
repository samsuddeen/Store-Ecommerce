<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Schema::disableForeignKeyConstraints();
        DB::table('menu_categories')->truncate();
        DB::table('menu_categories')->insert([
           [
            'name'=>'Header Category',
            'slug'=>Str::slug('Header Category'),
           ], 
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
