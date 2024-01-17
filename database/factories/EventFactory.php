<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'description' => fake()->paragraph(2),
            'location' => fake()->streetAddress(),
            'date' => fake()->date(),
            'image_path' => "images/testImage.jpg",
            'category' => fake()->randomElement($array = array('Music', 'Sports', 'Technology', 'Art', 'Food', 'Business', 'Networking', 'Education', 'Health', 'Entertainment')),
            'participation_limit' => fake()->numberBetween(3, 50),
            'creator_user_id' => 0
        ];
    }
}
