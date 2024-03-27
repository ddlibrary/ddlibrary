<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function en_user_can_view_forgot_password_page()
    {
        $this->refreshApplicationWithLocale('en');
        $response = $this->get('/en/password/reset');

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');

        $response->assertSee('Reset your password');
        $response->assertSee('Your email address');
        $response->assertSee('Send password reset link');
    }

    /** @test */
    public function en_getting_error_if_email_does_not_exist()
    {
        $this->refreshApplicationWithLocale('en');

        User::factory()->create();

        $response = $this->post('/en/password/email', ['email' => 'invalid@mail.com']);

        $response->assertSessionHasErrors(['email' => 'if the user exists in our system, we will send an email']);
    }

    /** @test */
    public function fa_user_can_view_forgot_password_page()
    {
        $this->refreshApplicationWithLocale('fa');
        $response = $this->get('/fa/password/reset');

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');

        $response->assertSee('گذرواژه خود را تغییر بدهید');
        $response->assertSee('آدرس ایمیل شما');
        $response->assertSee('لطفاً ایمیل آدرس خود را وارد کنید.');
    }

    /** @test */
    public function fa_getting_error_if_email_does_not_exist()
    {
        $this->refreshApplicationWithLocale('fa');

        User::factory()->create();

        $response = $this->post('/fa/password/email', ['email' => 'invalid@mail.com']);

        $response->assertSessionHasErrors(['email' => 'اگر کاربر در سیستم موجود باشد، ما برای شما ایمیل میفرستیم.']);
    }
}
