<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserValidationTest extends TestCase
{
    
    use RefreshDatabase,DatabaseMigrations;

     // generate random string for password
    private function generateRandomString($length){
        $characters = 'A1B2@33C#3D%3EF3%G3H3&&IJK4@!!4LM2N@O3P$@4QR4S@1TU3@&3&W2X%%Y2Za@3bc1!d3e@3fg4h@4ij4k@4lm4#4';
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $password;
    }
   
     /** @test */ 
    public function user_name_field_must_be_required() {
        $userData = UserDataGenerator::generateUserData();
        $userData['username'] = '';
      
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $errors = session('errors');
        $this->assertEquals($errors->get('username')[0], "The username field is required.");                    
    }
   
     /** @test */ 
    public function username_field_max_character_pass(){
        $username = str_repeat('H', 254);
        $userData = UserDataGenerator::generateUserData();
        $userData['username'] = $username;
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $this->assertDatabaseHas('users', ['username' => $userData['username']
    ]);
    }

     /** @test */ 
    public function user_name_field_max_characters_fail(){
        $username = str_repeat('a', 256);
        $userData = UserDataGenerator::generateUserData();
        $userData['username'] = $username;
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $errors = session('errors');
        $this->assertEquals($errors->get('username')[0], "The username may not be greater than 255 characters.");   
    }

     /** @test */ 
    public function email_field_to_be_required(){
        $userData = UserDataGenerator::generateUserData();
        $userData['email'] = '';
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $errors = session('errors');
        $this->assertEquals($errors->get('email')[0], "The email field is required when phone is not present.");             
    }

     /** @test */ 
    public function fmail_to_be_blocked(){
        $email =  'test33@fmail.com';
        $userData = UserDataGenerator::generateUserData();
        $userData['email'] = $email;

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $errors = session('errors');
        $this->assertEquals($errors->get('email')[0], "Please enter a valid email.");
    }

     /** @test */ 
    public function email_to_be_allowed(){
        $email =  'test33@gmail.com';
        $userData = UserDataGenerator::generateUserData();
        $userData['email'] = $email;

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $response->assertRedirect('/email/verify');
        $this->assertDatabaseHas('users', ['email' => $userData['email']
    ]);
    }

     /** @test */ 
    public function password_field_must_be_required(){
        $userData = UserDataGenerator::generateUserData();
        $userData['password'] = '';
        $userData['password_confirmation'] = '';
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $errors = session('errors');
        $this->assertEquals($errors->get('password')[0], "The password field is required.");
    }

     /** @test */ 
    public function password_min_eight_characters_pass(){
        $randpassword = $this->generateRandomString(10);
        $userData = UserDataGenerator::generateUserData();
        $userData['password'] = $randpassword;
        $userData['password_confirmation'] = $randpassword;
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $this->assertDatabaseHas('users', ['username' => $userData['username']
    ]);
    }
    
     /** @test */ 
    public function password_min_eight_characters_fails(){
        $randpassword = $this->generateRandomString(5);
        $userData = UserDataGenerator::generateUserData();
        $userData['password'] = $randpassword;
        $userData['password_confirmation'] = $randpassword;
       
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $errors = session('errors');
        $this->assertEquals($errors->get('password')[0], "The password must be at least 8 characters.");
    }

     /** @test */ 
    public function password_and_confirmpassword_equal_pass(){
        $userData = UserDataGenerator::generateUserData();

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['username' => $userData['username']
    ]);
    }
    
     /** @test */ 
    public function password_and_confirmpassword_equal_fail(){
        $userData = UserDataGenerator::generateUserData();
        $userData['password'] = 'Test@12345';
        $userData['password_confirmation'] = 'Test@123456789';
      
        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $errors = session('errors');
        $this->assertEquals($errors->get('password')[0], "The password confirmation does not match.");
    }

      /** @test */ 
    public function password_must_be_regix(){
        $userData = UserDataGenerator::generateUserData();
        $userData['password'] = 'test12345';
        $userData['password_confirmation'] = 'test12345';
      
        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $errors = session('errors');
        $this->assertEquals($errors->get('password')[0], "The password you entered doesn't have any special characters (!@#$%^&.) and (or) digits (0-9).");
    }

     /** @test */ 
    public function gender_id_must_be_required(){
        $userData = UserDataGenerator::generateUserData();
        $userData['gender'] = '';

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $errors = session('errors');
        $this->assertEquals($errors->get('gender')[0], "The gender field is required.");
    }

     /** @test */ 
    public function country_id_must_be_required(){
        $userData = UserDataGenerator::generateUserData();
        $userData['country'] = '';

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $errors = session('errors');
        $this->assertEquals($errors->get('country')[0], "The country field is required.");
    }

     /** @test */ 
    public function first_name_field_must_be_required(){
        $userData = UserDataGenerator::generateUserData();
        $userData['first_name'] = '';

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $errors = session('errors');
        $this->assertEquals($errors->get('first_name')[0], "The first name field is required.");
    }

      /** @test */ 
     public function first_name_max_characters_pass(){
        $first_name = str_repeat('f', 255);
        $userData = UserDataGenerator::generateUserData();
        $userData['first_name'] = $first_name;
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $response->assertSessionDoesntHaveErrors(['first_name']);
        $this->assertDatabaseHas('user_profiles', ['first_name' => $userData['first_name']
    ]);
    }

     /** @test */ 
    public function first_name_max_characters_fails(){
        $first_name = str_repeat('f', 257);
        $userData = UserDataGenerator::generateUserData();
        $userData['first_name'] = $first_name;
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $errors = session('errors');
        $this->assertEquals($errors->get('first_name')[0], "The first name may not be greater than 255 characters.");
    }
    
     /** @test */ 
    public function last_name_field_must_be_required(){
        $userData = UserDataGenerator::generateUserData();
        $userData['last_name'] = '';

        $response = $this->post('/register', $userData);
        $response->assertStatus(302);
        $errors = session('errors');
        $this->assertEquals($errors->get('last_name')[0], "The last name field is required.");
    }

     /** @test */ 
    public function last_name_max_characters_pass(){
        $last_name = str_repeat('f', 255);
        $userData = UserDataGenerator::generateUserData();
        $userData['last_name'] = $last_name;
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $response->assertSessionDoesntHaveErrors(['last_name']);
        $this->assertDatabaseHas('user_profiles', ['last_name' => $userData['last_name']
    ]);
    }

     /** @test */ 
    public function last_name_max_characters_fail(){
        $last_name = str_repeat('f', 257);
        $userData = UserDataGenerator::generateUserData();
        $userData['last_name'] = $last_name;
        
        $response = $this->post('/register', $userData);
        $response->assertStatus(status:302);
        $errors = session('errors');
        $this->assertEquals($errors->get('last_name')[0], "The last name may not be greater than 255 characters.");
    }
    
}
    class UserDataGenerator{
        public static function generateUserData(
        $username = 'testuser',
        $password = 'password@123',
        $password_confirmation = 'password@123',
        $email = 'test@gmail.com',
        $first_name = 'test user',
        $last_name = 'test user',
        $gender = 'Male',
        $country = 'Afghanistan',
        $city = ''
        ){
        return [
       'username' => $username,
       'password' => $password,
       'password_confirmation' => $password_confirmation,
       'email' => $email,
       'first_name' => $first_name,
       'last_name' => $last_name,
       'gender' => $gender,
       'country' => $country,
       'city' => $city,
       ];
    }

}
