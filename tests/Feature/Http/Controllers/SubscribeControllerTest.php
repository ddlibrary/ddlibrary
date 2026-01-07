<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SubscribeController
 */
class SubscribeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function en_authenticated_user_can_visit_subscribe_page(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/en/subscribe');

        $response->assertStatus(200)->assertViewIs('subscribe.index');
    }

    /** @test */
    public function en_unauthenticated_user_is_redirected_to_login_page(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/subscribe');

        $response->assertStatus(302)->assertRedirect('/en/login');
    }

    /** @test */
    public function en_authenticated_and_verified_user_can_subscribe(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/en/subscribe', $this->data(['name' => 'New User', '_method' => 'post']));
        $response->assertStatus(302)->assertRedirect('home');

        $this->assertDatabaseHas('subscribers', [
            'name' => 'New User',
            'email' => 'library@email.com',
        ]);

        $user->refresh();

        $this->assertEquals(1, $user->subscription()->count());
        $this->assertEquals($user->subscription->name, 'New User');
    }

    /** @test */
    public function en_unverified_user_can_not_subscribe(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->post('/en/subscribe', $this->data(['_method' => 'post']));
        $response->assertStatus(302)->assertRedirect('en/email/verify');

        $user->refresh();

        $this->assertEquals(0, $user->subscription()->count());
    }

    /** @test */
    public function en_name_field_is_required(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            '/en/subscribe',
            $this->data([
                'name' => '',
            ]),
        );

        $response->assertSessionHasErrors(['name' => 'The name field is required.']);
    }

    /** @test */
    public function en_email_field_is_required(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            '/en/subscribe',
            $this->data([
                'email' => '',
            ]),
        );

        $response->assertSessionHasErrors(['email' => 'The email field is required.']);
    }

    /** @test */
    public function en_email_should_be_a_valid_email(): void
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            '/en/subscribe',
            $this->data([
                'email' => 'email',
            ]),
        );

        $response->assertSessionHasErrors(['email' => 'The email field must be a valid email address.']);
    }

    /** @test */
    public function en_email_field_is_unique(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        Subscriber::factory()->create(['email' => 'test@email.com']);

        $response = $this->actingAs($user)->post(
            '/en/subscribe',
            $this->data([
                'email' => 'test@email.com',
            ]),
        );

        $response->assertSessionHasErrors(['email' => 'The email has already been taken.']);
    }

    // Farsi
    /** @test */
    public function fa_authenticated_user_can_visit_subscribe_page(): void
    {
        $this->refreshApplicationWithLocale('fa');

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/fa/subscribe');

        $response->assertStatus(200)->assertViewIs('subscribe.index');
    }

    /** @test */
    public function fa_unauthenticated_user_is_redirected_to_login_page(): void
    {
        $this->refreshApplicationWithLocale('fa');

        $response = $this->get('/fa/subscribe');

        $response->assertStatus(302)->assertRedirect('/fa/login');
    }

    /** @test */
    public function fa_authenticated_and_verified_user_can_subscribe(): void
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/fa/subscribe', $this->data(['name' => 'New User', '_method' => 'post']));
        $response->assertStatus(302)->assertRedirect('home');

        $this->assertDatabaseHas('subscribers', [
            'name' => 'New User',
            'email' => 'library@email.com',
        ]);

        $user->refresh();

        $this->assertEquals(1, $user->subscription()->count());
        $this->assertEquals($user->subscription->name, 'New User');
    }

    /** @test */
    public function fa_unverified_user_can_not_subscribe(): void
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create(['email_verified_at' => null]);

        $response = $this->actingAs($user)->post('/fa/subscribe', $this->data(['_method' => 'post']));
        $response->assertStatus(302)->assertRedirect('fa/email/verify');

        $user->refresh();

        $this->assertEquals(0, $user->subscription()->count());
    }

    /** @test */
    public function fa_name_field_is_required(): void
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            '/fa/subscribe',
            $this->data([
                'name' => '',
            ]),
        );

        $response->assertSessionHasErrors(['name' => 'فیلد نام الزامی است']);
    }

    /** @test */
    public function fa_email_field_is_required(): void
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            '/fa/subscribe',
            $this->data([
                'email' => '',
            ]),
        );

        $response->assertSessionHasErrors(['email' => 'فیلد ایمیل آدرس الزامی است']);
    }

    /** @test */
    public function fa_email_should_be_a_valid_email(): void
    {
        $this->refreshApplicationWithLocale('fa');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(
            '/fa/subscribe',
            $this->data([
                'email' => 'email',
            ]),
        );

        $response->assertSessionHasErrors(['email' => 'فرمت ایمیل آدرس معتبر نیست.']);
    }

    /** @test */
    public function fa_email_field_is_unique(): void
    {
        $this->refreshApplicationWithLocale('fa');

        $user = User::factory()->create();
        Subscriber::factory()->create(['email' => 'test@email.com']);

        $response = $this->actingAs($user)->post(
            '/fa/subscribe',
            $this->data([
                'email' => 'test@email.com',
            ]),
        );

        $response->assertSessionHasErrors(['email' => 'ایمیل آدرس قبلا انتخاب شده است.']);
    }

    protected function data($merge = [])
    {
        return array_merge(
            [
                'name' => 'Library',
                'email' => 'library@email.com',
            ],
            $merge,
        );
    }
}
