<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PageController
 */
class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get('en/admin/pages');

        $response->assertOk();
        $response->assertViewIs('admin.pages.pages_list');
    }

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get('en/page/create');

        $response->assertOk();
        $response->assertViewIs('pages.page_create');
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $response = $this->post(route('add_page'), $this->data());

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', ['title' => 'Sample Page Title']);
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $page = Page::factory()->create();

        $response = $this->get('en/page/edit/' . $page->id);

        $response->assertOk();
        $response->assertViewIs('pages.page_edit');
        $response->assertViewHas('page', $page);
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
        $page = Page::factory()->create();

        $response = $this->post(route('update_page', ['pageId' => $page->id]), [
            'title' => 'Updated Page Title',
            'language' => 'en',
            'summary' => 'Updated summary.',
            'body' => 'Updated body content.',
            'published' => 1,
        ]);

        $response->assertRedirect('page/' . $page->id);
        $this->assertDatabaseHas('pages', ['title' => 'Updated Page Title']);
    }

    /** @test */
    public function update_title_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);
        $page = Page::factory()->create();

        $response = $this->post(route('update_page', ['pageId' => $page->id]), [
            'title' => '',
            'language' => 'en',
            'summary' => 'Updated summary.',
            'body' => 'Updated body content.',
            'published' => 1,
        ]);

        $response->assertSessionHasErrors(['title' => 'The title field is required.']);
    }

    /**
     * @test
     */
    public function view_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $page = Page::factory()->create();

        $response = $this->get('en/page/' . $page->id);

        $response->assertOk();
        $response->assertViewIs('pages.pages_view');
        $response->assertViewHas('page', $page);
        $response->assertViewHas('translations');
    }

    /**
     * @test
     */
    public function translate_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $page1 = Page::factory()->create();
        $page2 = Page::factory()->create(['tnid' => $page1->id]);

        $response = $this->get('en/page/translate/' . $page2->id . '/' . $page2->tnid);

        $response->assertOk();
        $response->assertViewIs('pages.page_translate');
        $response->assertViewHas('page');
        $response->assertViewHas('page_self');
    }

    /**
     * @test
     */
    public function add_post_translate_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $page = Page::factory()->create();

        $response = $this->post(route('add_page_translate', ['pageId' => $page->id, 'lang' => 'en']), [
            'title' => 'Translated Title',
            'summary' => 'Translated Summary',
            'body' => 'Translated Body',
            'published' => 1,
        ]);

        $this->assertDatabaseHas('pages', ['title' => 'Translated Title']);
    }

    /**
     * @test
     */
    public function get_pages_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $response = $this->get(route('getpages'));

        $response->assertOk();
    }

    /**
     * @test
     */
    public function view_aborts_with_a_403(): void
    {
        $this->refreshApplicationWithLocale('en');

        $page = Page::factory()->create(['status' => 0]); // Create an unpublished page
        $user = User::factory()->create();
        $this->actingAs($user); // Regular user without admin privileges

        $response = $this->get('en/page/' . $page->id);

        $response->assertForbidden();
    }

    /** @test */
    public function title_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_page'), $this->data(['title' => '']));

        $response->assertSessionHasErrors(['title' => 'The title field is required.']);
    }

    /** @test */
    public function language_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_page'), $this->data(['language' => '']));

        $response->assertSessionHasErrors(['language' => 'The language field is required.']);
    }

    /** @test */
    public function summary_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_page'), $this->data(['summary' => '']));

        $response->assertSessionHasErrors(['summary' => 'The summary field is required.']);
    }

    /** @test */
    public function body_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_page'), $this->data(['body' => '']));

        $response->assertSessionHasErrors(['body' => 'The body field is required.']);
    }

    /** @test */
    public function published_field_must_be_an_integer()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_page'), $this->data(['published' => '']));

        $response->assertSessionHasErrors(['published' => 'The published field must be an integer.']);
    }

    /** @test */
    public function update_language_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);
        $page = Page::factory()->create();

        $response = $this->post(
            route('update_page', ['pageId' => $page->id]),
            $this->data([
                'language' => '',
                'summary' => 'Updated summary.',
            ]),
        );

        $response->assertSessionHasErrors(['language' => 'The language field is required.']);
    }

    /** @test */
    public function update_summary_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);
        $page = Page::factory()->create();

        $response = $this->post(
            route('update_page', ['pageId' => $page->id]),
            $this->data([
                'summary' => '',
                'body' => 'Update body.',
            ]),
        );

        $response->assertSessionHasErrors(['summary' => 'The summary field is required.']);
    }

    protected function data($merge = [])
    {
        return array_merge(
            [
                'title' => 'Sample Page Title',
                'language' => 'en',
                'summary' => 'This is a summary of the page.',
                'body' => 'This is the body of the page.',
                'published' => 1,
            ],
            $merge,
        );
    }
}
