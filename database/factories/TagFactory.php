<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->word(),
            'summary' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'image' => $this->faker->imageUrl($width = 640, $height = 480),
            'thumbnail' => $this->faker->imageUrl($width = 200, $height = 200),
            'slug' => $this->faker->slug(10),
            'user_id' => 1,
            'order' => random_int(1, 100),
            'publishStatus' => $this->faker->boolean()
        ];
    }
}
