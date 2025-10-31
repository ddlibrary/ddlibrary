<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceFlag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FlagController
 */
class FlagControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response()
    {
        $this->refreshApplicationWithLocale('en');
        
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        
        ResourceFlag::factory()->count(15)->create();
        $response = $this->actingAs($admin)->get(url("en/admin/resources/flags"));
        
        $response->assertOk();
        $response->assertViewIs('admin.flags.flags_list');
        $response->assertViewHas('flags');
        $this->assertCount(10, $response->viewData('flags'));
    }
}
