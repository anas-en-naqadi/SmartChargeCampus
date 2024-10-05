<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sell>
 */
class SellFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => \App\Models\Client::inRandomOrder()->first()->id,
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,

            'total_price' => fake()->randomFloat(2, 0, 1000),
            'paid_amount' => fake()->randomFloat(2, 0, 1000),
            'remaining_amount' => fake()->randomFloat(2, 0, 1000),
            'status' => fake()->randomElement(["مدفوع", "غير مدفوع", "متبقي"]),
            'check' => fake()->boolean(),
        ];
    }
}
