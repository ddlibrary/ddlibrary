<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomePageNoTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'no';

    #[Test]
    public function user_can_visit_no_home_page(): void
    {
        $response = $this->get('/no');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
