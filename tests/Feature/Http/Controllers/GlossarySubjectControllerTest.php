<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\GlossarySubject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\GlossarySubjectController
 */
class GlossarySubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_glossary_subjects_list(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $glossarySubjects = GlossarySubject::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('glossary_subjects_list'));

        $response->assertOk();
        $response->assertViewIs('admin.glossary.glossary_subject_list');
        $response->assertViewHas('glossary_subjects');
        $response->assertSee($glossarySubjects[0]->name);
    }

    public function test_admin_can_create_glossary_subject(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get(route('glossary_subjects_create'));

        $response->assertOk();
        $response->assertViewIs('admin.glossary.glossary_subject_edit');
        $response->assertViewHas('glossary_subject');
    }

    public function test_admin_can_edit_glossary_subject(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $subject = GlossarySubject::factory()->create();

        $response = $this->actingAs($admin)->get(route('glossary_subjects_edit', $subject));

        $response->assertOk();
        $response->assertViewIs('admin.glossary.glossary_subject_edit');
        $response->assertViewHas('glossary_subject');
        $response->assertSee($subject->name);
    }

    public function test_admin_can_update_glossary_subject(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $subject = GlossarySubject::factory()->create();
        $updatedData = [
            'english' => 'english',
            'farsi' => 'farsi',
            'pashto' => 'pashto',
            'munji' => 'munji',
            'nuristani' => 'nuristani',
            'pashayi' => 'pashayi',
            'shughni' => 'shughni',
            'swahili' => 'swahili',
            'uzbek' => 'uzbek',
            'id' => $subject->id,
        ];

        $response = $this->actingAs($admin)->post(route('glossary_subjects_update'), $updatedData);

        $response->assertRedirect();
        $this->assertDatabaseHas('glossary_subjects', ['id' => $subject->id, 'english' => 'english']);
    }

    public function test_non_admin_cannot_access_glossary_subjects(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('glossary_subjects_list'));

        $response->assertRedirect('/en');
    }
}
