<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserManagementTest extends TestCase
{
    
    use RefreshDatabase,DatabaseMigrations;

    /** @test */
  public function user_can_loged_in_successfully(){
    $user = User::factory()->create([
      'username' => 'testuser',
      'password' => bcrypt('password'),
      'email' =>'test@gmail.com',
      'status' => 1,
      'language'=> 'en',
    ]);
    $response = $this->post('/login',[
      '_token' => Session::token(), 
      'user-field'=>'testuser',
      'password' => 'password'
    ]);  
    $response-> assertStatus(status:302);
    $response->assertRedirect('/home');
    $this->assertAuthenticatedAs($user);
  }

    /** @test */
  public function user_does_not_exists(){
     $response = $this->post('/login',[
       '_token' => Session::token(), 
       'user-field'=>'naweedAhmad',
       'password' => 'test@1234'
    ]);
     $response-> assertStatus(status:302);
     $errors = session('errors');
     $this->assertEquals($errors->get('email')[0],"These credentials do not match our records.");
  }

    /** @test */ 
  public function username_and_password_are_required(){
     $response = $this->post('/login',[
       '_token' => Session::token(), 
       'user-field'=>'',
       'password' => ''
    ]);
     $response->assertStatus(status:302);
     $errors = session('errors');
     $this->assertEquals($errors->get('user-field')[0],"The user-field field is required.");
     $this->assertEquals($errors->get('password')[0],"The password field is required.");
  }

    /** @test */
  public function user_can_register_successfully(){
     $response = $this->post('register',[
       '_token' => Session::token(),  
       'username' => 'test',
       'password' => 'test@12345',
       'password_confirmation'=>'test@12345',
       'email' => 'test@gmail.com',
       'first_name' => 'test',
       'last_name' => 'test',
       'gender' => 'Male',
       'country' => '256',
       'city' => ''
    ]);
     $response-> assertStatus(status:302);
     $response-> assertRedirect('/email/verify');
     $this->assertDatabaseHas('users', ['email' => 'test@gmail.com']);
  }
           
}
