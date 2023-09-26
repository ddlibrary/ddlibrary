<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AccessLoginTest extends TestCase {
    use RefreshDatabase, DatabaseMigrations;

    /** @test */
    public function user_can_visit_english_login_page() {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/login');
        $response->assertStatus(status: 200);
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }

    /** @test */
    public function user_can_visit_farsi_login_page() {
        $this->refreshApplicationWithLocale('fa');

        $response = $this->get('/fa/login');
        $response->assertStatus(status: 200);
        $response->assertSee('ورود به کتاب‌خانه درخت دانش');
    }

    /** @test */
    public function user_can_visit_pashto_login_page() {
        $this->refreshApplicationWithLocale('ps');

        $response = $this->get('/ps/login');
        $response->assertStatus(status: 200);
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }

    /** @test */
    public function user_can_visit_uzbaki_login_page() {
        $this->refreshApplicationWithLocale('uz');

        $response = $this->get('/uz/login');
        $response->assertStatus(status: 200);
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }

    /** @test */
    public function user_can_visit_manji_login_page() {
        $this->refreshApplicationWithLocale('mj');

        $response = $this->get('/mj/login');
        $response->assertStatus(status: 200);
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }

    /** @test */
    public function user_can_visit_noristani_login_page() {
        $this->refreshApplicationWithLocale('no');

        $response = $this->get('/no/login');
        $response->assertStatus(status: 200);
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }

    /** @test */
    public function user_can_visit_soji_login_page() {
        $this->refreshApplicationWithLocale('sw');

        $response = $this->get('/sw/login');
        $response->assertStatus(status: 200);
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }

    /** @test */
    public function user_can_visit_sheghnani_login_page() {
        $this->refreshApplicationWithLocale('sh');

        $response = $this->get('/sh/login');
        $response->assertStatus(status: 200);
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }

    /** @test */
    public function user_can_visit_pashai_login_page() {
        $this->refreshApplicationWithLocale('pa');

        $response = $this->get('/pa/login');
        $response->assertStatus(status: 200);
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }
}
