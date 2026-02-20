<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\TaxonomyVocabularyEnum;
use App\Models\Resource;
use App\Models\ResourceAttachment;
use App\Models\ResourceComment;
use App\Models\ResourceCopyrightHolder;
use App\Models\ResourceEducationalResource;
use App\Models\ResourceEducationalUse;
use App\Models\ResourceFavorite;
use App\Models\ResourceFile;
use App\Models\ResourceFlag;
use App\Models\ResourceIamAuthor;
use App\Models\ResourceKeyword;
use App\Models\ResourceSharePermission;
use App\Models\ResourceTranslationRight;
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

    /** @test */
    public function at_least_one_of_author_or_publisher_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('en/resources/add/step1', [
            'title' => 'Resource Title',
            'author' => null,
            'publisher' => null,
            'has_translator' => 1,
            'translator' => 'Translator',
            'language' => 'en',
            'abstract' => 'This is an abstract.',
        ]);

        $response->assertSessionHasErrors(['publisher']);
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
            'resourceId' => $resource->id,
        ]);

        $response->assertOk();
        
        $this->assertDatabaseHas('resource_favorites', [
            'resource_id' => $resource->id,
            'user_id' => $user->id, // Check for the authenticated user
        ]);
    }

    /**
     * @test
     */
    public function resource_favorite_returns_not_logged_in_if_user_is_not_authenticated()
    {
        $this->refreshApplicationWithLocale('en');

        $resource = Resource::factory()->create();

        $response = $this->post('resources/favorite', [
            'resourceId' => $resource->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'notloggedin']);
    }

    /**
     * @test
     */
    public function resource_favorite_adds_favorite_when_it_does_not_exist()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $secondUser = User::factory()->create();
        $resource = Resource::factory()->create();

        // Insert existing favorite for another user
        ResourceFavorite::create([
            'resource_id' => $resource->id,
            'user_id' => $secondUser->id,
        ]);
        
        $this->actingAs($user);
        $response = $this->post('resources/favorite', [
            'resourceId' => $resource->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['action' => 'added', 'favorite_count' => 2]);

        $this->assertDatabaseHas('resource_favorites', [
            'resource_id' => $resource->id,
            'user_id' => $user->id, // Ensure it's added for the authenticated user
        ]);
    }

    /**
     * @test
     */
    public function deletes_resource_favorite_when_it_exists()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        // Create a favorite for the user
        ResourceFavorite::create([
            'resource_id' => $resource->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);
        $response = $this->post('resources/favorite', [
            'resourceId' => $resource->id,
        ]);

        $response->assertStatus(200)
                 ->assertJson(['action' => 'deleted', 'favorite_count' => 0]);

        $this->assertDatabaseMissing('resource_favorites', [
            'resource_id' => $resource->id,
            'user_id' => $user->id, // Ensure it's deleted for the authenticated user
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

    // Step One
    /**
     * @test
     */
    public function edit_step_one_get_displays_correct_resource_values_from_database(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $author1 = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceAuthor->value, 'name' => 'Author One']);
        $author2 = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceAuthor->value, 'name' => 'Author Two']);
        $publisher = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourcePublisher->value, 'name' => 'Publisher']);
        $translator1 = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceTranslator->value, 'name' => 'Translator One']);
        $translator2 = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceTranslator->value, 'name' => 'Translator Two']);
        $resourceFile = ResourceFile::create(['name' => 'test-file.pdf', 'label' => 'Test File', 'language' => 'en', 'resource_id' => null]);

        $resource = Resource::factory()->create([
            'title' => 'Original Title',
            'abstract' => 'Original Abstract',
            'language' => 'en',
            'resource_file_id' => $resourceFile->id,
        ]);
        $resource->authors()->attach([$author1->id, $author2->id]);
        $resource->publishers()->attach($publisher->id);
        $resource->translators()->attach([$translator1->id, $translator2->id]);
        $resourceFile->update(['resource_id' => $resource->id]);

        $response = $this->get(route('edit1', ['resourceId' => $resource->id]));
        $response->assertOk();
        $response->assertViewIs('resources.resources_modify_step1');
        $response->assertViewHas('resource');

        $viewResource = $response->viewData('resource');
        $this->assertEquals('Original Title', $viewResource->title);
        $this->assertEquals('Original Abstract', $viewResource->abstract);
        $this->assertEquals('en', $viewResource->language);
        $this->assertEquals($resourceFile->id, $viewResource->resource_file_id);
        $this->assertTrue($viewResource->authors->contains($author1->id) && $viewResource->authors->contains($author2->id));
        $this->assertCount(2, $viewResource->authors);
        $this->assertContains('Author One', $viewResource->authors->pluck('name')->toArray());
        $this->assertContains('Author Two', $viewResource->authors->pluck('name')->toArray());
        $this->assertTrue($viewResource->publishers->contains($publisher->id));
        $this->assertContains('Publisher', $viewResource->publishers->pluck('name')->toArray());
        $this->assertTrue($viewResource->translators->contains($translator1->id) && $viewResource->translators->contains($translator2->id));
        $this->assertCount(2, $viewResource->translators);
        $this->assertContains('Translator One', $viewResource->translators->pluck('name')->toArray());
        $this->assertContains('Translator Two', $viewResource->translators->pluck('name')->toArray());
        $this->assertFalse($viewResource->translators->isEmpty(), 'This is a work of translation checkbox should be checked');
        $this->assertNotNull($viewResource->resourceFile);
        $this->assertEquals($resourceFile->id, $viewResource->resourceFile->id);
    }

    /**
     * @test
     */
    public function edit_step_one_post_stores_data_in_session_not_database(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $oldAuthor1 = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceAuthor->value, 'name' => 'Old Author One']);
        $oldAuthor2 = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceAuthor->value, 'name' => 'Old Author Two']);
        $oldPublisher = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourcePublisher->value, 'name' => 'Old Publisher']);
        $oldTranslator = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceTranslator->value, 'name' => 'Old Translator']);
        $oldResourceFile = ResourceFile::create(['name' => 'old-file.pdf', 'label' => 'Old File', 'language' => 'en', 'resource_id' => null]);

        $resource = Resource::factory()->create([
            'title' => 'Original Title',
            'abstract' => 'Original Abstract',
            'language' => 'en',
            'resource_file_id' => $oldResourceFile->id,
        ]);
        $resource->authors()->attach([$oldAuthor1->id, $oldAuthor2->id]);
        $resource->publishers()->attach($oldPublisher->id);
        $resource->translators()->attach($oldTranslator->id);
        $oldResourceFile->update(['resource_id' => $resource->id]);

        $newResourceFile = ResourceFile::create(['name' => 'new-file.pdf', 'label' => 'New File', 'language' => 'en', 'resource_id' => null]);
        $newValues = [
            'title' => 'Updated Title',
            'abstract' => 'Updated Abstract',
            'language' => 'fa',
            'author' => 'New Author',
            'publisher' => 'New Publisher',
            'has_translator' => 1,
            'translator' => 'New Translator',
            'resource_file_id' => $newResourceFile->id,
        ];
        $response = $this->post(route('edit1', ['resourceId' => $resource->id]), $newValues);
        $response->assertRedirect('/resources/edit/step2/' . $resource->id);

        // Verify data is stored in session
        $sessionData = Session::get('edit_resource_step_1');
        $this->assertNotNull($sessionData);
        $this->assertEquals('Updated Title', $sessionData['title']);
        $this->assertEquals('Updated Abstract', $sessionData['abstract']);
        $this->assertEquals('fa', $sessionData['language']);
        $this->assertEquals($resource->id, $sessionData['id']);

        // Verify data is NOT saved to database (original values should remain)
        $resource->refresh();
        $this->assertEquals('Original Title', $resource->title);
        $this->assertEquals('Original Abstract', $resource->abstract);
        $this->assertEquals('en', $resource->language);
        $this->assertEquals($oldResourceFile->id, $resource->resource_file_id);
        $this->assertTrue($resource->authors->contains($oldAuthor1->id) && $resource->authors->contains($oldAuthor2->id));
        $this->assertTrue($resource->publishers->contains($oldPublisher->id));
        $this->assertTrue($resource->translators->contains($oldTranslator->id));
    }

    /**
     * @test
     */
    public function edit_step_one_get_displays_correctly_when_no_author_only_publisher(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $publisherTerm = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourcePublisher->value, 'name' => 'Only Publisher']);
        $resource = Resource::factory()->create([
            'title' => 'Resource Without Author',
            'abstract' => 'Abstract',
            'language' => 'en',
        ]);
        $resource->publishers()->attach($publisherTerm->id);

        $response = $this->get(route('edit1', ['resourceId' => $resource->id]));

        $response->assertOk();
        $response->assertViewIs('resources.resources_modify_step1');
        $viewResource = $response->viewData('resource');
        $this->assertEquals(0, $viewResource->authors->count());
        $this->assertEquals(1, $viewResource->publishers->count());
        $this->assertTrue($viewResource->publishers->contains($publisherTerm->id));
    }

     /**
     * @test
     */
    public function edit_step_one_get_displays_correctly_when_no_publisher_only_author(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $authorTerm = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceAuthor->value, 'name' => 'Only Author']);
        $resource = Resource::factory()->create([
            'title' => 'Resource Without Publisher',
            'abstract' => 'Abstract',
            'language' => 'en',
        ]);
        $resource->authors()->attach($authorTerm->id);

        $response = $this->get(route('edit1', ['resourceId' => $resource->id]));

        $response->assertOk();
        $response->assertViewIs('resources.resources_modify_step1');
        $viewResource = $response->viewData('resource');
        $this->assertEquals(1, $viewResource->authors->count());
        $this->assertTrue($viewResource->authors->contains($authorTerm->id));
        $this->assertEquals(0, $viewResource->publishers->count());
    }

    /**
     * @test
     */
    public function edit_step_one_get_displays_correctly_when_no_translator(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $authorTerm = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceAuthor->value, 'name' => 'Author']);
        $resource = Resource::factory()->create([
            'title' => 'Resource Without Translator',
            'abstract' => 'Abstract',
            'language' => 'en',
        ]);
        $resource->authors()->attach($authorTerm->id);
        $resource->translators()->detach();

        $response = $this->get(route('edit1', ['resourceId' => $resource->id]));

        $response->assertOk();
        $response->assertViewIs('resources.resources_modify_step1');
        $viewResource = $response->viewData('resource');
        $this->assertEquals(0, $viewResource->translators->count());
        $this->assertTrue($viewResource->translators->isEmpty(), 'This is a work of translation checkbox should be unchecked when no translator');
    }

    /**
     * @test
     */
    public function edit_step_one_get_displays_correctly_when_no_resource_file(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $authorTerm = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::ResourceAuthor->value, 'name' => 'Author']);
        $resource = Resource::factory()->create([
            'title' => 'Resource Without File',
            'abstract' => 'Abstract',
            'language' => 'en',
            'resource_file_id' => null,
        ]);
        $resource->authors()->attach($authorTerm->id);

        $response = $this->get(route('edit1', ['resourceId' => $resource->id]));

        $response->assertOk();
        $response->assertViewIs('resources.resources_modify_step1');
        $viewResource = $response->viewData('resource');
        $this->assertNull($viewResource->resource_file_id);
        $this->assertNull($viewResource->resourceFile);
    }

    /**
     * @test
     */
    public function edit_step_one_post_accepts_when_no_author_only_publisher(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        $response = $this->post(route('edit1', ['resourceId' => $resource->id]), [
            'title' => 'Title',
            'abstract' => 'Abstract',
            'language' => 'en',
            'author' => '',
            'publisher' => 'Publisher Only',
            'has_translator' => 0,
            'translator' => '',
            'resource_file_id' => null,
        ]);

        $response->assertRedirect('/resources/edit/step2/' . $resource->id);
        $sessionData = Session::get('edit_resource_step_1');
        $this->assertEquals('Publisher Only', $sessionData['publisher']);
        $this->assertEmpty($sessionData['author'] ?? '');
    }

    /**
     * @test
     */
    public function edit_step_one_post_accepts_when_no_publisher_only_author(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        $response = $this->post(route('edit1', ['resourceId' => $resource->id]), [
            'title' => 'Title',
            'abstract' => 'Abstract',
            'language' => 'en',
            'author' => 'Author Only',
            'publisher' => '',
            'has_translator' => 0,
            'translator' => '',
            'resource_file_id' => null,
        ]);

        $response->assertRedirect('/resources/edit/step2/' . $resource->id);
        $sessionData = Session::get('edit_resource_step_1');
        $this->assertEquals('Author Only', $sessionData['author']);
    }

    /**
     * @test
     */
    public function edit_step_one_post_accepts_when_no_translator(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        $response = $this->post(route('edit1', ['resourceId' => $resource->id]), [
            'title' => 'Title',
            'abstract' => 'Abstract',
            'language' => 'en',
            'author' => 'Author',
            'publisher' => 'Publisher',
            'has_translator' => 0,
            'translator' => '',
            'resource_file_id' => null,
        ]);

        $response->assertRedirect('/resources/edit/step2/' . $resource->id);
        $sessionData = Session::get('edit_resource_step_1');
        $this->assertNull($sessionData['translator'] ?? null);
    }

    /**
     * @test
     */
    public function edit_step_one_post_accepts_when_no_resource_file(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        $response = $this->post(route('edit1', ['resourceId' => $resource->id]), [
            'title' => 'Title',
            'abstract' => 'Abstract',
            'language' => 'en',
            'author' => 'Author',
            'publisher' => 'Publisher',
            'has_translator' => 0,
            'translator' => '',
            'resource_file_id' => null,
        ]);

        $response->assertRedirect('/resources/edit/step2/' . $resource->id);
        $sessionData = Session::get('edit_resource_step_1');
        $this->assertArrayHasKey('resource_file_id', $sessionData);
        $this->assertTrue($sessionData['resource_file_id'] === '' || $sessionData['resource_file_id'] === null);
    }

    /**
     * @test
     */
    public function edit_step_one_post_fails_when_both_author_and_publisher_empty(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $resource = Resource::factory()->create();

        $response = $this->post(route('edit1', ['resourceId' => $resource->id]), [
            'title' => 'Title',
            'abstract' => 'Abstract',
            'language' => 'en',
            'author' => '',
            'publisher' => '',
            'has_translator' => 0,
            'translator' => '',
            'resource_file_id' => null,
        ]);

        $response->assertSessionHasErrors('publisher');
    }
}
