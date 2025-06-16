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

        $response->assertSee('Email');
        $response->assertSee('Sign up');
        $response->assertSee('Password');
        $response->assertSee('Forgot your password?');
        $response->assertSee('Log in');
    }

    /** @test */
    public function en_user_can_login_with_valid_credential()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create(['email' => 'user@email.com', 'password' => bcrypt('Pass@123')]);

        $response = $this->from('en/login')->post('en/login', [
            'email' => 'user@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('home');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function en_disable_user_cannot_login_with_valid_credential()
    {
        $this->refreshApplicationWithLocale('en');
        User::factory()->create(['email' => 'disable_user@email.com', 'password' => bcrypt('Pass@123'), 'status' => false]);

        $response = $this->from('en/login')->post('en/login', [
            'email' => 'disable_user@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('/en/login');
        $response->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);
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
            'email' => $user->email,
            'password' => 'Invalid@123',
        ]);

        $response->assertRedirect('/en/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();
        $response->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);

        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function en_user_cannot_login_with_invalid_email()
    {
        $this->refreshApplicationWithLocale('en');
        User::factory()->create(['email' => 'email@mail.com', 'password' => bcrypt('Pass@123')]);

        $response = $this->from('en/login')->post('en/login', [
            'email' => 'invalid@mail.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('/en/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();
        $response->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);

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

        $response->assertSee('حساب جدید');
        $response->assertSee('گذرواژه');
        $response->assertSee('مرا به خاطر بسپار');
        $response->assertSee('گذرواژه را فراموش کردید؟');
        $response->assertSee('ورود به سیستم');
    }

    /** @test */
    public function fa_user_can_login_with_valid_credential()
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create(['email' => 'fa@email.com', 'password' => bcrypt('Pass@123')]);

        $response = $this->from('fa/login')->post('fa/login', [
            'email' => 'fa@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('home');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function fa_disable_user_cannot_login_with_valid_credential()
    {
        $this->refreshApplicationWithLocale('fa');
        User::factory()->create(['email' => 'fa_disable_user@email.com', 'password' => bcrypt('Pass@123'), 'status' => false]);

        $response = $this->from('fa/login')->post('fa/login', [
            'email' => 'fa_disable_user@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('/fa/login');
        $response->assertSessionHasErrors(['email' => 'اطلاعات وارد شده غلط می‌باشد.']);
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
            'email' => $user->email,
            'password' => 'Invalid@123',
        ]);

        $response->assertRedirect('/fa/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();
        $response->assertSessionHasErrors(['email' => 'اطلاعات وارد شده غلط میباشد.']);

        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function fa_user_cannot_login_with_invalid_email()
    {
        $this->refreshApplicationWithLocale('fa');
        User::factory()->create(['email' => 'email@mail.com', 'password' => bcrypt('Pass@123')]);

        $response = $this->from('fa/login')->post('fa/login', [
            'email' => 'invalid@mail.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('/fa/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();
        $response->assertSessionHasErrors(['email' => 'اطلاعات وارد شده غلط می‌باشد.']);

        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function test_has_too_many_login_attempts()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();

        // Make 5 requests
        for ($i = 0; $i < 5; $i++) {
            $response = $this->from('en/login')->post('en/login', [
                'email' => $user->email,
                'password' => 'wrong',
            ]);

            $response->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);
        }

        // Getting 'Too many login attempts' message on the 6th login attempt.
        $this->from('en/login')->post('en/login', [
            'email' => $user->email,
            'password' => 'wrong',
        ]);

        $response->assertSessionHasErrors('email', 'Too many login attempts. Please try again in 58 seconds.');
    }
}
