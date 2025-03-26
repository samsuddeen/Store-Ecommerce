<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Advertisement>
 */
class AdvertisementFactory extends Factory
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
            'url' => $this->faker->url(),
            'image' => 'https://images.unsplash.com/photo-1557858310-9052820906f7',
            'mobile_image' => 'https://images.unsplash.com/photo-1557858310-9052820906f7',
            'size' => random_int(1, 12)
        ];
    }
}
