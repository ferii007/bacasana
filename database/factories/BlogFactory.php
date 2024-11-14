<?php

namespace Database\Factories;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            // 'category_id' => Categories::factory(),
            'slug' => Str::slug(fake()->sentence()),
            'content' => fake()->paragraph(),
            'read_duration' => fake()->numberBetween(5, 30)
        ];
    }
}
