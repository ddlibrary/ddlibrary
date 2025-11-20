<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceComment;
use App\Models\ResourceFlag;
use App\Models\TaxonomyTerm;
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
        $response->assertViewIs('resources.resources_modify_step1');
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
        $response->assertViewIs('resources.resources_modify_step1');
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
            'author' => 'Author',
            'publisher' => 'Publisher',
            'translator' => 'Translator',
            'language' => $resource->language,
            'abstract' => $resource->abstract,
            'status' => 1,
        ];

        Session::put('edit_resource_step_1', $resource1);
        Session::put('edit_resource_step_2', [
            'subject_areas' => [],
            'learning_resources_types' => [],
            'keywords' => '',
            'educational_use' => [],
            'level' => [],
            'attc' => [],
        ]);

        $response = $this->get(route('edit3', ['resourceId' => $resource->id]));

        $response->assertOk();

        $response->assertViewIs('resources.resources_modify_step3');

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
            'author' => 'Author',
            'publisher' => 'Publisher',
            'translator' => 'Translator',
            'language' => $resource->language,
            'abstract' => $resource->abstract,
            'status' => 1,
        ];

        Session::put('edit_resource_step_1', $resource1);

        $response = $this->get("en/resources/edit/step2/$resource->id");

        $response->assertOk();
        $response->assertViewIs('resources.resources_modify_step2');
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

        Resource::factory()->create();

        $key = encrypt(time()); // or any other value you need to encrypt

        $response = $this->get("en/resource/view/99999/{$key}");


        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function flag_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $resource = Resource::factory()->create();

        $response = $this->post(route('flag'), [
            'userid' => $user->id,
            'resource_id' => $resource->id,
            'type' => 'spam',
            'details' => 'This is a spam resource.',
        ]);

        $response->assertRedirect('resource/' . $resource->id);
        $this->assertEquals('This is a spam resource.', ResourceFlag::where('resource_id', $resource->id)->value('details'));
    }

    /**
     * @test
     */
    public function list_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('resourceList'));

        $response->assertOk();
        $response->assertViewIs('resources.resources_list');
        $response->assertViewHas('resources');
        $response->assertViewHas('views');
        $response->assertViewHas('favorites');
        $response->assertViewHas('comments');
    }

    /**
     * @test
     */
    public function post_step_one_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('en/resources/add/step1', [
            'title' => 'Resource Title',
            'author' => 'Author Name',
            'publisher' => 'Publisher Name',
            'translator' => 'Translator Name',
            'language' => 'en',
            'abstract' => 'This is an abstract.',
        ]);

        $response->assertRedirect('/resources/add/step2');
    }

    /**
     * @test
     */
    public function translator_field_is_required_when_has_translator_is_checked()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('en/resources/add/step1', [
            'title' => 'Resource Title',
            'author' => 'Author Name',
            'publisher' => 'Publisher Name',
            'has_translator' => 1,
            'translator' => '',
            'language' => 'en',
            'abstract' => 'This is an abstract.',
        ]);

        // Assert: Check that validation fails
        $response->assertSessionHasErrors('translator');
    }

    /**
     * @test
     */
    public function translator_field_is_nullable_when_has_translator_is_not_checked()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('en/resources/add/step1', [
            'title' => 'Resource Title',
            'author' => 'Author Name',
            'publisher' => 'Publisher Name',
            'has_translator' => 0,
            'translator' => '',
            'language' => 'en',
            'abstract' => 'This is an abstract.',
        ]);

        // Assert: Check there are no validation errors
        $response->assertSessionDoesntHaveErrors('translator');
    }

    /**
     * @test
     */
    public function post_step_one_edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        $response = $this->post('en/resources/edit/step1/' . $resource->id, [
            'title' => 'Updated Resource',
            'author' => 'Updated Author',
            'publisher' => 'Updated Publisher',
            'translator' => 'Updated Translator',
            'language' => 'en',
            'abstract' => 'Updated abstract.',
        ]);

        $response->assertRedirect('/resources/edit/step2/' . $resource->id);
    }

    /**
     * @test
     */
    public function post_step_three_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();
        $taxonomyTerm = TaxonomyTerm::factory()->create();

        $step1 = [
            'title' => 'nice',
            'author' => 'wow',
            'publisher' => 'wow',
            'translator' => 'great',
            'language' => 'en',
            'abstract' => '<p>abstract</p>',
        ];

        $step2 = [
            'subject_areas' => [],
            'keywords' => 'keyword',
            'learning_resources_types' => [],
            'educational_use' => [],
            'level' => [],
        ];

        Session::put('new_resource_step_1', $step1);
        Session::put('new_resource_step_2', $step2);

        $response = $this->post('en/resources/add/step3', [
            'translation_rights' => 1,
            'educational_resource' => 1,
            'copyright_holder' => null,
        ]);

        $response->assertRedirect('/home');
    }

    /**
     * @test
     */
    public function post_step_three_edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();
        $taxonomyTerm = TaxonomyTerm::factory()->create();

        $step1 = [
            'title' => 'updated title',
            'author' => 'updated wow',
            'publisher' => 'updated wow',
            'translator' => 'updated great',
            'language' => 'en',
            'abstract' => '<p>updated abstract</p>',
        ];

        $step2 = [
            'subject_areas' => [],
            'keywords' => 'keyword',
            'learning_resources_types' => [],
            'educational_use' => [],
            'level' => [],
        ];

        Session::put('edit_resource_step_1', $step1);
        Session::put('edit_resource_step_2', $step2);

        $resource = Resource::factory()->create();
        $taxonomyTerm = TaxonomyTerm::factory()->create();

        $response = $this->post('en/resources/edit/step3/' . $resource->id, [
            'translation_rights' => 1,
            'educational_resource' => 1,
            'copyright_holder' => null,
        ]);

        $response->assertRedirect('/resource/' . $resource->id);

        $this->assertEquals('updated title', Resource::whereId($resource->id)->value('title'));
    }

    /**
     * @test
     */
    public function post_step_two_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $step1 = [
            'title' => 'nice',
            'author' => 'wow',
            'publisher' => 'wow',
            'translator' => 'great',
            'language' => 'en',
            'abstract' => '<p>abstract</p>',
        ];

        Session::put('resource1', $step1);

        // learning_resources_types with vid 7
        TaxonomyTerm::factory()->create(['vid' => 7, 'name' => 'Book']);
        TaxonomyTerm::factory()->create(['vid' => 7, 'name' => 'Media']);

        // subject_areas with vid 8
        TaxonomyTerm::factory()->create(['vid' => 8, 'name' => 'Computer']);
        TaxonomyTerm::factory()->create(['vid' => 8, 'name' => 'History']);

        // educational_use with vid 25
        TaxonomyTerm::factory()->create(['vid' => 25, 'name' => 'Information Education']);
        TaxonomyTerm::factory()->create(['vid' => 25, 'name' => 'Professional Development']);

        // level with vid 13
        TaxonomyTerm::factory()->create(['vid' => 13, 'name' => 'Preschool']);
        TaxonomyTerm::factory()->create(['vid' => 13, 'name' => 'Literacy']);

        $response = $this->post('en/resources/add/step2', [
            'subject_areas' => TaxonomyTerm::where('vid', 8)->pluck('id'),
            'keywords' => 'keyword',
            'learning_resources_types' => TaxonomyTerm::where('vid', 7)->pluck('id'),
            'educational_use' => TaxonomyTerm::where('vid', 25)->pluck('id'),
            'level' => TaxonomyTerm::where('vid', 13)->pluck('id'),
        ]);

        $response->assertRedirect('/resources/add/step3');
    }

    /**
     * @test
     */
    public function post_step_two_edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $step1 = [
            'title' => 'nice',
            'author' => 'wow',
            'publisher' => 'wow',
            'translator' => 'great',
            'language' => 'en',
            'abstract' => '<p>abstract</p>',
        ];

        Session::put('resource1', $step1);

        // learning_resources_types with vid 7
        TaxonomyTerm::factory()->create(['vid' => 7, 'name' => 'Learning resource type']);

        // subject_areas with vid 8
        TaxonomyTerm::factory()->create(['vid' => 8, 'name' => 'Subject area']);

        // educational_use with vid 25
        TaxonomyTerm::factory()->create(['vid' => 25, 'name' => 'Educatinal use']);

        // level with vid 13
        TaxonomyTerm::factory()->create(['vid' => 13, 'name' => 'Level']);

        $resource = Resource::factory()->create();

        $response = $this->post("en/resources/edit/step2/$resource->id", [
            'subject_areas' => TaxonomyTerm::where('vid', 8)->latest()->take(1)->pluck('id'),
            'keywords' => 'keyword',
            'learning_resources_types' => TaxonomyTerm::where('vid', 7)->latest()->take(1)->pluck('id'),
            'educational_use' => TaxonomyTerm::where('vid', 25)->latest()->take(1)->pluck('id'),
            'level' => TaxonomyTerm::where('vid', 13)->latest()->take(1)->pluck('id'),
        ]);

        $response->assertRedirect('/resources/edit/step3/' . $resource->id);
    }

    /**
     * @test
     */
    public function view_file_aborts_with_a_404(): void
    {
        $this->refreshApplicationWithLocale('en');

        Resource::factory()->create();

        $key = encrypt(time());

        $response = $this->get("en/resource/view/789/$key");

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

        // Create resources
        $resource = Resource::factory()->create(['primary_tnid' => false]);
        $resource->tnid = $resource->id;
        $resource->save();
        
        $translatedResource = Resource::factory()->create(['primary_tnid' => false]);
        $translatedResource->tnid = $translatedResource->id;
        $translatedResource->save();

        // Make the POST request to update the tnid
        $response = $this->post(route('updatetid', ['resourceId' => $resource->id]), [
            'link' => $translatedResource->id,
        ]);

        $response->assertRedirect();

        // Assert that the tnid has been updated correctly
        $this->assertDatabaseHas('resources', [
            'id' => $resource->id,
            'tnid' => $resource->id,
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
