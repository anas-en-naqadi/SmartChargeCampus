<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class SellProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => fake()->randomNumber(),
            'product_id' => \App\Models\Product::inRandomOrder()->first()->id,
            'sell_id' => \App\Models\Sell::inRandomOrder()->first()->id,

        ];
    }
}
