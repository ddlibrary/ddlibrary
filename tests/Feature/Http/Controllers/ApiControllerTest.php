<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Menu;
use App\Models\News;
use App\Models\Page;
use App\Models\Resource;
use App\Models\ResourceAttachment;
use App\Models\ResourceComment;
use App\Models\ResourceFavorite;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ApiController
 */
class ApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function delete_returns_an_ok_response(): void
    {
        $user = User::factory()->create();

        $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);
        $response = $this->actingAs($user)->post('api/user/delete');

        $response->assertOk();

        $deletedUser = User::with('profile')->whereId($user->id)->first();

        $this->assertEquals('0', $deletedUser->status);
        $this->assertEquals(null, $deletedUser->profile->first_name);
        $this->assertEquals(null, $deletedUser->profile->last_name);
        $this->assertEquals(null, $deletedUser->profile->gender);
        $this->assertEquals(null, $deletedUser->profile->country);
        $this->assertEquals(null, $deletedUser->profile->city);
    }

    /**
     * @test
     */
    public function favorites_returns_an_ok_response(): void
    {
        $user = User::factory()->create();

        $resources = Resource::factory()->times(3)->create();

        foreach ($resources as $resource) {
            ResourceFavorite::factory()->create(['user_id' => $user->id, 'resource_id' => $resource->id]);
        }

        $response = $this->actingAs($user)->post('api/favorites');

        $response->assertOk();

        $responseData = $response->json();
        $this->assertCount(3, $responseData); // Should match the number of resources created

        foreach ($resources as $resource) {
            $this->assertTrue(in_array($resource->id, array_column($responseData, 'id')));
        }
    }

    /**
     * @test
     */
    public function user_returns_an_ok_response(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('api/user');

        $response->assertOk();

        $response->assertJsonStructure(['id', 'email']);

        $this->assertEquals($user->id, $response->json('id'));
        $this->assertEquals($user->email, $response->json('email'));
    }
}
