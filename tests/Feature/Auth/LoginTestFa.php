<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTestFa extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'fa';

    /** @test */
    public function fa_guest_can_visit_login_page(): void
    {
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
    public function fa_user_can_login_with_valid_credential(): void
    {
        $user = User::factory()->create(['email' => 'fa@email.com', 'password' => bcrypt('Pass@123')]);

        $response = $this->from('fa/login')->post('fa/login', [
            'email' => 'fa@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('home');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function fa_disable_user_cannot_login_with_valid_credential(): void
    {
        User::factory()->create(['email' => 'fa_disable_user@email.com', 'password' => bcrypt('Pass@123'), 'status' => false]);

        $response = $this->from('fa/login')->post('fa/login', [
            'email' => 'fa_disable_user@email.com',
            'password' => 'Pass@123',
        ]);

        $response->assertRedirect('/fa/login');
        $response->assertSessionHasErrors(['email' => 'اطلاعات وارد شده غلط می‌باشد.']);
    }

    public function fa_user_cannot_view_a_login_form_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/fa/login');
        $response->assertRedirect('home');
    }

    /** @test */
    public function fa_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('Pass@123')]);

        $response = $this->from('fa/login')->post('fa/login', [
            'email' => $user->email,
            'password' => 'Invalid@123',
        ]);

        $response->assertRedirect('/fa/login');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors();
        $response->assertSessionHasErrors(['email' => 'اطلاعات وارد شده غلط می‌باشد.']);

        $this->assertFalse(session()->hasOldInput('password'));
        $this->assertGuest();
    }

    /** @test */
    public function fa_user_cannot_login_with_invalid_email(): void
    {
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
}
