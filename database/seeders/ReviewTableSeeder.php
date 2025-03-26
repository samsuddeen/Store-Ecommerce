<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $list=array(
        //     [
        //         'user_id'=>1,
        //         'product_id'=>1,
        //         'rating'=>5,
        //         'message'=>'Good Product'
        //     ],
        //     [
        //         'user_id'=>1,
        //         'product_id'=>1,
        //         'rating'=>3,
        //         'message'=>'Best Product'
        //     ],
        //     [
        //         'user_id'=>1,
        //         'product_id'=>2,
        //         'rating'=>4,
        //         'message'=>'Better Product'
        //     ],
        //     [
        //         'user_id'=>1,
        //         'product_id'=>2,
        //         'rating'=>2,
        //         'message'=>'Not Good'
        //     ]
        // );

        // foreach($list as $data)
        // {
        //     $review=new Review();
        //     $review->fill($data);
        //     $review->save();
        // }
    }
}
