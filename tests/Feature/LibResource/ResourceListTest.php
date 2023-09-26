<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResourceListTest extends TestCase {

    use RefreshDatabase, DatabaseMigrations;

    /** @test */
    public function user_can_visit_english_resource_page() {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('Free and open educational resources for Afghanistan');
    }

    /** @test */
    public function user_can_visit_farsi_resource_page() {
        $this->refreshApplicationWithLocale('fa');

        $response = $this->get('/fa/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('منابع باز و رایگان آموزشی برای افغانستان');
    }

    /** @test */
    public function user_can_visit_pashto_resource_page() {
        $this->refreshApplicationWithLocale('ps');

        $response = $this->get('/ps/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('د افغانستان لپاره پرانیستی او وړیا سرچینی');
    }

    /** @test */
    public function user_can_visit_uzbaki_resource_page() {
        $this->refreshApplicationWithLocale('uz');

        $response = $this->get('/uz/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('افغانستان اوچون آچیق و مجانی اورگتیش قایناقلر');
    }

    /** @test */
    public function user_can_visit_manji_resource_page() {
        $this->refreshApplicationWithLocale('mj');

        $response = $this->get('/mj/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('منابع باز و رایگان آموزشی برای افغانستان');
    }

    /** @test */
    public function user_can_visit_noristani_resource_page() {
        $this->refreshApplicationWithLocale('no');

        $response = $this->get('/no/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('افغانستان نه معیں څنګ اوش کوں منبع');
    }

    /** @test */
    public function user_can_visit_soji_resource_page() {
        $this->refreshApplicationWithLocale('sw');

        $response = $this->get('/sw/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('افغُانستُانی ݜچي هوړُاجيلی او آندېي بېخا');
    }

    /** @test */
    public function user_can_visit_sheghnani_resource_page() {
        $this->refreshApplicationWithLocale('sh');

        $response = $this->get('/sh/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('تعلیمے مفت ات یېت سرچښمه یېن افغانستان ارد');
    }

    /** @test */
    public function user_can_visit_pashai_resource_page() {
        $this->refreshApplicationWithLocale('pa');

        $response = $this->get('/pa/resources');
        $response->assertStatus(200)
            ->assertViewIs('resources.resources_list')
            ->assertSee('منابع بازافغانستان آنتې چلوي او اندو ياده کينيس شِراونچې');
    }
}
