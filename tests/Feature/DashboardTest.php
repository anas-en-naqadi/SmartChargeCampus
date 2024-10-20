<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $user; // Declare user property

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * A basic feature test example.
     */
    public function test_weekly_sales_data(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/weekly-sales');

        $response->assertStatus(200)->assertJsonStructure(['labels', 'data', 'colors', 'borderColors']);
    }

    public function test_dashboard_data(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/dashboard-data');

        $response->assertStatus(200);
    }

    public function test_stock_by_category_data(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/stock-by-category');

        $response->assertStatus(200)->assertJsonStructure(['labels', 'datasets']);
    }

    public function test_monthly_sales_data(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/monthly-sales');

        $response->assertStatus(200)->assertJsonStructure(['labels', 'data', 'colors', 'borderColors']);
    }
    public function test_monthly_remaining_data(): void
    {
        $response = $this->actingAs($this->user, 'sanctum')->get('/api/month-remaining');

        $response->assertStatus(200)->assertJsonStructure(['labels', 'data', 'colors', 'borderColors']);
    }
}
