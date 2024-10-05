<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class ClientFactory extends Factory
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
            'name' => fake()->name(),
            'phone' => fake()->unique()->phoneNumber(),
            'user_id'=>\App\Models\User::inRandomOrder()->first()->id,
            'cni' => fake()->unique()->numerify('##########'), // Generates a unique numeric CNI with 10 digits
            'role'=>fake()->randomElement(["client","transporter"]),
            'total_credit' => fake()->randomFloat(2, 0, 1000),

        ];
    }
}
