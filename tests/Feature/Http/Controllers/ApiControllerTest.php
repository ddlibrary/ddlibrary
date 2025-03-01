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
    public function register_returns_an_ok_response(): void
    {
        $requestData = [
            'email' => 'ddluser@example.com',
            'username' => 'ddluser',
            'password' => 'Password@123!',
        ];

        $response = $this->postJson('api/register', $requestData);

        $response->assertOk();

        $response->assertJsonStructure(['token', 'user']);

        $this->assertDatabaseHas('users', [
            'email' => $requestData['email'],
            'username' => $requestData['username'],
        ]);

        $this->assertNotEmpty($response->json('token'));

        $user = User::where('email', $requestData['email'])->first();
        $this->assertEquals(1, $user->status); // Ensure the user is active
        $this->assertEquals(config('app.locale'), $user->language); // Check the default language

        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_id' => 6,
        ]);
    }

    /**
     * @test
     */
    public function pages_returns_an_ok_response(): void
    {
        $pages = Page::factory()
            ->count(3)
            ->create([
                'status' => 1,
                'language' => 'en',
            ]);

        $response = $this->getJson('api/pages/en'); // Change 'en' to the desired language if needed

        $response->assertOk();

        $response->assertJsonStructure([
            'current_page',
            'data' => [
                '*' => [
                    // Each page item in the data array should have the following structure
                    'id',
                    'title',
                    'summary',
                    'body',
                    'language',
                    'status',
                    'created_at',
                    'updated_at',
                ],
            ],
            'last_page',
            'per_page',
            'total',
        ]);

        $responseData = $response->json();

        $this->assertEquals(3, $responseData['total']);

        foreach ($pages as $page) {
            $this->assertTrue(in_array($page->id, array_column($responseData['data'], 'id')), "Page ID {$page->id} not found in the response.");
        }
    }

    /**
     * @test
     */
    public function page_view_returns_an_ok_response(): void
    {
        $page = Page::factory()->create();
        $translations = Page::factory()
            ->count(2)
            ->create(['tnid' => $page->tnid]);

        $response = $this->getJson("api/page_view/$page->id");

        $response->assertOk();
        $response->assertViewIs('pages.page_app_view');
        $response->assertViewHas('page');

        $translation_id = $page->tnid;
        if ($translation_id) {
            $response->assertViewHas('translations');
        } else {
            $response->assertViewHas('translations', []);
        }
    }

    /**
     * @test
     */
    public function page_returns_an_ok_response(): void
    {
        $pages = Page::factory()->count(3)->create();

        $pageItem = $pages->first();

        $response = $this->getJson("api/page/{$pageItem->id}");

        $response->assertOk();

        $response->assertJsonStructure([
            '*' => ['id', 'title', 'summary', 'body', 'created_at', 'updated_at'],
        ]);

        $this->assertCount(1, $response->json());
        $this->assertEquals($pageItem->id, $response->json()[0]['id']);
        $this->assertEquals($pageItem->title, $response->json()[0]['title']);
        $this->assertEquals($pageItem->summary, $response->json()[0]['summary']);
        $this->assertEquals($pageItem->body, $response->json()[0]['body']);
    }

    /**
     * @test
     */
    public function news_view_returns_an_ok_response(): void
    {
        $news = News::factory()->create();

        $response = $this->getJson("api/news_view/{$news->id}");

        $response->assertOk();

        $response->assertViewIs('news.news_api_view');

        $response->assertViewHas('news', $news);

        $translation_id = $news->tnid;
        if ($translation_id) {
            $translations = News::where('tnid', $translation_id)->get();
            $response->assertViewHas('translations', $translations);
        } else {
            $response->assertViewHas('translations', []);
        }
    }

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
        $resources = Resource::factory()
            ->count(12)
            ->create([
                'status' => 1,
                'language' => 'en',
            ]);

        $response = $this->getJson('api/resources/en');

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'abstract', 'status', 'language'],
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
        $resources = Resource::factory()
            ->count(40)
            ->create([
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
                '*' => ['id', 'title', 'abstract', 'status', 'language'],
            ],
            'links',
        ]);

        $this->assertCount(8, $response->json('data'));

        // Optionally, verify that the returned resources are from the correct offset
        $expectedIds = $resources
            ->slice(($offset - 1) * $perPage, $perPage)
            ->pluck('id')
            ->toArray();
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
        $menus = Menu::factory()
            ->count(3)
            ->create([
                'language' => 'en',
                'location' => 'bottom-menu',
            ]);

        // Make the API request
        $response = $this->getJson('api/links/en');

        // Assert that the response is OK
        $response->assertOk();

        // Assert the JSON structure of the response
        $response->assertJsonStructure([
            '*' => ['id', 'title', 'path'],
        ]);

        // Assert that the correct number of menus are returned
        $this->assertCount(3, $response->json());

        // Optionally verify that the expected menu items are returned
        foreach ($menus as $menu) {
            $this->assertTrue(collect($response->json())->contains(fn($item) => $item['id'] === $menu->id), 'Menu item not found in the response data');
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

        $response->assertJsonStructure(['token', 'user']);

        $this->assertArrayHasKey('token', $response->json());
        $this->assertEquals($user->username, $response->json('user'));

        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     */
    public function logout_returns_an_ok_response(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $token = $user->createToken('Test Device')->plainTextToken;

        $response = $this->withHeaders(['Authorization' => "Bearer {$token}"])->postJson('api/logout');

        $response->assertOk();

        $response->assertJsonStructure(['message']);

        $this->assertEquals('Logged out!', $response->json('message'));

        $this->assertCount(0, $user->tokens);
    }

    /**
     * @test
     */
    public function news_returns_an_ok_response(): void
    {
        $newsItems = News::factory()->count(3)->create();

        $newsItem = $newsItems->first();

        $response = $this->getJson("api/news/{$newsItem->id}");

        $response->assertOk();

        $response->assertJsonStructure([
            '*' => ['id', 'title', 'summary', 'body', 'created_at', 'updated_at'],
        ]);

        $this->assertCount(1, $response->json()); // Should return only one news item
        $this->assertEquals($newsItem->id, $response->json()[0]['id']);
        $this->assertEquals($newsItem->title, $response->json()[0]['title']);
        $this->assertEquals($newsItem->summary, $response->json()[0]['summary']);
        $this->assertEquals($newsItem->body, $response->json()[0]['body']);
    }

    /**
     * @test
     */
    public function news_list_returns_an_ok_response(): void
    {
        $newsItems = News::factory()
            ->count(3)
            ->create([
                'status' => 1,
                'language' => 'en',
            ]);

        $response = $this->getJson('api/news_list/en');

        $response->assertOk();

        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'title', 'summary', 'body', 'created_at', 'updated_at'],
            ],
            'links',
        ]);

        $this->assertCount(3, $response->json('data'));

        foreach ($newsItems as $newsItem) {
            $this->assertTrue(collect($response->json('data'))->contains(fn($item) => $item['id'] === $newsItem->id), 'News item not found in the response data');
        }
    }
}
