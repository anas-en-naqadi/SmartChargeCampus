<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Client;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private int $category_id;
    private Client $transporter;
    private Product $product;
    private Purchase $purchase;
    protected function setUp(): void
    {
        Parent::setUp();

        $this->user = User::factory()->create();
        $this->transporter = Client::factory()->create(['role' => 'transporter']);

        $this->category_id = Category::factory()->create()->id;
        $this->product = Product::factory()->create();
        $this->purchase = Purchase::factory()->create();
    }
    /**
     * A basic feature test example.
     */
    public function test_getting_all_user_purchases(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/purchase');

        $response->assertStatus(200);
    }

    public function test_storing_a_purchase_with_new_product(): void
    {
        $data = $this->getPurchaseData();

        $response = $this->actingAs($this->user, 'sanctum')->post('/api/purchase', $data);

        $response->assertStatus(200)->assertJsonStructure(['message', 'purchase']);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'category_id' => $this->category_id,
        ]);
    }

    public function test_storing_a_purchase_with_existing_product(): void
    {

        $data = $this->getPurchaseData($this->product->id);

        $response = $this->actingAs($this->user, 'sanctum')->post('/api/existing-purchase', $data);

        $response->assertStatus(200)->assertJsonStructure(['message', 'purchase']);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'category_id' => $this->category_id,
            'updated_product' => $this->product->id
        ]);
    }

    public function test_updating_a_purchase_with_new_product(): void
    {
        $data = $this->getPurchaseData();
        $data['stock_quantity'] = 200;
        $response = $this->actingAs($this->user, 'sanctum')->put("/api/purchase/{$this->purchase->id}", $data);

        $response->assertStatus(200)->assertJsonStructure(['message', 'purchase']);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'category_id' => $this->category_id,
            'stock_quantity' => 200
        ]);
    }

    public function test_updating_a_purchase_with_existing_product(): void
    {
        $product = Product::factory()->create();
        $data = $this->getPurchaseData($product->id);
        $data['purchase_price'] = 100;
        $response = $this->actingAs($this->user, 'sanctum')->put("/api/purchase/{$this->purchase->id}", $data);

        $response->assertStatus(200)->assertJsonStructure(['message', 'purchase']);
        $this->assertDatabaseHas('purchases', [
            'user_id' => $this->user->id,
            'category_id' => $this->category_id,
            'updated_product' => $product->id,
            'purchase_price' => 100

        ]);
    }

    public function test_destroy_a_purchase(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->delete("/api/purchase/{$this->purchase->id}");
        $response->assertStatus(200)->assertJsonStructure(['message']);
        $this->assertSoftDeleted('purchases', [
            'id' => $this->purchase->id,
        ]);
    }

    private function getPurchaseData(int $product_id = null): array
    {
        return [
            'name' => 'Premium Biscuits',
            'user_id' => $this->user->id,
            'purchase_price' => 49.99,
            'category_id' => $this->category_id,
            'stock_quantity' => 150,
            'expiration_date' => '2025-12-31',
            'transporter_id' => $this->transporter->id,
            'updated_product' => $product_id,
        ];
    }
}
