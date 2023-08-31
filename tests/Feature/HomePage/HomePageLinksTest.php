<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class HomePageLinksTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_home_page_links_Test()
    {
        LaravelLocalization::setLocale('en');
        $response = $this->get('/');

       
       $response->assertStatus(200);
        //$response->assertSee(__(key:'Explore our subjects'));
    }

    public function test_glossary_link_Test(){
        LaravelLocalization::setLocale('en');
      //$user = User::factory()->create();
      $response=$this->get('http://localhost:8000/en/gloassary');
      $redirectUrl = $response->headers->get('Location');
      $this->assertEquals('http://localhost:8000/en/gloassary', $redirectUrl);

      $response->assertRedirect('/en/gloassary');
      // $currenturl = url()->full();

      dd($response); 

       // $response->assertStatus(200);


    }


    public  function test_home_page(){

      
    }
}
