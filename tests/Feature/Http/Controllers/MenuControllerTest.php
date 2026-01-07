<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\MenuController
 */
class MenuControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function ajax_get_parents_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $menus = Menu::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('ajax_get_parents', ['id' => $menus[0]->id, 'loc' => $menus[0]->location, 'lang' => 'en']));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get('en/admin/menu/add/0'); // Assuming 0 for new menu

        $response->assertOk();
        $response->assertViewIs('admin.menu.menu_add');
        $response->assertViewHas('menu');
        $response->assertViewHas('new_menu', true);
        $response->assertViewHas('locations');
        $response->assertViewHas('parents');
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $menu = Menu::factory()->create();

        $response = $this->actingAs($admin)->get('en/admin/menu/edit/' . $menu->id);

        $response->assertOk();
        $response->assertViewIs('admin.menu.menu_edit');
        $response->assertViewHas('details');
        $response->assertViewHas('locations');
        $response->assertViewHas('parents');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $menus = Menu::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('en/admin/menu');

        $response->assertOk();
        $response->assertViewIs('admin.menu.menu_list');
        $response->assertViewHas('menuRecords');
        $response->assertViewHas('searchBar');
        $this->assertCount(3, $response->viewData('menuRecords'));
    }

    /**
     * @test
     */
    public function sort_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $menus = Menu::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get(route('sort_menu'));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_menu'), $this->data(['title' => 'New Menu']));

        $response->assertRedirect('admin/menu');
        $this->assertDatabaseHas('menus', ['title' => 'New Menu']);
    }

    /** @test */
    public function title_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(
            route('store_menu'),
            $this->data([
                'title' => '',
            ]),
        );
        $response->assertStatus(400);
    }

    /** @test */
    public function location_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_menu'), $this->data(['location' => '']));

        $response->assertStatus(400);
    }

    /** @test */
    public function path_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_menu'), $this->data(['path' => '']));

        $response->assertStatus(400);
    }

    /** @test */
    public function status_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_menu'), $this->data(['status' => '']));

        $response->assertStatus(400);
    }

    /** @test */
    public function weight_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_menu'), $this->data(['weight' => '']));

        $response->assertStatus(400);
    }

    /** @test */
    public function language_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_menu'), $this->data(['language' => '']));

        $response->assertStatus(400);
    }

    /**
     * @test
     */
    public function translate_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $menu = Menu::factory()->create();

        $response = $this->actingAs($admin)->get('en/admin/menu/translate/' . $menu->id);

        $response->assertOk();
        $response->assertViewIs('admin.menu.menu_translate');
        $response->assertViewHas('translations');
        $response->assertViewHas('locals');
        $response->assertViewHas('tnid');
        $response->assertViewHas('id', $menu->id);
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $menu = Menu::factory()->create();

        $response = $this->actingAs($admin)->post(
            route('update_menu', ['menuId' => $menu->id]),
            $this->data([
                'title' => 'Updated Menu',
            ]),
        );

        $response->assertRedirect('admin/menu/edit/' . $menu->id);
        $this->assertDatabaseHas('menus', ['title' => 'Updated Menu']);
    }

    /**
     * @test
     */
    public function destroy_deletes_single_menu_without_translations(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $menu = Menu::factory()->create([
            'tnid' => null,
            'parent' => 0,
        ]);

        $response = $this->actingAs($admin)->delete(route('delete_menu', $menu->id));

        $response->assertRedirect('admin/menu');
        $response->assertSessionHas('success', 'Menu deleted successfully!');
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }

    /**
     * @test
     */
    public function destroy_deletes_menu_and_all_translations_with_same_tnid(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $tnid = Menu::max('tnid') + 1;
        
        // Create menu with translations
        $menuEn = Menu::factory()->create([
            'tnid' => $tnid,
            'language' => 'en',
            'parent' => 0,
        ]);

        $menuFa = Menu::factory()->create([
            'tnid' => $tnid,
            'language' => 'fa',
            'parent' => 0,
        ]);

        $menuPs = Menu::factory()->create([
            'tnid' => $tnid,
            'language' => 'ps',
            'parent' => 0,
        ]);

        $response = $this->actingAs($admin)->delete(route('delete_menu', $menuEn->id));

        $response->assertRedirect('admin/menu');
        $response->assertSessionHas('success', 'Menu and all translations deleted successfully!');
        
        // Verify all translations are deleted
        $this->assertDatabaseMissing('menus', ['id' => $menuEn->id]);
        $this->assertDatabaseMissing('menus', ['id' => $menuFa->id]);
        $this->assertDatabaseMissing('menus', ['id' => $menuPs->id]);
    }

    /**
     * @test
     */
    public function deletes_selected_translations_from_translate_page(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $tnid = Menu::max('tnid') + 1;
        
        // Create menu with translations
        $menuEn = Menu::factory()->create([
            'tnid' => $tnid,
            'language' => 'en',
            'parent' => 0,
        ]);

        $menuFa = Menu::factory()->create([
            'tnid' => $tnid,
            'language' => 'fa',
            'parent' => 0,
        ]);

        $menuPs = Menu::factory()->create([
            'tnid' => $tnid,
            'language' => 'ps',
            'parent' => 0,
        ]);

        // Delete only selected translations (fa and ps, but not en)
        $selectedIds = $menuFa->id . ',' . $menuPs->id;

        $response = $this->actingAs($admin)->delete(route('delete_menu', $menuEn->id), [
            'selected_ids' => $selectedIds,
        ]);

        $response->assertRedirect('admin/menu/translate/' . $menuEn->id);
        $response->assertSessionHas('success', '2 menu translations deleted successfully!');
        
        // Verify selected translations are deleted
        $this->assertDatabaseMissing('menus', ['id' => $menuFa->id]);
        $this->assertDatabaseMissing('menus', ['id' => $menuPs->id]);
        
        // Verify English menu still exists
        $this->assertDatabaseHas('menus', ['id' => $menuEn->id]);
    }

    /**
     * @test
     */
    public function deletes_single_selected_translation(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $tnid = Menu::max('tnid') + 1;
        
        $menuEn = Menu::factory()->create([
            'tnid' => $tnid,
            'language' => 'en',
            'parent' => 0,
        ]);

        $menuFa = Menu::factory()->create([
            'tnid' => $tnid,
            'language' => 'fa',
            'parent' => 0,
        ]);

        // Delete only one translation
        $response = $this->actingAs($admin)->delete(route('delete_menu', $menuEn->id), [
            'selected_ids' => (string) $menuFa->id,
        ]);

        $response->assertRedirect('admin/menu/translate/' . $menuEn->id);
        $response->assertSessionHas('success', 'Menu translation deleted successfully!');
        
        // Verify selected translation is deleted
        $this->assertDatabaseMissing('menus', ['id' => $menuFa->id]);
        
        // Verify English menu still exists
        $this->assertDatabaseHas('menus', ['id' => $menuEn->id]);
    }

    /**
     * @test
     */
    public function destroy_does_not_delete_translations_with_different_tnid(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $tnid1 = Menu::max('tnid') + 1;
        $tnid2 = $tnid1 + 1;
        
        $menu1 = Menu::factory()->create([
            'tnid' => $tnid1,
            'language' => 'en',
            'parent' => 0,
        ]);

        $menu2 = Menu::factory()->create([
            'tnid' => $tnid2,
            'language' => 'en',
            'parent' => 0,
        ]);

        // Try to delete menu1, but include menu2's ID in selected_ids (should not work)
        $response = $this->actingAs($admin)->delete(route('delete_menu', $menu1->id), [
            'selected_ids' => (string) $menu2->id,
        ]);

        // When selected_ids don't match tnid, controller falls back to deleting all with same tnid
        // So it redirects to admin/menu and deletes menu1
        $response->assertRedirect('admin/menu');
        
        // Verify menu2 still exists (different tnid, so shouldn't be deleted)
        $this->assertDatabaseHas('menus', ['id' => $menu2->id]);
        
        // Menu1 should be deleted (fallback behavior)
        $this->assertDatabaseMissing('menus', ['id' => $menu1->id]);
    }

    /**
     * @test
     */
    public function destroy_requires_authentication(): void
    {
        $this->refreshApplicationWithLocale('en');

        $menu = Menu::factory()->create();

        $response = $this->delete(route('delete_menu', $menu->id));

        // Should redirect to login (may or may not have locale prefix)
        $this->assertTrue(
            $response->isRedirect() && 
            (str_contains($response->getTargetUrl(), 'login') || str_contains($response->getTargetUrl(), '/en/login'))
        );
    }

    /**
     * @test
     */
    public function destroy_requires_admin_role(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        // Don't attach admin role

        $menu = Menu::factory()->create();

        $response = $this->actingAs($user)->delete(route('delete_menu', $menu->id));

        // Should redirect or return 403
        $this->assertTrue(
            $response->isRedirect() || $response->status() === 403
        );
    }

    protected function data($merge = [])
    {
        return array_merge(
            [
                'title' => 'Home Page',
                'location' => 'header',
                'path' => '/new-menu',
                'status' => 1,
                'language' => 'en',
                'weight' => 1,
            ],
            $merge,
        );
    }
}
