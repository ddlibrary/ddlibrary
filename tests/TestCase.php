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

    protected string $defaultLocale = 'en';

    protected function setUp(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'='.$this->defaultLocale);
        parent::setUp();
        $this->withoutVite();
    }

    /**
     * @throws BindingResolutionException
     */
    protected function refreshApplicationWithLocale(string $locale): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY.'='.$locale);
        app()->make(LaravelLocalization::class)->setLocale($locale);
    }

    protected function tearDown(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDown();
    }
}
