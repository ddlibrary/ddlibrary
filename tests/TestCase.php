<?php

namespace Tests;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JMac\Testing\Traits\AdditionalAssertions;
use Mcamara\LaravelLocalization\LaravelLocalization;

abstract class TestCase extends BaseTestCase
{
    use AdditionalAssertions;
    protected bool $seed = true;

    /**
     * @throws BindingResolutionException
     */
    protected function refreshApplicationWithLocale($locale): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'='.$locale);
        // $this->refreshApplication();
        app()->make(LaravelLocalization::class)->setLocale($locale);
    }

    protected function tearDown(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDown();
    }
}
