<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_visit_english_home_page(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }

    /** @test */
    public function user_can_visit_farsi_home_page(): void
    {
        $this->refreshApplicationWithLocale('fa');

        $response = $this->get('/fa');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }

    /** @test */
    public function user_can_visit_pashto_home_page(): void
    {
        $this->refreshApplicationWithLocale('ps');

        $response = $this->get('/ps');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }

    /** @test */
    public function user_can_visit_uzbaki_home_page(): void
    {
        $this->refreshApplicationWithLocale('uz');

        $response = $this->get('/uz');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }

    /** @test */
    public function user_can_visit_munji_home_page(): void
    {
        $this->refreshApplicationWithLocale('mj');

        $response = $this->get('/mj');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }

    /** @test */
    public function user_can_visit_noorestani_home_page(): void
    {
        $this->refreshApplicationWithLocale('no');

        $response = $this->get('/no');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }

    /** @test */
    public function user_can_visit_sowji_home_page(): void
    {
        $this->refreshApplicationWithLocale('sw');

        $response = $this->get('/sw');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }

    /** @test */
    public function user_can_visit_shaghnani_home_page(): void
    {
        $this->refreshApplicationWithLocale('sh');

        $response = $this->get('/sh');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }

    /** @test */
    public function user_can_visit_pashaiee_home_page(): void
    {
        $this->refreshApplicationWithLocale('pa');

        $response = $this->get('/pa');

        $response->assertStatus(200)
            ->assertViewIs('home');
    }
}
