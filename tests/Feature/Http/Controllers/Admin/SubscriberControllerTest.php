<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Admin\SubscriberController
 */
class SubscriberControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function destroy_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $subscriber = Subscriber::factory()->create();

        $response = $this->actingAs($admin)->delete(route('subscribers.destroy', [$subscriber]));

        $response->assertRedirect();
        $this->assertModelMissing($subscriber);
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get(route('subscribers.index'));

        $response->assertOk();
        $response->assertViewIs('admin.subscriber.index');
        $response->assertViewHas('subscribers');
        $response->assertViewHas('totalSubscribers');
    }
}
