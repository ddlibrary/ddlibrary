<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Exception;
use Sentry\State\HubInterface;
use Sentry\SentrySdk;
use Mockery;

class SentryIntegrationTest extends TestCase
{
    #[Test]
    public function test_reports_exceptions_to_sentry()
    {
        // 1. Arrange: Define a route that throws an exception
        Route::get('/_test-sentry-exception', function () {
            throw new Exception('Test Sentry Exception');
        });

        // 2. Mock the Sentry Hub
        $hub = Mockery::mock(HubInterface::class);
        $hub->shouldReceive('captureException')
            ->once()
            ->withArgs(function ($exception) {
                return $exception instanceof Exception && $exception->getMessage() === 'Test Sentry Exception';
            });

        // Ensure other things on the hub are handled
        $hub->shouldReceive('pushScope');
        $hub->shouldReceive('popScope');
        $hub->shouldReceive('configureScope');
        $hub->shouldReceive('getIntegration')->andReturnNull();
        $hub->shouldReceive('setTransaction');
        $hub->shouldReceive('getClient')->andReturnNull();
        $hub->shouldReceive('getScope')->andReturn(Mockery::mock(\Sentry\State\Scope::class));
        $hub->shouldReceive('addBreadcrumb');
        $hub->shouldIgnoreMissing();

        // Inject the mock hub
        SentrySdk::setCurrentHub($hub);

        // 3. Act: Hit the route
        $this->get('/_test-sentry-exception');
    }
}
