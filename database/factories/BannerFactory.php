<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JetBrains\PhpStorm\ArrayShape;

/**
 * @extends Factory
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['image' => "string", 'title' => "string", 'content' => "string", 'btn_text' => "string", 'url' => "string", 'user_id' => "int", 'order' => "int", 'publishStatus' => "bool"])]
    public function definition(): array
    {
        return [
            'image'=>$this->faker->imageUrl(),
            'title'=>$this->faker->jobTitle(),
            'content'=>$this->faker->sentence(),
            'btn_text'=>$this->faker->word(),
            'url'=>$this->faker->url(),
            'user_id'=>1,
            'order'=>1,
            'publishStatus'=>$this->faker->boolean(90)
        ];
    }
}
