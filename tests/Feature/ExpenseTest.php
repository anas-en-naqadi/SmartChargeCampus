<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ExpenseTest extends TestCase
{

    use RefreshDatabase;

    private Expense $expense; // Declare client property
    private User $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->expense = Expense::factory()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_can_list_expenses(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/expense'); // Authenticate the client
        $response->assertStatus(200);
    }

    public function test_adding_new_product(): void
    {

        $data =  [
            'name' => 'John nakdi',
            'amount' => 55.34,
        ];;
        $response = $this->actingAs($this->user, 'sanctum')->post('/api/expense', $data);
        // Check if the product was created successfully
        $response->assertStatus(200)
            ->assertJsonStructure(['message', 'expense']);

        // Confirm in the database
        $this->assertDatabaseHas('expenses', [
            'name' => 'John nakdi',
            'amount' => 55.34,
        ]);
    }


    public function test_deleting_existing_product(): void
    {


        $response = $this->actingAs($this->user, 'sanctum')->deleteJson("/api/expense/{$this->expense->id}");
        $response->assertStatus(200)
            ->assertJsonStructure(['message']);
    }
}
