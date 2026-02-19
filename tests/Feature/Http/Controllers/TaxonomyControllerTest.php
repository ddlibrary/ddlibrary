<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\TaxonomyHierarchy;
use App\Models\TaxonomyTerm;
use App\Models\TaxonomyVocabulary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
        $response->assertViewHas('taxonomyVocabularies');
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $this->modifyTaxonomyHierarchy();
        
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
        $response->assertViewHas('taxonomyVocabularies');
        $response->assertViewHas('supportedLocales');
        $response->assertViewHas('translationData');
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
        $response->assertViewHas('taxonomyVocabularies');
        $response->assertViewHas('languages');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $this->modifyTaxonomyHierarchy();

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        TaxonomyVocabulary::factory()->times(3)->create();
        $vocabulary = TaxonomyVocabulary::factory()->create();
        TaxonomyVocabulary::factory()->times(5)->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'names' => ['en' => 'New taxonomy'],
            'weight' => 1,
            'parents' => ['en' => 0],
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, TaxonomyTerm::where('vid', $vocabulary->vid)->count());
        $this->assertEquals('New taxonomy', TaxonomyTerm::where('vid', $vocabulary->vid)->value('name'));
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

        $response = $this->actingAs($admin)->get(route('taxonomyedit', ['taxonomyVocabulary' => $vocabulary->vid, 'tid' => $taxonomyTerm->id]));

        $response->assertOk();
        $response->assertViewIs('admin.taxonomy.taxonomy_edit');
        $response->assertViewHas('term');
        $response->assertViewHas('taxonomyVocabularies');
        $response->assertViewHas('supportedLocales');
        $response->assertViewHas('translationData');
    }

    /**
     * @test
     */
    public function store_the_vid_field_is_required(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'names' => ['en' => 'Test'],
            'weight' => 1,
        ]);

        $response->assertSessionHasErrors(['vid' => 'The vid field is required.']);
    }

    /**
     * @test
     */
    public function store_the_selected_vid_is_invalid(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => 99999,
            'names' => ['en' => 'Test'],
            'weight' => 1,
        ]);

        $response->assertSessionHasErrors(['vid']);
        $errors = $response->getSession()->get('errors')->get('vid');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('invalid', $errors[0]);
    }

    /**
     * @test
     */
    public function store_the_weight_field_is_required(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'names' => ['en' => 'Test'],
        ]);

        $response->assertSessionHasErrors(['weight' => 'The weight field is required.']);
    }

    /**
     * @test
     */
    public function store_the_weight_must_be_an_integer(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'names' => ['en' => 'Test'],
            'weight' => 'not-an-integer',
        ]);

        $response->assertSessionHasErrors(['weight']);
        $errors = $response->getSession()->get('errors')->get('weight');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('integer', $errors[0]);
    }

    /**
     * @test
     */
    public function store_the_weight_must_be_at_least_0(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'names' => ['en' => 'Test'],
            'weight' => -1,
        ]);

        $response->assertSessionHasErrors(['weight']);
        $errors = $response->getSession()->get('errors')->get('weight');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('at least 0', $errors[0]);
    }

    /**
     * @test
     */
    public function store_the_names_field_is_required(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'weight' => 1,
        ]);

        $response->assertSessionHasErrors(['names' => 'The names field is required.']);
    }

    /**
     * @test
     */
    public function store_at_least_one_translation_name_is_required(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'names' => ['en' => '', 'fa' => ' '],
            'weight' => 1,
        ]);

        $response->assertSessionHasErrors(['names']);
        $errors = $response->getSession()->get('errors')->get('names');
        $this->assertContains('At least one translation name is required.', $errors);
    }

    /**
     * @test
     */
    public function store_the_name_must_not_be_greater_than_255_characters(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'names' => ['en' => str_repeat('a', 256)],
            'weight' => 1,
        ]);

        $response->assertSessionHasErrors(['names.en']);
        $errors = $response->getSession()->get('errors')->get('names.en');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('255', $errors[0]);
    }

    /**
     * @test
     */
    public function store_the_parents_must_be_an_array(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'names' => ['en' => 'Test'],
            'weight' => 1,
            'parents' => '0',
        ]);

        $response->assertSessionHasErrors(['parents']);
        $errors = $response->getSession()->get('errors')->get('parents');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('array', $errors[0]);
    }

    /**
     * @test
     */
    public function store_the_parents_en_must_be_at_least_0(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post('en/admin/taxonomy/store', [
            'vid' => $vocabulary->vid,
            'names' => ['en' => 'Test'],
            'weight' => 1,
            'parents' => ['en' => -1],
        ]);

        $response->assertSessionHasErrors(['parents.en']);
        $errors = $response->getSession()->get('errors')->get('parents.en');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('at least 0', $errors[0]);
    }

    /**
     * @test
     */
    public function update_the_selected_term_ids_en_is_invalid(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $term = TaxonomyTerm::factory()->create();
        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->post("en/admin/taxonomy/update/{$vocabulary->vid}/{$term->id}", [
            'vid' => $vocabulary->vid,
            'names' => ['en' => 'Updated'],
            'weight' => 1,
            'term_ids' => ['en' => 99999],
        ]);

        $response->assertSessionHasErrors(['term_ids.en']);
        $errors = $response->getSession()->get('errors')->get('term_ids.en');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('invalid', $errors[0]);
    }

    /**
     * @test
     */
    public function index_the_selected_taxonomy_vocabulary_id_is_invalid(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get(route('gettaxonomylist', ['taxonomy_vocabulary_id' => 99999]));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['taxonomy_vocabulary_id']);
        $errors = $response->getSession()->get('errors')->get('taxonomy_vocabulary_id');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('invalid', $errors[0]);
    }

    /**
     * @test
     */
    public function index_the_selected_language_is_invalid(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->get(route('gettaxonomylist', [
            'taxonomy_vocabulary_id' => $vocabulary->vid,
            'language' => 'in-valid',
        ]));

        $response->assertSessionHasErrors(['language']);
        $errors = $response->getSession()->get('errors')->get('language');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('invalid', $errors[0]);
    }

    /**
     * @test
     */
    public function index_the_term_must_not_be_greater_than_255_characters(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $vocabulary = TaxonomyVocabulary::factory()->create();

        $response = $this->actingAs($admin)->get(route('gettaxonomylist', [
            'taxonomy_vocabulary_id' => $vocabulary->vid,
            'term' => str_repeat('a', 256),
        ]));

        $response->assertSessionHasErrors(['term']);
        $errors = $response->getSession()->get('errors')->get('term');
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('255', $errors[0]);
    }

    private function modifyTaxonomyHierarchy(){
        if (DB::getDriverName() === 'sqlite') {
            // Test-only: drop then recreate table with nullable aux_id and trigger for auto value
            DB::statement('DROP TABLE IF EXISTS taxonomy_term_hierarchy');
            DB::statement('CREATE TABLE taxonomy_term_hierarchy (id TEXT NOT NULL PRIMARY KEY, tid INTEGER NOT NULL, parent INTEGER NOT NULL, aux_id INTEGER NULL)');
            DB::statement('CREATE UNIQUE INDEX taxonomy_term_hierarchy_tid_parent_unique ON taxonomy_term_hierarchy (tid, parent)');
            DB::statement('CREATE TRIGGER taxonomy_term_hierarchy_aux_id_trigger AFTER INSERT ON taxonomy_term_hierarchy WHEN NEW.aux_id IS NULL BEGIN UPDATE taxonomy_term_hierarchy SET aux_id = (SELECT COALESCE(MAX(aux_id), 0) + 1 FROM taxonomy_term_hierarchy) WHERE rowid = NEW.rowid; END');
        }
    }
}
