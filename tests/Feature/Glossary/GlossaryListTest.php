<?php

namespace Tests\Feature\Glossary;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlossaryListTest extends TestCase
{
    use DatabaseMigrations, RefreshDatabase;

    /** @test */
    public function user_can_visit_glossary_page(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/glossary');

        $response->assertStatus(200)
            ->assertViewIs('glossary.glossary_list');
    }
}
