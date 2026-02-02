<?php

namespace Modules\Organizations\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Modules\Core\Models\User;
use Modules\Organizations\Models\Organization;
use Tests\TestCase;

class OpportunityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Create a new opportunity.
     */
    public function test_it_can_create_an_opportunity_with_location()
    {
        $user = \Modules\Core\Models\User::factory()->create();

        Role::firstOrCreate(['name' => 'system-admin', 'guard_name' => 'sanctum']);
        $this->actingAs($user, 'sanctum');
        $user->assignRole('system-admin');

        $organization = \Modules\Organizations\Models\Organization::factory()->create([
            'user_id' => $user->id,
        ]);

        // Create a location record using factory
        $location = \Modules\Core\Models\Location::factory()->create();

        $response = $this->postJson('/api/v1/opportunities', [
            'title'           => 'Scholarship Program',
            'description'     => 'A scholarship opportunity for students',
            'type'            => 'Scholarship',
            'start_date'      => now()->toDateString(),
            'end_date'        => now()->addDays(30)->toDateString(),
            'organization_id' => $organization->id,
            'location_id'     => $location->id, // added location_id
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'status'  => 'success',
            'message' => 'opportunities.created',
        ]);
    }



    /**
     * Test: Return 404 if opportunity not found.
     */
    public function test_it_returns_404_if_opportunity_not_found()
    {

        $user = \Database\Factories\UserFactory::new()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/opportunities/999');
        $response->assertStatus(404);
    }
}
