<?php

namespace Database\Factories;

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
            'team_id' => 1,
            'name' => fake()->unique()->company(),
            'quantity' => fake()->numberBetween(0, 100),
            'price' => fake()->numberBetween(1, 1000),

        ];
    }
}
