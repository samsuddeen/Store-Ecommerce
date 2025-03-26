<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 20;
        Schema::disableForeignKeyConstraints();
        Category::factory()->count($count)->create();
        Category::get()->each(fn ($item, $key) => $key != 0 ? $item->update(['parent_id' => random_int(1, $key)]) : null);
        Category::fixTree();
        Schema::enableForeignKeyConstraints();
    }
}
