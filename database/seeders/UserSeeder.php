<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $products=Product::get();
        // foreach($products as $data){
        //     $data->meta_title=$data->name;
        //     $data->meta_keywords="Glass pipes , Smoking accessories , Nepali glassware , Handcrafted pipes , Unique smoking products , Online ,smoke shop , Premium smoking gear , Tobacco pipes , Himalayan glass pipes , Nepali craftsmanship";
        //     $data->meta_description="Explore a wide selection of intricately crafted glass pipes and accessories at Glass Pipe Nepal. Discover unique designs and high-quality materials perfect for any smoking enthusiast. Shop now for premium smoking experiences";
        //     $data->save();
        // }
        // dd('Success');
        User::factory()->count(20)->create();
    }
}
