<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomePageUzTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'uz';

    #[Test]
    public function user_can_visit_uz_home_page(): void
    {
        $response = $this->get('/uz');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
