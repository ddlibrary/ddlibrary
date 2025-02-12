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
    public function invoke_aborts_with_a_404_for_non_existent_file(): void
    {
        $this->refreshApplicationWithLocale('en');

        $admin = User::factory()->create();
        $admin->roles()->attach(5);

        $response = $this->actingAs($admin)->get(url('storage/999/999/nonexistent.pdf'));

        $response->assertNotFound();
    }
}
