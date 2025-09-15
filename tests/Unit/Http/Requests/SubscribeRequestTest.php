<?php

namespace Tests\Unit\Http\Requests;

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

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $requestData = [
            'email' => 'library@example.com',
            'name' => 'Ddlibary user',
        ];

        $response = $this->actingAs($admin)->post('en/subscribe', $requestData);

        $response->assertRedirect('home');

        $this->assertDatabaseHas('subscribers', [
            'email' => 'library@example.com',
            'name' => 'Ddlibary user',
            'user_id' => $admin->id,
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
}
