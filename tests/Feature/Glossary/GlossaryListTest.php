<?php

namespace Tests\Feature\Glossary;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class GlossaryListTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_visit_glossary_page(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/glossary');

        $response->assertStatus(200)
            ->assertViewIs('glossary.glossary_list');
    }
}
