<?php

namespace Modules\Organizations\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class OrganizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Create a new organization.
     *
     * This test ensures that the API can successfully create
     * a new organization record when valid data is provided.
     * It checks:
     * - Response status is 201 (Created).
     * - JSON structure contains all expected fields.
     * - Database actually stores the new organization.
     */
    public function test_it_can_create_an_organization()
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->postJson('/api/organizations', [
            'license_number' => 'ABC123',
            'type' => 'NGO',
            'bio' => 'Non-profit organization focused on education',
            'website' => 'https://example.org',
        ]);

        $response->assertStatus(201)
         ->assertJsonStructure([
             'data' => [
                 'id',
                 'license_number',
                 'type',
                 'bio',
                 'website',
                 'created_at',
                 'updated_at',
             ]
         ]);


        $this->assertDatabaseHas('organizations', [
            'license_number' => 'ABC123',
        ]);
    }

    /**
     * Test: Return 404 if organization not found.
     *
     * This test ensures that the API correctly returns
     * a 404 Not Found response when trying to fetch
     * an organization by an ID that does not exist.
     */
    public function test_it_returns_404_if_organization_not_found()
    {
        $this->actingAs(User::factory()->create(), 'sanctum');

        $response = $this->getJson('/api/organizations/999');
        $response->assertStatus(404);
    }
}
