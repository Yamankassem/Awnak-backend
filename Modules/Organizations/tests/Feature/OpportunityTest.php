<?php

namespace Modules\Organizations\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Organizations\Models\Organization;
use Tests\TestCase;

class OpportunityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Create a new opportunity.
     *
     * This test ensures that the API can successfully create
     * a new opportunity record when valid data is provided.
     * It checks:
     * - Response status is 201 (Created).
     * - JSON structure contains all expected fields.
     * - Database actually stores the new opportunity.
     */
    public function test_it_can_create_an_opportunity()
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        // Create an organization first
        $organization = Organization::factory()->create();

        $response = $this->postJson('/api/opportunities', [
            'title'       => 'Scholarship Program',
            'description' => 'A scholarship opportunity for students',
            'type'        => 'Scholarship', // required field
            'start_date'  => now()->toDateString(), // today
            'end_date'    => now()->addDays(30)->toDateString(), // deadline after 30 days
            'organization_id'=> $organization->id,
        ]);


        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'title',
                    'description',
                    'type',
                    'start_date',
                    'end_date',
                    'created_at',
                    'updated_at',
                    'organization' => [ 'id', 'license_number', 'type', 'bio', 'website', ],
                ]
            ]);

        $this->assertDatabaseHas('opportunities', [
            'title' => 'Scholarship Program',
            'type'  => 'Scholarship',
        ]);
    }

    /**
     * Test: Return 404 if opportunity not found.
     *
     * This test ensures that the API correctly returns
     * a 404 Not Found response when trying to fetch
     * an opportunity by an ID that does not exist.
     */
    public function test_it_returns_404_if_opportunity_not_found()
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->getJson('/api/opportunities/999');
        $response->assertStatus(404);
    }
}
