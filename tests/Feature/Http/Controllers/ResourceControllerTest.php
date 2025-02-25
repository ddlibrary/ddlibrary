<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceComment;
use App\Models\ResourceAttachment;
use App\Models\ResourceFlag;
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
