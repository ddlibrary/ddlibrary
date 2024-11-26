<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SettingController
 */
class SettingControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $setting = \App\Models\Setting::factory()->create();

        $response = $this->get('admin/settings');

        $response->assertOk();
        $response->assertViewIs('admin.settings.settings_view');
        $response->assertViewHas('setting', $setting);

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $setting = \App\Models\Setting::factory()->create();

        $response = $this->post(route('settings'), [
            // TODO: send request data
        ]);

        $response->assertRedirect('/admin/settings');

        // TODO: perform additional assertions
    }

    // test cases...
}
