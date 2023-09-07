<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserRegisterationPageTest extends TestCase
{
   use RefreshDatabase, DatabaseMigrations;
    

 /** @test */
    public function user_can_visit_english_register_page(){
      $this->refreshApplicationWithLocale('en');

       $response = $this-> get('/en/register');
      $response->assertStatus(status:200);
      $response->assertSee('Register an account');
      
    }
    /** @test */
    public function user_can_visit_farsi_register_page(){
      $this->refreshApplicationWithLocale('fa');

       $response = $this-> get('/fa/register');
      $response->assertStatus(status:200);
      $response->assertSee('ثبت یک حساب کاربری');
     
      
    }

    /** @test */
    public function user_can_visit_pashto_register_page(){
      $this->refreshApplicationWithLocale('ps');

       $response = $this-> get('/ps/register');
      $response->assertStatus(status:200);
      $response->assertSee('Register an account');
    }

    /** @test */
    public function user_can_visit_uzbaki_register_page(){
      $this->refreshApplicationWithLocale('uz');

       $response = $this-> get('/uz/register');
      $response->assertStatus(status:200);
      $response->assertSee('Register an account');
      
    }

    /** @test */
    public function user_can_visit_manji_register_page(){
      $this->refreshApplicationWithLocale('mj');

       $response = $this-> get('/mj/register');
      $response->assertStatus(status:200);
      $response->assertSee('Register an account');
      
    }

    /** @test */
    public function user_can_visit_noristani_register_page(){
      $this->refreshApplicationWithLocale('no');

       $response = $this-> get('/no/register');
      $response->assertStatus(status:200);
      $response->assertSee('Register an account');
      
    }

    /** @test */
    public function user_can_visit_soji_register_page(){
      $this->refreshApplicationWithLocale('sw');

       $response = $this-> get('/sw/register');
      $response->assertStatus(status:200);
      $response->assertSee('Register an account');
      
    }

    /** @test */
    public function user_can_visit_sheghnani_register_page(){
      $this->refreshApplicationWithLocale('sh');

       $response = $this-> get('/sh/register');
      $response->assertStatus(status:200);
      $response->assertSee('Register an account');
      
    }

       /** @test */
       public function user_can_visit_pashai_register_page(){
        $this->refreshApplicationWithLocale('pa');
  
         $response = $this-> get('/pa/register');
        $response->assertStatus(status:200);
        $response->assertSee('Register an account');
        
      }

 

}
