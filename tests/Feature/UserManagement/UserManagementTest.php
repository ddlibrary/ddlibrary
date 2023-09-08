<?php

namespace Tests\Feature;

use Tests\TestCase;
//use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserManagementTest extends TestCase
{
    
    use RefreshDatabase,DatabaseMigrations;

       /** @test */
       public function user_can_loged_in_successfully() {
        $csrfToken = Session::token();
        
        $response = $this->post('/login',[
                   '_token' => Session::token(), 
                   'username'=>'administrator',
                   'password' => 'test@1234'
  
        ]);
        $response-> assertStatus(status:302);
        $response-> assertRedirect('/');

      }

       /** @test */
       public function user_cannot_login_becasue_user_does_not_exists() {
         $csrfToken = Session::token();
         
         $response = $this->post('/login',[
                    '_token' => Session::token(), 
                    'user-field'=>'naweedAhmad',
                    'password' => 'test@1234'
   
         ]);
         $response-> assertStatus(status:302);
         $response = $this->withSession(['message'])->get('/en/login');

 
       }

         /** @test */ 
       public function user_login_validation_error_redirects_back_with_errors() {

        $csrfToken = Session::token();
        $response = $this->post('/login',[
                   '_token' => Session::token(), 
                   'user-field'=>'',
                   'password' => ''
  
        ]);
        $response->assertStatus(status:302);
        $response->assertSessionHasErrors(['user-field']);
        $response->assertSessionHasErrors(['password']);

       }

       /** @test */
      public function user_can_register_successfully(){
         $csrfToken = Session::token();
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
      }

      /** @test */ 
     public function user_register_validation_error_redirects_back_with_errors() {
        $csrfToken = Session::token();
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


     

}
