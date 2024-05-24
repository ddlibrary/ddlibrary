<?php

namespace Tests\Feature\Auth;

use App\Enums\GenderEnum;
use App\Enums\TaxonomyVocabularyEnum;
use App\Models\Role;
use App\Models\TaxonomyTerm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_can_view_registration_page()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('/en/register');

        $response->assertSuccessful();
        $response->assertViewIs('auth.register');
    }

    /** @test */
    public function user_can_register_with_basic_details()
    {
        $this->refreshApplicationWithLocale('en');
        $country = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::UserCountry->value]);
        $city = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::UserDistricts->value]);
        Role::factory(5)->create();
        $libraryUserRole = Role::factory()->create(['name' => 'library user']);

        $response = $this->from('en/register')->post('en/register', [
            'username' => 'saeidi',
            'password' => 'School@123',
            'password_confirmation' => 'School@123',
            'email' => 'saeidi@email.com',
            'first_name' => 'Azizullah',
            'last_name' => 'Saeidi',
            'gender' => GenderEnum::Male->value,
            'country' => $country->id,
            'city' => $city->id,
        ]);

        $response->assertRedirect('email/verify');

        $user = User::first();

        $this->assertTrue(Auth::user()->is($user));
        $this->assertEquals('Azizullah', $user->profile->first_name);
        $this->assertEquals('Saeidi', $user->profile->last_name);
        $this->assertEquals('saeidi@email.com', $user->email);
        $this->assertEquals('saeidi', $user->username);
        $this->assertEquals($city->id, $user->profile->city);
        $this->assertEquals($country->id, $user->profile->country);
        $this->assertEquals(GenderEnum::Male->value, $user->profile->gender);
        $this->assertEquals($libraryUserRole->name, $user->roles->value('name'));
    }

    /** @test */
    public function name_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'first_name' => '',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors('first_name');
        $this->assertNull(User::first());
    }

    /** @test */
    public function last_name_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'last_name' => '',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors('last_name');
        $this->assertNull(User::first());
    }

    /** @test */
    public function email_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'email' => '',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors('email');
        $this->assertNull(User::first());
    }

    /** @test */
    public function email_should_be_unique()
    {
        $this->refreshApplicationWithLocale('en');

        User::factory()->create(['email' => 'unique@email.com']);
        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'email' => 'unique@email.com',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors(['email' => 'The email has already been taken.']);
        $this->assertCount(1, User::all());
    }

    /** @test */
    public function country_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'country' => '',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors('country');
        $this->assertNull(User::first());
    }

    /** @test */
    public function gender_field_is_required()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'gender' => '',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors('gender');
        $this->assertNull(User::first());
    }

    /** @test */
    public function passwords_must_match()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'password' => 'Right@123',
                'password_confirmation' => 'Wrong@123',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors(['password' => 'The password field confirmation does not match.']);
        $this->assertNull(User::first());
    }

    /** @test */
    public function easy_password_fail()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'password' => '12345678',
                'password_confirmation' => '12345678',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors(['password' => "The password you entered doesn't have any special characters (!@#$%^&.) and (or) digits (0-9)."]);
        $this->assertNull(User::first());
    }

    /** @test */
    public function test_password_must_be_at_least_8_chars_long()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'password' => 'Short@1',
                'password_confirmation' => 'Short@1',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors(['password' => 'The password field must be at least 8 characters.']);
        $this->assertNull(User::first());
    }

    /** @test */
    public function thrown_error_if_password_and_confirmation_do_not_match()
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->from('en/register')->post(
            'en/register',
            $this->data([
                'password' => 'Password@123',
                'password_confirmation' => 'Password@12',
            ]),
        );

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors(['password' => 'The password field confirmation does not match.']);
        $this->assertNull(User::first());
    }

    /** @test */
    public function old_session_inputs()
    {
        $this->refreshApplicationWithLocale('en');

        $country = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::UserCountry->value]);
        $city = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::UserDistricts->value]);

        $response = $this->from('en/register')->post('en/register', [
            'username' => 'saeidi',
            'password' => 'School@123',
            'password_confirmation' => 'School@123',
            'email' => 'saeidi@email.com',
            'first_name' => null,
            'last_name' => 'Saeidi',
            'gender' => GenderEnum::Male->value,
            'country' => $country->id,
            'city' => $city->id,
        ]);

        $response->assertRedirect('en/register');
        $response->assertSessionHasErrors('first_name', 'The first name field is required.');
        $response->assertSessionHas('_old_input', [
            'username' => 'saeidi',
            'email' => 'saeidi@email.com',
            'first_name' => null,
            'last_name' => 'Saeidi',
            'gender' => GenderEnum::Male->value,
            'country' => $country->id,
            'city' => $city->id,
        ]);
        $response->assertSessionMissing('_old_input.password');
    }
    

    protected function data($merge = [])
    {
        $country = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::UserCountry->value]);
        $city = TaxonomyTerm::factory()->create(['vid' => TaxonomyVocabularyEnum::UserDistricts->value]);

        return array_merge(
            [
                'username' => 'saeidi',
                'password' => 'School@123',
                'password_confirmation' => 'School@123',
                'email' => 'saeidi@email.com',
                'first_name' => 'Azizullah',
                'last_name' => 'Saeidi',
                'gender' => GenderEnum::Male->value,
                'country' => $country->id,
                'city' => $city->id,
            ],
            $merge,
        );
    }
}
