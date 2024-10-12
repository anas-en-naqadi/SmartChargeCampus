<?php

namespace Database\Factories;

use App\Models\Product;
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
            'name' => $this->faker->name(),
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'purchase_price' => $this->faker->randomFloat(2,10, 1000),
            'stock_quantity' => $this->faker->randomNumber(),
            'expiration_date' => $this->faker->date(),
            'updated_product' => $this->faker->boolean ? Product::inRandomOrder()->first()->id : null,
            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,];
    }
}
