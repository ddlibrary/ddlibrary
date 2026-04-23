<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomePageFaTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'fa';

    #[Test]
    public function user_can_visit_fa_home_page(): void
    {
        $response = $this->get('/fa');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
