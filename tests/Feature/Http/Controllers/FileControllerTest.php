<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DdlFile;
use App\Models\User;
use App\Models\Resource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FileController
 */
class FileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function invoke_returns_an_ok_response(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $resource = Resource::factory()->create();
        $file = DdlFile::factory()->create(['name' => 'test.pdf']);

        $response = $this->actingAs($admin)->get(url("en/storage/{$resource->id}/{$file->id}/{$file->name}"));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'inline; filename="test.pdf"');
    }

    /**
     * @test
     */
    public function invoke_aborts_with_a_404_for_non_existent_file(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get(url('storage/999/999/nonexistent.pdf'));

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function invoke_aborts_with_a_403_for_unauthorized_user(): void
    {
        $this->refreshApplicationWithLocale('en');

        $user = User::factory()->create();
        $resource = Resource::factory()->create();
        $file = File::factory()->create();

        $response = $this->actingAs($user)->get(url("storage/{$resource->id}/{$file->id}/{$file->name}"));

        $response->assertForbidden();
    }
}
