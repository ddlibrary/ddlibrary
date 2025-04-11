<?php

namespace Tests\Unit\Resource;

use App\Models\Resource;
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
        $this->assertEquals('Resource successfully added!', Session::get('alert.message'));
        $this->assertEquals('success', Session::get('alert.level'));
    }

    
}