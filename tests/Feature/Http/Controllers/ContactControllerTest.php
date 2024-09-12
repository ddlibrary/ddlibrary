<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Contact;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ContactController
 */
class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $response = $this->actingAs($admin)->get('en/contact-us');

        $response->assertViewIs('contacts.contacts_view');
        $response->assertViewHas('email');
        $response->assertViewHas('fullname');
    }

    /**
     * @test
     */
    public function delete_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $contact = Contact::factory()->create();

        $response = $this->actingAs($admin)->get("en/admin/contacts/delete/{$contact->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);
        $contacts = Contact::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('en/admin/contacts');

        $response->assertOk();
        $response->assertViewIs('admin.contacts.contact_list');
        $response->assertViewHas('records');
        $this->assertCount(3, $response->viewData('records'));
    }

    /**
     * @test
     */
    public function read_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $contact = Contact::factory()->create();

        $response = $this->actingAs($admin)->get("en/admin/contacts/read/$contact->id");

    $response->assertRedirect();
    $response->assertSessionHas('success');
    $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function store_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        $admin = User::factory()->create();
        $response = $this->actingAs($admin)->post('en/contact-us', [
            'name' => 'John Doe',
            'subject' => 'Test Subject',
            'email' => 'test@example.com',
            'message' => 'This is a test message',
        ]);

        $response->assertRedirect('/contact-us')
                 ->assertSessionHas('success', 'We received your message and will contact you back soon!');
    }
}
