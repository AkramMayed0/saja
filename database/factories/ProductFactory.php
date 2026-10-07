<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);
        return [
            'title' => ucfirst($title),
            'slug' => Str::slug($title),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 5, 100),
            'stock' => fake()->numberBetween(1, 100),
            'author' => fake()->name(),
            'publisher' => fake()->company(),
            'language' => 'Arabic',
            'age_suited' => fake()->randomElement(['All ages', '12+', '18+']),
            'season_type' => fake()->randomElement(['Summer', 'Winter', 'All']),
            'format' => fake()->randomElement(['Hardcover', 'Paperback']),
            'isbn' => fake()->isbn13(),
            'rating' => fake()->randomFloat(1, 3.5, 5.0),
            'reviews_count' => fake()->numberBetween(10, 500),
        ];
    }
}
