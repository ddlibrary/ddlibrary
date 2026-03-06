<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePagePaTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'pa';

    /** @test */
    public function user_can_visit_pa_home_page(): void
    {
        $response = $this->get('/pa');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
