<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('ar_SA');
        return [
            'amount' => fake()->randomFloat(2, 0, 1000),
            'user_id'=>\App\Models\User::inRandomOrder()->first()->id,
            'name' => fake()->name()        ];
    }
}
