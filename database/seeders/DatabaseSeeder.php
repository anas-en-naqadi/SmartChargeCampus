<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // \App\Models\User::query()->delete();
        // \App\Models\Client::query()->delete();

        // \App\Models\Category::query()->delete();
        // \App\Models\Product::query()->delete();
        // \App\Models\Sell::query()->delete();

        // \App\Models\Purchase::query()->delete();
        // \App\Models\Expense::query()->delete();
        // \App\Models\SellProduct::query()->delete();
// limits

         \App\Models\User::factory(1)->create();
      












        // $this->call([
        //     CategorySeeder::class,
        //     ProductSeeder::class,
        //     // Other seeders...
        // ]);
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
