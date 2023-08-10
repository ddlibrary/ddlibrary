<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HomePageTest extends TestCase
{
    
    use RefreshDatabase, DatabaseMigrations;
    
    /** @test */
    public function user_can_visit_english_home_page()
    {
        LaravelLocalization::setLocale('en');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/en', $redirectUrl);

        // Assert that the response is a redirect to /en
        $response->assertRedirect('/en');
    }

    /** @test */
    public function user_can_visit_farsi_home_page()
    {
        LaravelLocalization::setLocale('fa');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/fa', $redirectUrl);

        // Assert that the response is a redirect to /fa
        $response->assertRedirect('/fa');
    }

    /** @test */
    public function user_can_visit_pashto_home_page()
    {
        LaravelLocalization::setLocale('ps');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/ps', $redirectUrl);

        // Assert that the response is a redirect to /ps
        $response->assertRedirect('/ps');
    }

    /** @test */
    public function user_can_visit_usbaki_home_page()
    {
        LaravelLocalization::setLocale('uz');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/uz', $redirectUrl);

        // Assert that the response is a redirect to /uz
        $response->assertRedirect('/uz');
    }

    /** @test */
    public function user_can_visit_munji_home_page()
    {
        LaravelLocalization::setLocale('mj');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/mj', $redirectUrl);

        // Assert that the response is a redirect to /mj
        $response->assertRedirect('/mj');
    }

    /** @test */
    public function user_can_visit_noorestani_home_page()
    {
        LaravelLocalization::setLocale('no');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/no', $redirectUrl);

        // Assert that the response is a redirect to /no
        $response->assertRedirect('/no');
    }

    /** @test */
    public function user_can_visit_sowji_home_page()
    {
        LaravelLocalization::setLocale('sw');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/sw', $redirectUrl);

        // Assert that the response is a redirect to /sw
        $response->assertRedirect('/sw');
    }

    /** @test */
    public function user_can_visit_shafnani_home_page()
    {
        LaravelLocalization::setLocale('sh');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/sh', $redirectUrl);

        // Assert that the response is a redirect to /sh
        $response->assertRedirect('/sh');
    }

    /** @test */
    public function user_can_visit_pashaiee_home_page()
    {
        LaravelLocalization::setLocale('pa');
       // Send a GET request to the root URL of the web application
        $response = $this->get('/');

        // Get the redirect URL from the response headers
        $redirectUrl = $response->headers->get('Location');

        // Assert that the redirect URL is correct
        $this->assertEquals('http://localhost/pa', $redirectUrl);

        // Assert that the response is a redirect to /pa
        $response->assertRedirect('/pa');
    }
}
