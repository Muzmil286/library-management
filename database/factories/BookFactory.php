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
            'title' => $this->faker->title(),
            'category_id' => rand(1, 50),
            'copies' => rand(1, 8),
            'published' => $this->faker->dateTime(),
            'publisher' => $this->faker->company()
        ];
    }
}
