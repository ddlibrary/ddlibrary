<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
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
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $setting = Setting::factory()->create();
        $response = $this->get('en/admin/settings');

        $response->assertOk();
        $response->assertViewIs('admin.settings.settings_view');
        $response->assertViewHas(['setting']);
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $setting = Setting::factory()->create();

        $response = $this->post('en/admin/settings/'.$setting->id, [
            '_method' => 'PUT',
            'website_name' => 'Darakht-e Danesh Library',
            'website_slogan' => 'Free and open educational resources for Afghanistan',
            'website_email' => 'support@darakhtdanesh.org',
        ]);

        $response->assertRedirect('/admin/settings');
        $this->assertDatabaseHas('settings', [
            'id' => $setting->id,
            'website_name' => 'Darakht-e Danesh Library',
            'website_slogan' => 'Free and open educational resources for Afghanistan',
            'website_email' => 'support@darakhtdanesh.org',
        ]);
    }

    /**
     * @test
     */
    public function update_fails_without_required_fields(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $setting = Setting::factory()->create();

        $response = $this->post('en/admin/settings/'.$setting->id, [
            '_method' => 'PUT',
            'website_name' => '',
            'website_slogan' => '',
            'website_email' => '',
        ]);

        $response->assertSessionHasErrors(['website_name', 'website_slogan', 'website_email']);
    }
}
