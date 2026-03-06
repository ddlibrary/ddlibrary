<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageSwTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'sw';

    /** @test */
    public function user_can_visit_sw_home_page(): void
    {
        $response = $this->get('/sw');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
