<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomePagePsTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'ps';

    #[Test]
    public function user_can_visit_ps_home_page(): void
    {
        $response = $this->get('/ps');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
