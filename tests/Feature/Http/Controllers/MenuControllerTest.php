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
    public function en_title_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_menu'), $this->data([
            'title' => '',
        ]),);
        $response->assertStatus(400);
    }

    /** @test */
    public function en_location_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('store_menu'), $this->data(['location' => '']));

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

        $response = $this->actingAs($admin)->post(route('update_menu', ['menuId' => $menu->id]), $this->data([
            'title' => 'Updated Menu'
        ]));

        $response->assertRedirect('admin/menu/edit/' . $menu->id);
        $this->assertDatabaseHas('menus', ['title' => 'Updated Menu']);
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
