<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    #[Test]
    public function test_basic_test(): void
    {
        $response = $this->get('/en');

        $response->assertSuccessful();
    }
}
