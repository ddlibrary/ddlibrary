<?php

namespace Tests\Unit\Http\Requests;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Requests\SubscribeRequest
 */
class SubscribeRequestTest extends TestCase
{
    use RefreshDatabase;
    /** @var \App\Http\Requests\SubscribeRequest */
    private $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->subject = new \App\Http\Requests\SubscribeRequest();
    }

    /**
     * @test
     */
    public function authorize(): void
    {
        $actual = $this->subject->authorize();

        $this->assertTrue($actual);
    }

    /**
     * @test
     */
    public function rules(): void
    {
        $actual = $this->subject->rules();

        $this->assertValidationRules(
            [
                'g-recaptcha-response' => [],
                'email' => ['required', 'email', 'unique:subscribers,email'],
                'name' => ['required', 'string'],
            ],
            $actual,
        );
    }

    /**
     * @test
     */
    public function test_store_creates_new_subscriber(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $requestData = [
            'email' => 'library@example.com',
            'name' => 'Ddlibary user',
        ];

        $response = $this->actingAs($user)->post('en/subscribe', $requestData);

        $response->assertRedirect('home');

        $this->assertDatabaseHas('subscribers', [
            'email' => 'library@example.com',
            'name' => 'Ddlibary user',
            'user_id' => $user->id,
        ]);
        $response->assertSessionHas('alert.message', __('Thank you for subscribing to our newsletter! You will now receive updates and news directly in your inbox.'));
    }

    public function test_store_creates_new_subscriber_for_unauthenticated_user(): void
    {
        $this->refreshApplicationWithLocale('en');

        $requestData = [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ];

        $response = $this->post('en/subscribe', $requestData);

        $response->assertRedirect('en/login');

        $this->assertDatabaseMissing('subscribers', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_email_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();

        $requestData = [
            'name' => 'Test User',
        ];

        $response = $this->actingAs($user)->post('en/subscribe', $requestData);

        $response->assertSessionHasErrors('email');
    }

    public function test_email_must_be_valid_email_format()
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();

        $requestData = [
            'email' => 'invalid-email',
            'name' => 'Test User',
        ];

        $response = $this->actingAs($user)->post('en/subscribe', $requestData);

        $response->assertSessionHasErrors('email');
    }

     public function test_name_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        
        $user = User::factory()->create();
        
        $requestData = [
            'email' => 'test@example.com',
        ];

        $response = $this->actingAs($user)->post('en/subscribe', $requestData);

        $response->assertSessionHasErrors('name');
    }

    public function test_email_must_be_unique()
    {
        $this->refreshApplicationWithLocale('en');
        
        $user = User::factory()->create();

        Subscriber::create([
            'email' => 'test@example.com',
            'name' => 'Existing User',
            'user_id' => $user->id,
        ]);

        $requestData = [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ];

        $response = $this->actingAs($user)->post('en/subscribe', $requestData);

        $response->assertSessionHasErrors('email');
    }

    public function test_name_must_be_string()
    {
        $this->refreshApplicationWithLocale('en');
        
        $user = User::factory()->create();


        $requestData = [
            'email' => 'test@example.com',
            'name' => 12123,
        ];

        $response = $this->actingAs($user)->post('en/subscribe', $requestData);

        $response->assertSessionHasErrors('name');
    }

    public function test_recaptcha_response_is_required_when_enabled()
    {
        // Temporarily set CAPTCHA to enabled
        $this->app['config']->set('captcha.enabled', true);

        $this->refreshApplicationWithLocale('en');
        
        $user = User::factory()->create();
        
        $requestData = [
            'email' => 'test@example.com',
            'name' => 'Test User',
            'g-recaptcha-response' => [],
        ];

        $response = $this->actingAs($user)->post('en/subscribe', $requestData);

        $response->assertSessionHasErrors('g-recaptcha-response');
    }
}
