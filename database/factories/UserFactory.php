<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $this->faker = \Faker\Factory::create('ar_SA');
        // return [
        //     'name' => 'Admin',
        //     'email' => 'campus@admin.com',
        //     'password' => Hash::make('campus@admin.com'),
        //     'role' => 'admin'
        // ];
         return [
            'name' => 'Anas Enq',
            'email' => 'anas@student.com',
            'password' => Hash::make('anas@student.com'),
            'role' => 'student'
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
