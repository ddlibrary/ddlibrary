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
use Illuminate\Support\Facades\Hash;
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

    /**
     * @test
     */
    public function resources_returns_an_ok_response(): void
    {

        $resources = Resource::factory()->count(12)->create([
            'status' => 1,
            'language' => 'en',
        ]);
    
        $response = $this->getJson('api/resources/en');
    
        $response->assertOk();
    
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'abstract',
                    'status',
                    'language',
                ],
            ],
            'links',
        ]);
    
        $this->assertCount(12, $response->json('data'));
    }

    /**
     * @test
     */
    public function resource_offset_returns_an_ok_response(): void
    {

        $resources = Resource::factory()->count(40)->create([
            'status' => 1,
            'language' => 'en',
        ]);
    
        // Define the language and offset (page number)
        $lang = 'en';
        $perPage = 32; // Number of resources per page
        $offset = 2;
    
        // Make the API request (assuming offset is treated as page number)
        $response = $this->getJson("api/resources/{$lang}?page={$offset}");
    
        // Assert that the response is OK
        $response->assertOk();
    
        // Assert the JSON structure of the response
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'abstract',
                    'status',
                    'language',
                ],
            ],
            'links',
        ]);
    
        $this->assertCount(8, $response->json('data'));
    
        // Optionally, verify that the returned resources are from the correct offset
        $expectedIds = $resources->slice(($offset - 1) * $perPage, $perPage)->pluck('id')->toArray();
        foreach ($response->json('data') as $item) {
            $this->assertTrue(in_array($item['id'], $expectedIds), 'Resource not found in the expected data set');
        }

    }

    /**
     * @test
     */
    public function links_returns_an_ok_response(): void
    {
        // Create 3 menu items with language 'en' and location 'bottom-menu'
        $menus = Menu::factory()->count(3)->create([
            'language' => 'en',
            'location' => 'bottom-menu',
        ]);

        // Make the API request
        $response = $this->getJson('api/links/en');

        // Assert that the response is OK
        $response->assertOk();

        // Assert the JSON structure of the response
        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'path',
            ],
        ]);

        // Assert that the correct number of menus are returned
        $this->assertCount(3, $response->json());

        // Optionally verify that the expected menu items are returned
        foreach ($menus as $menu) {
            $this->assertTrue(
                collect($response->json())->contains(fn($item) => $item['id'] === $menu->id),
                'Menu item not found in the response data'
            );
        }
    }

    /**
     * @test
     */
    public function login_returns_an_ok_response(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $requestData = [
            'email' => $user->email,
            'password' => 'password123',
            'device_name' => 'Test Device',
        ];

        $response = $this->postJson('api/login', $requestData);

        $response->assertOk();

        $response->assertJsonStructure([
            'token',
            'user',
        ]);

        $this->assertArrayHasKey('token', $response->json());
        $this->assertEquals($user->username, $response->json('user'));

        $this->assertAuthenticatedAs($user);
    }
}
