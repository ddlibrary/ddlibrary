<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceAttachment;
use App\Models\ResourceComment;
use App\Models\User;
use Illuminate\Support\Facades\Session;
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
    public function attributes_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        // Test for authors
        $response = $this->get('en/resources/attributes/authors?term=sample');
        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['id', 'name'],
        ]);

        // Test for publishers
        $response = $this->get('en/resources/attributes/publishers?term=sample');
        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['id', 'name'],
        ]);

        // Test for translators
        $response = $this->get('en/resources/attributes/translators?term=sample');
        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['id', 'name'],
        ]);

        // Test for keywords
        $response = $this->get('en/resources/attributes/keywords?term=sample');
        $response->assertOk();
        $response->assertJsonStructure([
            '*' => ['id', 'name'],
        ]);
    }

    /**
     * @test
     */
    public function comment_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = Resource::factory()->create();

        $response = $this->post(route('comment'), [
            'userid' => $user->id,
            'resource_id' => $resource->id,
            'comment' => 'This is a test comment.',
        ]);

        $response->assertRedirect('resource/' . $resource->id);
        $this->assertDatabaseHas('resource_comments', [
            'resource_id' => $resource->id,
            'user_id' => $user->id,
            'comment' => 'This is a test comment.',
        ]);
    }

    /**
     * @test
     */
    public function create_step_one_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('step1'));

        $response->assertOk();
        $response->assertViewIs('resources.resources_add_step1');
        $response->assertViewHas('resource');
    }

    /**
     * @test
     */
    public function create_step_one_edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        $response = $this->get(route('edit1', ['resourceId' => $resource->id]));

        $response->assertOk();
        $response->assertViewIs('resources.resources_edit_step1');
    }

    /**
     * @test
     */
    public function create_step_three_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('step3'));

        $response->assertRedirect('/resources/add/step1');
    }

    /**
     * @test
     */
    public function create_step_three_edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        // Create a resource
        $resource = Resource::factory()->create();

        // Prepare resource1 data
        $resource1 = [
            'title' => $resource->title,
            'author' => "Author",
            'publisher' => "Publisher",
            'translator' => "Translator",
            'language' => $resource->language,
            'abstract' => $resource->abstract,
            'status' => 1,
        ];

        Session::put('resource1', $resource1);
        Session::put('resource2', [
            'subject_areas' => [],
            'learning_resources_types' => [],
            'keywords' => '',
            'educational_use' => [],
            'level' => [],
            'attc' => [],
        ]);

        $response = $this->get(route('edit3', ['resourceId' => $resource->id]));

        $response->assertOk();

        $response->assertViewIs('resources.resources_edit_step3');

        $response->assertViewHas('resource');
    }

    /**
     * @test
     */
    public function create_step_two_returns_an_ok_response(): void
    {

        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $response = $this->get(route('step2'));

        $response->assertRedirect('/resources/add/step1');

    }

    /**
     * @test
     */
    public function create_step_two_edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);
        $this->actingAs($user);

        $resource = Resource::factory()->create();

        $resource1 = [
            'title' => $resource->title,
            'author' => "Author",
            'publisher' => "Publisher",
            'translator' => "Translator",
            'language' => $resource->language,
            'abstract' => $resource->abstract,
            'status' => 1,
        ];

        Session::put('resource1', $resource1);

        $response = $this->get("en/resources/edit/step2/$resource->id");

        $response->assertOk();
        $response->assertViewIs('resources.resources_edit_step2');

    }

    /**
     * @test
     */
    public function delete_resource_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        $response = $this->get('en/admin/resource/delete/' . $resource->id);

        $response->assertRedirect();
        $this->assertEquals(0, Resource::whereId($resource->id)->count());
    }

    /**
     * @test
     */
    public function download_file_aborts_with_a_404(): void
    {
        $this->refreshApplicationWithLocale('en');

        $resource = Resource::factory()->create();

        $response = $this->get('en/resource/view/999/invalid-key');

        $response->assertNotFound();
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
