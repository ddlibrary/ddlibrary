<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageEnTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'en';

    /** @test */
    public function user_can_visit_english_home_page(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
