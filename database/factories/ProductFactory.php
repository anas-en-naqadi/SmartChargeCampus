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
        $this->faker = \Faker\Factory::create('ar_SA');
        return [
            'name' => $this->faker->name(),
            'user_id' => \App\Models\User::inRandomOrder()->first()->id,
            'selling_price' => $this->faker->randomFloat(2, 10, 1000),
            'purchase_price' => $this->faker->randomFloat(2,10, 1000),
            'stock_quantity' => $this->faker->randomNumber(),
            'expiration_date' => $this->faker->date(),

            'category_id' => \App\Models\Category::inRandomOrder()->first()->id,
            'image' => $this->faker->imageUrl(),
        ];
    }
}
