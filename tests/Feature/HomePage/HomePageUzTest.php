<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageUzTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'uz';

    /** @test */
    public function user_can_visit_uz_home_page(): void
    {
        $response = $this->get('/uz');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
