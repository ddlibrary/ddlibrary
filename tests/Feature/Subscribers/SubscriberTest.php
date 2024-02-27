<?php

namespace Tests\Feature\Subscribers;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriberTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_visit_subscribe_page(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/en/subscribe');

        $response->assertStatus(200)->assertViewIs('subscribe.index');
    }

    /** @test */
    public function unauthenticated_user_is_redirected_to_login_page(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/subscribe');

        $response->assertStatus(302)->assertRedirect('/en/login');
    }

    /** @test */
    public function authenticated_user_can_subscribe()
    {
        $this->refreshApplicationWithLocale('en');
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/en/subscribe', $this->data(['name' => 'New User', '_method' => 'post']));
        $response->assertStatus(302)->assertRedirect('/subscribe');

        $this->assertDatabaseHas('subscribers', [
            'name' => 'New User',
            'email' => 'azizullahsaeidi@email.com',
        ]);
    }

    /** @test */
    public function name_field_is_required()
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
    public function email_field_is_required()
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
    public function email_should_be_a_valid_email()
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
    public function email_field_is_unique()
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

    protected function data($merge = [])
    {
        return array_merge(
            [
                'name' => 'Azizullah Saeidi',
                'email' => 'azizullahsaeidi@email.com',
            ],
            $merge,
        );
    }
}
