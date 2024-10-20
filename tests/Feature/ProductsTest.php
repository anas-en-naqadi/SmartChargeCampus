<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductsTest extends TestCase
{

    use RefreshDatabase;

    protected User $user; // Declare user property

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Category::factory()->count(5)->create(); // Create a user for all tests
    }

    /**
     * A basic feature test example.
     */
    public function test_can_list_products(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/product'); // Authenticate the user
        $response->assertStatus(200);
    }

    public function test_adding_new_product(): void
    {
        $category_id = Category::first()->id;
        $image = UploadedFile::fake()->image('product.jpg');
        $base64Image = base64_encode(file_get_contents($image->getPathname()));
        $data = $this->getProductData($this->user->id, $category_id, $image);
        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/product', $data);
        // Check if the product was created successfully
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'product']);

        // Confirm in the database
        $this->assertDatabaseHas('products', [
            'name' => 'biscuit',
            'user_id' => $this->user->id,
            'category_id' => $category_id,
        ]);
    }

    public function test_updating_existing_product(): void
    {

        $category_id = Category::first()->id;

        $product = Product::factory()->create(['user_id' => $this->user->id]);
        $image = UploadedFile::fake()->image('product.jpg');
        $data = $this->getProductData($this->user->id, $category_id, $image);
        $data['selling_price'] = 500.23;



        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/product/{$product->id}", $data);
        // Check if the product was created successfully
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'product']);

        // Confirm in the database
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'biscuit',
            'user_id' => $this->user->id,
            'selling_price' => 500.23,
            'category_id' => $category_id,
        ]);
    }

    public function test_deleting_existing_product(): void
    {

        $product = Product::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/product/{$product->id}");
        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }


    protected function getProductData(int $userId, int $categoryId, UploadedFile $image): array
    {
        return [
            'name' => 'biscuit',
            'stock_quantity' => 34,
            'purchase_price' => 43.28,
            'selling_price' => 238.23,
            'expiration_date' => null,
            'user_id' => $userId,
            'category_id' => $categoryId,
            'image' => 'data:image/jpeg;base64,' . base64_encode(file_get_contents($image->getPathname())),
        ];
    }
}
