<?php

namespace Database\Seeders;

use App\Models\Advertisement;
use App\Models\ProductImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productImage=ProductImage::get();
        foreach($productImage as $data){
            $modifiedURL = str_replace('https://glasspipenepal.com', 'https://glasspipenepal.com/', $data->image);
                $data->image=$modifiedURL;
                $data->save();
        }
        dd('success');
        Advertisement::factory()->count(20)->create();
    }
}
