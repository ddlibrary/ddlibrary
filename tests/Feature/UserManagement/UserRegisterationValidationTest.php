<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserRegisterationValidationTest extends TestCase
{
    
    use RefreshDatabase,DatabaseMigrations;
   
     /** @test */ 
     public function user_register_validation_username_required_error() {
        
        $response = $this->post('/register',[
                    '_token' => Session::token(),  
                    'username' => '',
                    'password' => 'test@12345',
                    'password_confirmation'=>'test@12345',
                    'email' => 'test@gmail.com',
                    'first_name' => 'test',
                    'last_name' => 'test',
                    'gender' => 'Male',
                    'country' => 'Afghanistan',
                    'city' => ''
          
                ]);
         $response->assertStatus(status:302);
         $response = $this->withSession(['username'])->get('/en/register');
                              
     }

    /** @test */ 
    public function user_registeration_validation_username_max_character_passes(){
       
        $username = str_repeat('a', 255);
        $response = $this->post('/register',[
            '_token' => Session::token(),  
            'username' => $username,
            'password' => 'test@12345',
            'password_confirmation'=>'test@12345',
            'email' => 'test@gmail.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
  
        ]);
        $response->assertStatus(status:302);
        $response->assertSessionDoesntHaveErrors([
            'username'
        ]);
        
    }

  /** @test */ 
  public function user_registeration_validation_username_max_character_fails(){
       
    $username = str_repeat('a', 256);
    $response = $this->post('/register',[
        '_token' => Session::token(),  
        'username' => $username,
        'password' => 'test@12345',
        'password_confirmation'=>'test@12345',
        'email' => 'test@gmail.com',
        'first_name' => 'test',
        'last_name' => 'test',
        'gender' => 'Male',
        'country' => 'Afghanistan',
        'city' => ''

    ]);
    $response->assertStatus(status:302);
    $this->session(['errors' => ['username']]);
    
    }

    /** @test */ 
    public function user_registeration_validation_email_required_error(){
     
        $response = $this->post('/register',[
            '_token' => Session::token(),  
            'username' => 'testuser',
            'password' => 'test@12345',
            'password_confirmation'=>'test@12345',
            'email' => '',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
  
        ]);
        $response->assertStatus(status:302);
        $response = $this->withSession(['email'])->get('/en/register');
                      
    }

    /** @test */ 
    public function user_registeration_validation_email_uniq_to_user_error(){
        
     $email = 'test_' . uniqid() . '@example.com';
        $response = $this->post('/register',[
            '_token' => Session::token(),  
            'username' => 'testuser',
            'password' => 'test@12345',
            'password_confirmation'=>'test@12345',
            'email' => $email,
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
  
        ]);
        $this->assertEquals(302, $response->getStatusCode());
        $duplicateResponse = $this->post('/register',[
            '_token' => Session::token(),  
            'username' => 'testuser',
            'password' => 'test@12345',
            'password_confirmation'=>'test@12345',
            'email' => $email,
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
  
        ]);
        $this->assertEquals(302, $duplicateResponse->getStatusCode());
        $this->session(['The email has already been taken','errors' => ['email']]);

    }

    /** @test */ 
    public function user_registeration_validation_password_Required_error(){

        $response = $this->post('/register',[
            '_token' => Session::token(),  
            'username' => '',
            'password' => '',
            'password_confirmation'=>'',
            'email' => 'test@gmail.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
  
        ]);
        $response->assertStatus(status:302);
        $response = $this->withSession(['password','password_confirmation'])->get('/en/register');

    }

    /** @test */ 
    public function user_registeration_validation_password_min_eight_character_pass(){
       
        $password = str_repeat('a', 8);

        $response = $this->post('/register',[
            '_token' => Session::token(),  
            'username' => 'testuser',
            'password' => $password,
            'password_confirmation'=>$password,
            'email' => 'test@gmail.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
  
        ]);
        $response->assertStatus(status:302);
       $response = $this->withSession(['password'])->get('/en/register');
        
    }
    
     /** @test */ 
     public function user_registeration_validation_password_min_eight_character_fails(){
       
        $password = str_repeat('a', 10);

        $response = $this->post('/register',[
            '_token' => Session::token(),  
            'username' => 'testuser',
            'password' => $password,
            'password_confirmation'=>$password,
            'email' => 'test@gmail.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
  
        ]);
        $response->assertStatus(status:302);
        $this->session(['errors' => ['password']]);
        
    }

    /** @test */ 
    public function user_registeration_validation_for_password_and_confirmpassword_are_equal_pass(){
    
        $userData = [
            'username' => 'testuser',
            'password' => 'password@123',
            'password_confirmation' => 'password@123',
            'email' => 'test@gmail.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
        ];

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'username' => $userData['username'],
        ]);

    }
    
    /** @test */ 
    public function user_registeration_validation_for_password_and_confirmpassword_are_equal_fails(){
    
        $userData = [
            'username' => 'testuser',
            'password' => 'password@123',
            'password_confirmation' => 'password@12355',
            'email' => 'test@gmail.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => 'Afghanistan',
            'city' => ''
        ];

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $this->session(['errors' => ['password']]);

    }

    /** @test */ 
    public function user_registeration_validation_gender_required_error(){
    
        $userData = [
            'username' => 'testuser',
            'password' => 'password@123',
            'password_confirmation' => 'password@123',
            'email' => 'test@gmail.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => '',
            'country' => 'Afghanistan',
            'city' => ''
        ];

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $this->session(['errors' => ['gender']]);

    }

     /** @test */ 
    public function user_registeration_validation_country_requird_error(){
     
        $userData = [
            'username' => 'testuser',
            'password' => 'password@123',
            'password_confirmation' => 'password@123',
            'email' => 'test@gmail.com',
            'first_name' => 'test',
            'last_name' => 'test',
            'gender' => 'Male',
            'country' => '',
            'city' => ''
        ];

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $this->session(['errors' => ['country']]);
        
    }



}
