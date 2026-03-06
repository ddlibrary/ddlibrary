<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTestEn extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'en';

    /** @test */
    public function en_guest_can_visit_login_page(): void
    {
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
    public function en_user_can_login_with_valid_credential(): void
    {
        $user = User::factory()->create(['email' => 'user@email.com', 'password' => bcrypt('Pass@123')]);

        $response = $this->from('en/login')->post('en/login', [
            'email' => 'user@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('home');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function en_disable_user_cannot_login_with_valid_credential(): void
    {
        User::factory()->create(['email' => 'disable_user@email.com', 'password' => bcrypt('Pass@123'), 'status' => false]);

        $response = $this->from('en/login')->post('en/login', [
            'email' => 'disable_user@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('/en/login');
        $response->assertSessionHasErrors(['email' => 'These credentials do not match our records.']);
    }

    public function en_user_cannot_view_a_login_form_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/en/login');
        $response->assertRedirect('home');
    }

    /** @test */
    public function en_user_cannot_login_with_invalid_password(): void
    {
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
    public function en_user_cannot_login_with_invalid_email(): void
    {
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

    /** @test */
    public function test_has_too_many_login_attempts(): void
    {
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
