<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' =>$name =  $this->faker->streetName(),
            'slug' =>Str::slug($name)."-".$this->faker->md5(),
            'publishStatus' =>$this->faker->boolean(90),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->numberBetween(1000, 650000),
            'retailPrice' => $this->faker->numberBetween(1000, 65000),
            'currency' => $this->faker->currencyCode(),
            'sku' => $this->faker->md5(),
            'lengthUnit' => 'cm',
            'weightUnit' => 'KG',
            'material' => $this->faker->citySuffix,
            'brand_id' => 1,
            'country_id' => 148,
            'user_id' => 1

        ];
    }
}
