<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForgotPasswordFaTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'fa';

    /** @test */
    public function fa_user_can_view_forgot_password_page(): void
    {
        $this->refreshApplicationWithLocale('fa');
        $response = $this->get('/fa/password/reset');

        $response->assertSuccessful();
        $response->assertViewIs('auth.passwords.email');

        $response->assertSee('گذرواژه خود را تغییر بدهید');
        $response->assertSee('آدرس ایمیل شما');
    }

    /** @test */
    public function fa_getting_error_if_email_does_not_exist(): void
    {
        $this->refreshApplicationWithLocale('fa');

        User::factory()->create();

        $response = $this->post('/fa/password/email', ['email' => 'invalid@mail.com']);

        $response->assertSessionHasErrors(['email' => 'اگر کاربر در سیستم موجود باشد، ما برای شما ایمیل میفرستیم.']);
    }
}
