<?php

namespace Tests\Unit\Resource;

use App\Models\Resource;
use App\Models\ResourceTranslationLink;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use App\Models\User;

class ResourceControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_add_a_resource_translation_link_when_not_exists()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $resource1 = Resource::create([
            'title' => "Resource 1",
            'abstract' => "abstract content",
            'language' => 'en',
            'status' => 1,
        ]);
        $link = Resource::create([
            'title' => "Resource 2",
            'abstract' => "abstract content",
            'language' => 'en',
            'status' => 1,
        ]);

        $response = $this->actingAs($admin)->post(route('updatetid', $resource1->id), [
            'link_resource_id' => $link->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('resource_translation_links', [
            'resource_id' => $resource1->id,
            'link_resource_id' => $link->id,
        ]);
        $this->assertEquals('Resource successfully linked!', Session::get('alert.message'));
        $this->assertEquals('success', Session::get('alert.level'));
    }

    /** @test */
    public function it_does_not_add_a_resource_translation_link_if_already_exists()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $resource1 = Resource::create([
            'title' => "Resource 1",
            'abstract' => "abstract content",
            'language' => 'en',
            'status' => 1,
        ]);
        $link = Resource::create([
            'title' => "Resource 2",
            'abstract' => "abstract content",
            'language' => 'en',
            'status' => 1,
        ]);

        ResourceTranslationLink::insert([
            'resource_id' => $resource1->id,
            'link_resource_id' => $link->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $$response = $this->actingAs($admin)->post(route('updatetid', $resource1->id), [
            'link_resource_id' => $link->id,
        ]);

        $this->assertDatabaseCount('resource_translation_links', 1);
        $this->assertEquals('This resource already linked!', Session::get('alert.message'));
        $this->assertEquals('danger', Session::get('alert.level'));
    }

     /** @test */
    public function it_validates_the_request()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $resource1 = Resource::create([
            'title' => "Resource 1",
            'abstract' => "abstract content",
            'language' => 'en',
            'status' => 1,
        ]);

        $response = $this->actingAs($admin)->post(route('updatetid', $resource1->id), [
            'link_resource_id' => '',
        ]);

        $response->assertSessionHasErrors('link_resource_id');
    }

    /** @test */
    public function it_does_not_allow_invalid_resource_id()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $resource1 = Resource::create([
            'title' => "Resource 1",
            'abstract' => "abstract content",
            'language' => 'en',
            'status' => 1,
        ]);

        $response = $this->actingAs($admin)->post(route('updatetid', $resource1->id), [
            'link_resource_id' => 444444,
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('link_resource_id');
        $this->assertEquals('The selected link resource id is invalid.', session('errors')->get('link_resource_id')[0]);
    }
    
}