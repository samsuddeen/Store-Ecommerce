<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PaymentMethodSeeder extends Seeder
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
        DB::table('payment_methods')->truncate();
        DB::table('payment_methods')->insert([
            [
                'user_id'=>1,
                'type'=>1,
                'title'=>'eSewa',
                'slug'=>Str::slug('eSewa'),
                '_token'=>Crypt::encryptString(12345678910),
                'is_default'=>true,
                'status'=>'1',
            ],
            [
                'user_id'=>1,
                'type'=>2,
                'title'=>'Khalti',
                'slug'=>Str::slug('Khalti'),
                '_token'=>Crypt::encryptString(12345678910),
                'is_default'=>0,
                'status'=>'1',
            ],

            // [
            //     'user_id'=>1,
            //     'type'=>1,
            //     'title'=>'IME PAY',
            //     'slug'=>Str::slug('IME PAY'),
            //     '_token'=>Crypt::encryptString(12345678910),
            //     'is_default'=>0,
            //     'status'=>'1',
            // ],

            [
                'user_id'=>1,
                'type'=>3,
                'title'=>'COD',
                'slug'=>Str::slug('cash on delivery'),
                '_token'=>Crypt::encryptString(12345678910),
                'is_default'=>0,
                'status'=>'1',
            ],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}
