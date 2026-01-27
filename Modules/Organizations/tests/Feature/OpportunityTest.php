<?php

namespace Modules\Organizations\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Organizations\Models\Organization;
use Tests\TestCase;

class OpportunityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Create a new opportunity.
     */
    public function test_it_can_create_an_opportunity()
{
    $user = \Database\Factories\UserFactory::new()->create();
    $this->actingAs($user, 'sanctum');

    $organization = Organization::factory()->create([
        'user_id' => $user->id,
    ]);

    $response = $this->postJson('/api/opportunities', [
        'title'           => 'Scholarship Program',
        'description'     => 'A scholarship opportunity for students',
        'type'            => 'Scholarship',
        'start_date'      => now()->toDateString(),
        'end_date'        => now()->addDays(30)->toDateString(),
        'organization_id' => $organization->id,
    ]);

    $response->assertStatus(201);
}


    /**
     * Test: Return 404 if opportunity not found.
     */
    public function test_it_returns_404_if_opportunity_not_found()
    {
        // أنشئ مستخدم باستخدام الـFactory بشكل صريح
        $user = \Database\Factories\UserFactory::new()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/opportunities/999');
        $response->assertStatus(404);
    }
}
