<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TaxonomyVocabulary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\VocabularyController
 */
class VocabularyControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get(route('vocabularycreate'));

        $response->assertOk();
        $response->assertViewIs('admin.vocabulary.vocabulary_create');
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $taxonomyVocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($user)->get('en/admin/vocabulary/edit/' . $taxonomyVocabulary->vid);

        $response->assertOk();
        $response->assertViewIs('admin.vocabulary.vocabulary_edit');
        $response->assertViewHas('vocabulary');
    }

    /**
     * @test
     */
    public function get_vocabularies_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get(route('getvocabularies'));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get(route('vocabularylist'));

        $response->assertOk();
        $response->assertViewIs('admin.vocabulary.vocabulary_list');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->post(
            route('vocabularystore'),
            $this->data([
                'name' => 'New vocabulary',
            ]),
        );

        $response->assertRedirect('/admin/vocabulary');

        $this->assertEquals('New vocabulary', TaxonomyVocabulary::latest()->value('name'));
    }

    /** @test */
    public function name_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->post(route('vocabularystore'), $this->data(['name' => '']));

        $response->assertSessionHasErrors(['name' => 'The name field is required.']);
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $taxonomyVocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($user)->post(
            "en/admin/vocabulary/edit/$taxonomyVocabulary->vid",
            $this->data([
                'name' => 'Updated vocabulary',
                'weight' => '10',
                'language' => 'fa',
            ]),
        );

        $response->assertRedirect('/admin/vocabulary');

        $this->assertEquals('Updated vocabulary', TaxonomyVocabulary::where('vid', $taxonomyVocabulary->vid)->value('name'));
        $this->assertEquals('10', TaxonomyVocabulary::where('vid', $taxonomyVocabulary->vid)->value('weight'));
        $this->assertEquals('fa', TaxonomyVocabulary::where('vid', $taxonomyVocabulary->vid)->value('language'));
    }

    protected function data($merge = [])
    {
        return array_merge(
            [
                'name' => 'Vocabulary',
                'language' => 'en',
                'weight' => '1',
            ],
            $merge,
        );
    }
}
