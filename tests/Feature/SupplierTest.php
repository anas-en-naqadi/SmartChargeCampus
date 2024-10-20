<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierTest extends TestCase
{


    use RefreshDatabase;

    private Client $supplier; // Declare client property
    private User $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->supplier = Client::factory()->create(['role'=>'transporter']);
    }

    /**
     * A basic feature test example.
     */
    public function test_can_list_suppliers(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/supplier'); // Authenticate the client
        $response->assertStatus(200);
    }

    public function test_adding_new_product(): void
    {

        $data = $this->getClientData();
        $response = $this->actingAs($this->user, 'sanctum')->post('/api/supplier', $data);
        // Check if the product was created successfully
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'supplier']);

        // Confirm in the database
        $this->assertDatabaseHas('clients', [
            'name' => 'John nakdi',
            'total_credit' => 1000.00,
        ]);
    }

    public function test_updating_existing_product(): void
    {

        $data = $this->getClientData(500.23);



        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/supplier/{$this->supplier->id}", $data);
        // Check if the product was created successfully
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'supplier']);

        // Confirm in the database
        $this->assertDatabaseHas('clients', [
            'id' => $this->supplier->id,
            'total_credit' => 500.23,
        ]);
    }

    public function test_deleting_existing_product(): void
    {


        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/supplier/{$this->supplier->id}");
        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }


    protected function getClientData(float $total_credit = 1000.00): array
    {
        return  [
            'name' => 'John nakdi',
            'total_credit' => $total_credit,
            'phone' => '123-456-7890',
            'cni' => 'CNI123456',
        ];
    }
}
