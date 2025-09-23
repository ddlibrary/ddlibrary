<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TaxonomyHierarchy;
use App\Models\TaxonomyTerm;
use App\Models\TaxonomyVocabulary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TaxonomyController
 */
class TaxonomyControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $taxonomyVocabularies = TaxonomyVocabulary::factory()->times(3)->create();
        $response = $this->actingAs($admin)->get(route('taxonomycreate'));

        $response->assertOk();
        $response->assertViewIs('admin.taxonomy.taxonomy_create');
        $response->assertViewHas('vocabulary');
    }

    /**
     * @test
     */
    public function create_translate_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $taxonomyVocabulary = TaxonomyVocabulary::factory()->create();
        $taxonomyTerm = TaxonomyTerm::factory()->create();
        $taxonomyHierarchy = TaxonomyHierarchy::factory()->create(['tid' => $taxonomyTerm->id, 'aux_id' => $taxonomyTerm->id]);
        $taxonomyTerms = TaxonomyTerm::factory()->times(3)->create();

        $response = $this->actingAs($admin)->get("en/admin/taxonomy/create-translate/$taxonomyTerm->id/null/en");

        $response->assertOk();
        $response->assertViewIs('admin.taxonomy.taxonomy_create_translate');
        $response->assertViewHas('vocabulary');
        $response->assertViewHas('tnid');
        $response->assertViewHas('vid');
        $response->assertViewHas('lang');
        $response->assertViewHas('weight');
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $taxonomyTerm = TaxonomyTerm::factory()->create();
        TaxonomyHierarchy::factory()->create();
        $taxonomyVocabularies = TaxonomyVocabulary::factory()->times(3)->create();
        TaxonomyTerm::factory()->times(3)->create();

        $response = $this->actingAs($admin)->get('en/admin/taxonomy/edit/' . $taxonomyVocabularies->first()->vid . "/$taxonomyTerm->id");

        $response->assertOk();
        $response->assertViewIs('admin.taxonomy.taxonomy_edit');
        $response->assertViewHas('term');
        $response->assertViewHas('vocabulary');
        $response->assertViewHas('parents');
        $response->assertViewHas('theParent');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        TaxonomyTerm::factory()->times(3)->create();
        TaxonomyVocabulary::factory()->times(3)->create();

        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get(route('gettaxonomylist'));

        $response->assertOk();
        $response->assertViewIs('admin.taxonomy.taxonomy_list');
        $response->assertViewHas('terms');
        $response->assertViewHas('searchBar');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        TaxonomyVocabulary::factory()->times(3)->create();
        $vocabulary = TaxonomyVocabulary::factory()->create();
        TaxonomyVocabulary::factory()->times(5)->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'name' => 'New taxonomy',
            'weight' => 1,
            'language' => 'en',
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, TaxonomyTerm::where('vid', $vocabulary->vid)->count());
        $this->assertEquals('New taxonomy', TaxonomyTerm::where('vid', $vocabulary->vid)->value('name'));
    }

    /**
     * @test
     */
    public function store_translate_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $taxonomyTerm = TaxonomyTerm::factory()->create();
        TaxonomyVocabulary::factory()->times(3)->create();
        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post(route('taxonomytranslatestore', ['tnid' => $taxonomyTerm->id]), [
            'vid' => $vocabulary->vid,
            'name' => 'New taxonomy translate',
            'weight' => 1,
            'language' => 'en',
        ]);

        $response->assertRedirect();
    }

    /**
     * @test
     */
    public function translate_returns_an_ok_response(): void
    {
        $taxonomyTerm = TaxonomyTerm::factory()->create();
        $taxonomyTerms = TaxonomyTerm::factory()->times(3)->create();

        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get("en/admin/taxonomy/translate/{$taxonomyTerm->id}");

        $response->assertOk();
        $response->assertViewIs('admin.taxonomy.taxonomy_translate');
        $response->assertViewHas('translations');
        $response->assertViewHas('supportedLocals');
        $response->assertViewHas('tnid');
        $response->assertViewHas('tid');
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $taxonomyTerm = TaxonomyTerm::factory()->create();
        TaxonomyVocabulary::factory()->times(3)->create();
        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->get(route('taxonomyedit', ['vid' => $vocabulary->vid, 'tid' => $taxonomyTerm->id]));

        $response->assertOk();
        $response->assertViewIs('admin.taxonomy.taxonomy_edit');
        $response->assertViewHas('term');
        $response->assertViewHas('vocabulary');
        $response->assertViewHas('parents');
        $response->assertViewHas('theParent');
    }
}
