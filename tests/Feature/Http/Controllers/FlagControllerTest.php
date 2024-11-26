<?php

namespace Tests\Feature\Http\Controllers;

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
    public function index_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $resourceFlags = \App\Models\ResourceFlag::factory()->times(3)->create();

        $response = $this->get('admin/flags');

        $response->assertOk();
        $response->assertViewIs('admin.flags.flags_list');
        $response->assertViewHas('flags');

        // TODO: perform additional assertions
    }

    // test cases...
}
