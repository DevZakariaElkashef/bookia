<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
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
            'image' => str_replace(['public', '\\'], ['', '/'], fake()->image('public/images/books', 640, 480, 'sherif')),
            'price' => rand(50, 100),
            'offer' => rand(0, 40),
            'description' => fake()->sentence()
        ];
    }
}
