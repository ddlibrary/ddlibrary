<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

/**
 * @see \App\Http\Controllers\FileController
 */
class FileControllerTest extends TestCase
{
    /**
     * @test
     */
    public function invoke_returns_an_ok_response(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        $response = $this->get('storage/{resource_id}/{file_id}/{file_name}');

        $response->assertOk();

        // TODO: perform additional assertions
    }

    /**
     * @test
     */
    public function invoke_aborts_with_a_404(): void
    {
        $this->markTestIncomplete('This test case was generated by Shift. When you are ready, remove this line and complete this test case.');

        // TODO: perform additional setup to trigger `abort(404)`...

        $response = $this->get('storage/{resource_id}/{file_id}/{file_name}');

        $response->assertNotFound();
    }

    // test cases...
}
