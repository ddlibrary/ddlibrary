<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Resource;
use App\Models\Role;
use App\Models\TaxonomyVocabulary;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function delete_user_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get("en/admin/user/delete/$admin->id");

        $response->assertRedirect();

        $this->assertEquals(0, User::find($admin->id));
    }

    /**
     * @test
     */
    public function edit_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get(route('edit_user', ['userId' => $user->id]));

        $response->assertOk();
        $response->assertViewIs('admin.users.edit_user');
        $response->assertViewHas('user', $user);
        $response->assertViewHas('countries');
        $response->assertViewHas('provinces');
        $response->assertViewHas('userRoles');
        $response->assertViewHas('roles');
    }

    /**
     * @test
     */
    public function export_users_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        User::factory()->times(4)->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get('en/admin/user/export');

        $response->assertOk();
    }

    /**
     * @test
     */
    public function favorites_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(6);
        $resource = Resource::factory()->create();

        $response = $this->actingAs($user)->get(route('user-favorites'));

        $response->assertOk();
        $response->assertViewIs('users.favorites');
        $response->assertViewHas('user');
        $response->assertViewHas('page');
        $response->assertViewHas('resources');
    }

    /**
     * @test
     */
    public function index_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');
        $roles = Role::factory()->times(3)->create();

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $response = $this->actingAs($user)->get('en/admin/users');

        $response->assertOk();
        $response->assertViewIs('admin.users.users');
        $response->assertViewHas('users');
        $response->assertViewHas('roles', $roles);
        $response->assertViewHas('filters');
    }

    /**
     * @test
     */
    public function update_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post("en/admin/user/update/$user->id", [
            'username' => 'username',
            'password' => 'Home@3212_',
            'email' => 'abcd@email.com',
            'status' => '1',
            'first_name' => 'Ahmad',
            'last_name' => 'Ahmadi',
            'gender' => 'Male',
            'role' => '1',
            'country' => 1,
        ]);

        $response->assertRedirect('/admin/user/edit/' . $user->id);
        $updatedUser = User::find($user->id);
        $this->assertEquals('abcd@email.com', $updatedUser->email);
        $this->assertEquals('Ahmadi', $updatedUser->profile->last_name);
    }

    /**
     * @test
     */
    public function update_profile_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('user-profile-update'), [
            'email' => 'new_email@email.com',
            'username' => 'new_username',
        ]);

        $response->assertRedirect(URL('user/profile'));

        $this->assertEquals('new_email@email.com', User::whereId($user->id)->value('email'));
    }

    /**
     * @test
     */
    public function uploaded_resources_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $resources = Resource::factory()->times(3)->create();

        $response = $this->actingAs($user)->get(route('user-uploaded-resources'));

        $response->assertOk();
        $response->assertViewIs('users.uploaded-resources');
        $response->assertViewHas('user');
        $response->assertViewHas('page');
        $response->assertViewHas('resources');
    }

    /**
     * @test
     */
    public function view_user_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $user->roles()->attach(5);

        $resources = Resource::factory()->times(3)->create();

        $response = $this->actingAs($user)->get(route('user-view'));

        $response->assertOk();
        $response->assertViewIs('users.view_user');
        $response->assertViewHas('page');
        $response->assertViewHas('user');
    }

    /**
     * @test
     */
    public function update_gender_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $userProfile = UserProfile::factory()->create(['user_id' => $user->id, 'gender' => null]);

        $response = $this->actingAs($user)->post(route('update.gender'), [
            'gender' => 'Male',
        ]);

        $response->assertRedirect();
        $this->assertEquals('Male', $userProfile->fresh()->gender);
    }

    /**
     * @test
     */
    public function update_gender_validates_required_field(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('update.gender'), []);

        $response->assertSessionHasErrors('gender');
    }

    /**
     * @test
     */
    public function update_gender_validates_allowed_values(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post(route('update.gender'), [
            'gender' => 'Invalid Gender',
        ]);

        $response->assertSessionHasErrors('gender');
    }

    /**
     * @test
     */
    public function update_gender_accepts_all_valid_values(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $userProfile = UserProfile::factory()->create(['user_id' => $user->id]);

        $validGenders = ['Male', 'Female', 'None'];

        foreach ($validGenders as $gender) {
            $response = $this->actingAs($user)->post(route('update.gender'), [
                'gender' => $gender,
            ]);

            $response->assertRedirect();
            $this->assertEquals($gender, $userProfile->fresh()->gender);
        }
    }
}
