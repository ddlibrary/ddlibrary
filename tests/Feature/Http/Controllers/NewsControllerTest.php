<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\News;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\NewsController
 */
class NewsControllerTest extends TestCase
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

        $response = $this->actingAs($admin)->get('en/admin/news');

        $response->assertOk();
        $response->assertViewIs('admin.news.news_list');
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

        $news = News::factory()->create();

        $response = $this->post(route('add_news_translate', ['newsId' => $news->id, 'lang' => 'en']), [
            'title' => 'Translated Title',
            'summary' => 'Translated Summary',
            'body' => 'Translated Body',
            'published' => 1,
        ]);

        $this->assertDatabaseHas('news', [
            'title' => 'Translated Title',
            'summary' => 'Translated Summary',
            'body' => 'Translated Body',
        ]);
    }

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get('en/news/create');

        $response->assertOk();
        $response->assertViewIs('news.news_create');
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

        $response = $this->post(route('add_news'), $this->data());

        $response->assertRedirect();
        $this->assertDatabaseHas('news', ['title' => 'Sample News Title']);
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

        $news = News::factory()->create();

        $response = $this->get('en/news/edit/' . $news->id);

        $response->assertOk();
        $response->assertViewIs('news.news_edit');
        $response->assertViewHas('news', $news);
    }

    /**
     * @test
     */
    public function get_news_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);

        $response = $this->get(route('getnews'));

        $response->assertOk();
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

        $news = News::factory()->create();

        $response = $this->get('en/news/translate/' . $news->id . '/' . $news->tnid);

        $response->assertOk();
        $response->assertViewIs('news.news_translate');
        $response->assertViewHas('news');
        $response->assertViewHas('news_self');
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
        $news = News::factory()->create();

        $response = $this->post(route('update_news', ['newsId' => $news->id]), [
            'title' => 'Updated News Title',
            'language' => 'en',
            'summary' => 'Updated summary.',
            'body' => 'Updated body content.',
            'published' => 1,
        ]);

        $response->assertRedirect('news/' . $news->id);
        $this->assertDatabaseHas('news', ['title' => 'Updated News Title']);
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

        $news = News::factory()->create();

        $response = $this->get('en/news/' . $news->id);

        $response->assertOk();
        $response->assertViewIs('news.news_view');
        $response->assertViewHas('news', $news);
        $response->assertViewHas('translations');
    }

    /** @test */
    public function title_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_news'), $this->data(['title' => '']));

        $response->assertSessionHasErrors(['title' => 'The title field is required.']);
    }

    /** @test */
    public function language_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_news'), $this->data(['language' => '']));

        $response->assertSessionHasErrors(['language' => 'The language field is required.']);
    }

    /** @test */
    public function summary_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_news'), $this->data(['summary' => '']));

        $response->assertSessionHasErrors(['summary' => 'The summary field is required.']);
    }

    /** @test */
    public function body_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_news'), $this->data(['body' => '']));

        $response->assertSessionHasErrors(['body' => 'The body field is required.']);
    }

    /** @test */
    public function published_field_must_be_an_integer()
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->post(route('add_news'), $this->data(['published' => '']));

        $response->assertSessionHasErrors(['published' => 'The published field must be an integer.']);
    }

    /** @test */
    public function update_title_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);
        $news = News::factory()->create();

        $response = $this->post(
            route('update_news', ['newsId' => $news->id]),
            $this->data([
                'title' => '',
                'summary' => 'Updated summary.',
            ]),
        );

        $response->assertSessionHasErrors(['title' => 'The title field is required.']);
    }

    /** @test */
    public function update_language_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $this->actingAs($admin);
        $news = News::factory()->create();

        $response = $this->post(
            route('update_news', ['newsId' => $news->id]),
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
        $news = News::factory()->create();

        $response = $this->post(
            route('update_news', ['newsId' => $news->id]),
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
                'title' => 'Sample News Title',
                'language' => 'en',
                'summary' => 'This is a summary of the news.',
                'body' => 'This is the body of the news.',
                'published' => 1,
            ],
            $merge,
        );
    }
}
