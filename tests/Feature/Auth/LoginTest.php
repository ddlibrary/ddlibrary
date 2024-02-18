<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;


    /** @test */
    public function en_guest_can_visit_login_page()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/login');

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');

        $response->assertSee('or');
        $response->assertSee('Email');
        $response->assertSee('Sign up');
        $response->assertSee('Password');
        $response->assertSee('Remember me');
        $response->assertSee('Forgot your password?');
        $response->assertSee('Log in to Darakht-e Danesh Library');
    }

    /** @test */
    public function en_user_can_login_with_valid_credential()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create(['email' => 'user@email.com', 'password' => bcrypt('Pass@123')]);
        
        $response = $this->from('en/login')->post('en/login', [
            'user-field' => 'user@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('home');
        $this->assertAuthenticatedAs($user);
        
    }

    public function en_user_cannot_view_a_login_form_when_authenticated()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/en/login');
        $response->assertRedirect('home');
    }

    /** @test */
    public function en_user_cannot_login_with_invalid_password()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create(['password' => bcrypt('Pass@123')]);
        
        $response = $this->from('en/login')->post('en/login', [
            'user-field' => $user->email,
            'password' => 'Invalid@123',
        ]);
        
        $response->assertRedirect('/en/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('email')[0], "These credentials do not match our records.");
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function en_user_cannot_login_with_invalid_email()
    {
        $this->refreshApplicationWithLocale('en');
        User::factory()->create(['email' => 'email@mail.com', 'password' => bcrypt('Pass@123')]);

        $response = $this->from('en/login')->post('en/login', [
            'user-field' => 'invalid@mail.com',
            'password' => "Pass@123",
        ]);

        $response->assertRedirect('/en/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('email')[0], "These credentials do not match our records.");
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }


    // Farsi

    /** @test */
    public function fa_guest_can_visit_login_page()
    {
        $this->refreshApplicationWithLocale('fa');

        $response = $this->get('/fa/login');

        $response->assertSuccessful();
        $response->assertViewIs('auth.login');

        $response->assertSee('یا');
        $response->assertSee('ایمیل یا نام کاربری یا شماره تیلفون');
        $response->assertSee('حساب جدید');
        $response->assertSee('گذرواژه');
        $response->assertSee('مرا به خاطر بسپار');
        $response->assertSee('گذرواژه را فراموش کردم');
        $response->assertSee("ورود به کتاب‌خانه درخت دانش");
    }

    /** @test */
    public function fa_user_can_login_with_valid_credential()
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create(['email' => 'fa@email.com', 'password' => bcrypt('Pass@123')]);
        
        $response = $this->from('fa/login')->post('fa/login', [
            'user-field' => 'fa@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('home');
        $this->assertAuthenticatedAs($user);
        
    }

    public function fa_user_cannot_view_a_login_form_when_authenticated()
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/fa/login');
        $response->assertRedirect('home');
    }

    /** @test */
    public function fa_user_cannot_login_with_invalid_password()
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create(['password' => bcrypt('Pass@123')]);
        
        $response = $this->from('fa/login')->post('fa/login', [
            'user-field' => $user->email,
            'password' => 'Invalid@123',
        ]);
        
        $response->assertRedirect('/fa/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('email')[0], "اطلاعات وارد شده غلط میباشد.");
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function fa_user_cannot_login_with_invalid_email()
    {
        $this->refreshApplicationWithLocale('fa');
        User::factory()->create(['email' => 'email@mail.com', 'password' => bcrypt('Pass@123')]);

        $response = $this->from('fa/login')->post('fa/login', [
            'user-field' => 'invalid@mail.com',
            'password' => "Pass@123",
        ]);

        $response->assertRedirect('/fa/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();

        $this->assertEquals(session('errors')->get('email')[0], "اطلاعات وارد شده غلط میباشد.");
        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

}
