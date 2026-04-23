<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PrivacyPolicyControllerEnTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'en';

    #[Test]
    public function privacy_policy_returns_english_view(): void
    {
        $response = $this->get('en/privacy-policy');
        $response->assertOk();
        $response->assertViewIs('policies.privacy_en_web_current');
    }

    #[Test]
    public function mobile_privacy_policy_returns_expected_view(): void
    {
        $response = $this->get('en/mobile-privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_en_mob_current');
    }

    #[Test]
    public function opt_out_returns_view(): void
    {
        $response = $this->get('en/opt-out');

        $response->assertOk();
        $response->assertViewIs('layouts.opt_out');
    }
}
