<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageMjTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'mj';

    /** @test */
    public function user_can_visit_mj_home_page(): void
    {
        $response = $this->get('/mj');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
