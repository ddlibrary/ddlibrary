<?php

namespace Tests\Feature\HomePage;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GlossaryListTest extends TestCase
{
    
    use RefreshDatabase, DatabaseMigrations;
    
     /** @test */
    public function user_can_visit_english_glossary_page(){
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/glossary');
        $response->assertStatus(200)
            ->assertViewIs('glossary.glossary_list'); 
    }

      /** @test */
    public function user_can_visit_farsi_glossary_page(){
        $this->refreshApplicationWithLocale('fa');
  
        $response = $this->get('/fa/glossary');
        $response->assertStatus(200)
              ->assertViewIs('glossary.glossary_list');
    }

      /** @test */
    public function user_can_visit_pashto_glossary_page(){
        $this->refreshApplicationWithLocale('ps');

        $response = $this->get('/ps/glossary');
        $response->assertStatus(200)
            ->assertViewIs('glossary.glossary_list');
    }

      /** @test */
    public function user_can_visit_uzbaki_glossary_page(){
        $this->refreshApplicationWithLocale('uz');
  
        $response = $this->get('/uz/glossary');
        $response->assertStatus(200)
              ->assertViewIs('glossary.glossary_list');
    }

      /** @test */
    public function user_can_visit_manji_glossary_page(){
        $this->refreshApplicationWithLocale('mj');

        $response = $this->get('/mj/glossary');
        $response->assertStatus(200)
            ->assertViewIs('glossary.glossary_list');
    }
        
     /** @test */
    public function user_can_visit_noristani_glossary_page(){
        $this->refreshApplicationWithLocale('no');

        $response = $this->get('/no/glossary');
        $response->assertStatus(200)
            ->assertViewIs('glossary.glossary_list');
    }

      /** @test */
    public function user_can_visit_soji_glossary_page(){
          $this->refreshApplicationWithLocale('sw');
  
          $response = $this->get('/sw/glossary');
          $response->assertStatus(200)
              ->assertViewIs('glossary.glossary_list');
    }

      /** @test */
    public function user_can_visit_sheghnani_glossary_page(){
        $this->refreshApplicationWithLocale('sh');
    
        $response = $this->get('/sh/glossary');
        $response->assertStatus(200)
            ->assertViewIs('glossary.glossary_list');
    }

      /** @test */
    public function user_can_visit_pashai_glossary_page(){
        $this->refreshApplicationWithLocale('pa');
  
        $response = $this->get('/pa/glossary');
        $response->assertStatus(200)
            ->assertViewIs('glossary.glossary_list');
    }
     
}
