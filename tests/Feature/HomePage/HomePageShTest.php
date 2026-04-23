<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HomePageShTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'sh';

    #[Test]
    public function user_can_visit_sh_home_page(): void
    {
        $response = $this->get('/sh');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
