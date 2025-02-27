<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceAttachment;
use App\Models\ResourceComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ResourceController
 */
class ResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $response = $this->get('en/admin/resources');

        $response->assertOk();
        $response->assertViewIs('admin.resources.resources');
        $response->assertViewHas('resources');
        $response->assertViewHas('filters');
        $response->assertViewHas('languages');
    }

    /**
     * @test
     */
    public function view_file_aborts_with_a_404(): void
    {
        $this->refreshApplicationWithLocale('en');

        $resource = Resource::factory()->create();

        $response = $this->get('en/resource/view/999/invalid-key');

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function published_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');


        $admin = User::factory()->create();
        $admin->roles()->attach(5); // Ensure this is the correct role for admin access
        $this->actingAs($admin);
    
        // Create a resource
        $resource = Resource::factory()->create(['status' => 0]); // Start with an unpublished status
    
        // Make the request to the published route
        $response = $this->get('en/admin/resource/published/' . $resource->id);
    
        // Assert that the response is a redirect (302)
        $response->assertRedirect();
    
        // Verify the resource's status was updated correctly
        $resource->refresh(); // Refresh the resource model to get the latest data
        $this->assertEquals(1, $resource->status);
    }

    
    /**
     * @test
     */
    public function resource_favorite_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = Resource::factory()->create();

        $response = $this->post('resources/favorite', [
            'userId' => $user->id,
            'resourceId' => $resource->id,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('resource_favorites', [
            'resource_id' => $resource->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * @test
     */
    public function update_tid_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();
        $translatedResource = Resource::factory()->create();

        $response = $this->post(route('updatetid', ['resourceId' => $resource->id]), [
            'link' => $translatedResource->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'tnid' => $translatedResource->id,
        ]);
    }

    /**
     * @test
     */
    public function view_public_resource_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $resource = Resource::factory()->create();
        $resourceComments = ResourceComment::factory()
            ->times(3)
            ->create(['resource_id' => $resource->id]);

        $response = $this->get('en/resource/' . $resource->id);

        $response->assertOk();
        $response->assertViewIs('resources.resources_view');
        $response->assertViewHas('resource', $resource);
        $response->assertViewHas('relatedItems');
        $response->assertViewHas('comments', $resourceComments);
        $response->assertViewHas('translations');
    }

    /**
     * @test
     */
    public function view_public_resource_aborts_with_a_403(): void
    {
        $this->refreshApplicationWithLocale('en');

        $resource = Resource::factory()->create(['status' => 0]); // Assuming status 0 means unpublished

        $response = $this->get('en/resource/' . $resource->id);

        $response->assertForbidden();
    }
}
