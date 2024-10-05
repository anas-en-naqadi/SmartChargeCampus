<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class PurchaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transporter_id' => \App\Models\Client::inRandomOrder()->where("role","transporter")->first()->id,
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'product_id' => \App\Models\Product::inRandomOrder()->first()->id,
        ];
    }
}
