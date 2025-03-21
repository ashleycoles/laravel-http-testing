<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->paragraphs(4, true),
            'price' => fake()->randomFloat(2),
            'stock' => rand(0, 100),
            // For one-to-many relationships, we can just set the foreign id to use
            // the factory of the relation
            // Now when we use the product factory, it will automatically make its
            // own category
            'category_id' => Category::factory()
        ];
    }
}
