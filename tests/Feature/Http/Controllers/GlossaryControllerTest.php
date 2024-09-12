<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Glossary;
use App\Models\GlossarySubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\GlossaryController
 */
class GlossaryControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function test_index_returns_a_successful_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('en/glossary');

        $response->assertStatus(200);

        $response->assertViewIs('glossary.glossary_list');
    }

    /**
     * @test
     */
    public function test_create_returns_a_successful_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get(route('glossary_create'));

        $response->assertStatus(200);
        $response->assertViewIs('glossary.create');
    }

    /**
     * @test
     */
    public function test_store_creates_new_glossary_item(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $data = [
            'english' => 'english',
            'farsi' => 'farsi',
            'pashto' => 'pashto',
            'subject' => GlossarySubject::factory()->create()->id,
        ];

        $response = $this->actingAs($user)->post(route('glossary_store'), $data);

        $response->assertRedirect(url('en/glossary'));
        $this->assertDatabaseHas('glossary', $data);
    }

    /**
     * @test
     */
    public function test_update_glossary_item(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5); // Assuming role 5 is for library managers

        $glossary = Glossary::factory()->create();

        $data = [
            'data' => [
                $glossary->id, // Glossary id
                'glossary', // Type is glossary
                'en', // language
                'Updated English'], // updated value
        ];

        $response = $this->actingAs($user)->post(route('glossary_update'), $data);

        $response->assertStatus(200);

        $glossary->refresh();

        $this->assertEquals($glossary->name_en, 'Updated English');

    }

    /**
     * @test
     */
    public function test_delete_glossary_item(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $glossary = Glossary::factory()->create();

        $response = $this->actingAs($user)->post(route('glossary_delete', $glossary->id));

        $response->assertStatus(200);
        $this->assertDatabaseMissing('glossary', ['id' => $glossary->id]);
    }

    /**
     * @test
     */
    public function test_approve_glossary_item(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $glossary = Glossary::factory()->create(['flagged_for_review' => true]);

        $response = $this->actingAs($user)->post(route('glossary_approve', $glossary->id));

        $response->assertStatus(200);
        $this->assertDatabaseHas('glossary', [
            'id' => $glossary->id,
            'flagged_for_review' => false,
        ]);
    }
}
