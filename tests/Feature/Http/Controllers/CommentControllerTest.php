<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Models\Resource;
use App\Models\ResourceComment;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CommentController
 */
class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function comment_creates_a_new_resource_comment_and_redirects(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        Mail::fake();

        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        // Create a setting for website_email
        Setting::factory()->create(['id' => 1,'website_email' => 'test@example.com']);

        $response = $this->actingAs($user)->post('/en/resources/comment', [
            'userid' => $user->id,
            'resource_id' => $resource->id,
            'comment' => 'This is a test comment.',
        ]);

        $response->assertRedirect('resource/' . $resource->id);
        $response->assertSessionHas('alert.message', __('Your comment is successfully registered. We will publish it after review.'));
        $response->assertSessionHas('alert.level', 'success');

        $this->assertDatabaseHas('resource_comments', [
            'user_id' => $user->id,
            'resource_id' => $resource->id,
            'comment' => 'This is a test comment.',
        ]);

        // Test email sending
        if (config('mail.send_email') == 'yes') {
            Mail::assertSent(\App\Mail\NewComment::class);
        } else {
            Mail::assertNotSent(\App\Mail\NewComment::class);
        }
    }
    
    /**
     * @test
     */
    public function store_creates_a_new_comment_and_redirects(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $user = User::factory()->create();
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->post('/en/resources/comment', [
            'userid' => $user->id,
            'resource_id' => $resource->id,
            'comment' => 'This is a test comment.',
        ]);

        $response->assertRedirect();
        // $response->assertSessionHas('success');

        $this->assertDatabaseHas('resource_comments', [
            'user_id' => $user->id,
            'resource_id' => $resource->id,
            'comment' => 'This is a test comment.',
        ]);
    }


    /**
     * @test
     */
    public function destroy_deletes_comment_and_redirects(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $user = User::factory()->create();
        $user->roles()->attach(5);
        $comment = ResourceComment::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get("en/admin/comments/delete/{$comment->id}");

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals(0, ResourceComment::where('id', $comment->id)->count());
    }

    /**
     * @test
     */
    public function comment_redirects_to_login_if_userid_is_empty(): void
    {
        $this->refreshApplicationWithLocale('en');

        $resource = Resource::factory()->create();

        $response = $this->post('/en/resources/comment', [
            'userid' => '',
            'resource_id' => $resource->id,
            'comment' => 'This is a test comment.',
        ]);

        $response->assertRedirect('en/login');
    }

    /**
     * @test
     */
    public function published_toggles_comment_status_and_redirects(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        // Create an admin user
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        // Create a comment with initial status 0
        $comment = ResourceComment::factory()->create(['status' => 0]);

        // Test publishing the comment
        $response = $this->actingAs($admin)->get("/en/admin/comments/published/{$comment->id}");

        $response->assertRedirect();
        $this->assertEquals(1, $comment->fresh()->status);

        // Test unpublishing the comment
        $response = $this->actingAs($admin)->get("/en/admin/comments/published/{$comment->id}");

        $response->assertRedirect();
        $this->assertEquals(0, $comment->fresh()->status);
    }

    /**
     * @test
     */
    public function published_requires_admin_access(): void
    {
        $this->refreshApplicationWithLocale('en');
        
        $user = User::factory()->create();

        $comment = ResourceComment::factory()->create();

        $response = $this->actingAs($user)->get("/en/admin/comments/published/{$comment->id}");

        $response->assertStatus(302);
    }
}
