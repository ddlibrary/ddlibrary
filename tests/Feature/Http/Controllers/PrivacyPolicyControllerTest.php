<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function privacy_policy_returns_english_view(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('en/privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_en_web_current');
    }

    /**
     * @test
     */
    public function privacy_policy_returns_pashto_view(): void
    {
        $this->refreshApplicationWithLocale('ps');

        $response = $this->get('ps/privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_ps_web_current');
    }

    /**
     * @test
     */
    public function privacy_policy_defaults_to_farsi_view_for_other_locales(): void
    {
        $this->refreshApplicationWithLocale('fa');

        $response = $this->get('fa/privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_fa_web_current');
    }

    /**
     * @test
     */
    public function privacy_policy_returns_farsi_with_other_locales_view(): void
    {
        $this->refreshApplicationWithLocale('uz');

        $response = $this->get('uz/privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_fa_web_current');
    }

    /**
     * @test
     */
    public function mobile_privacy_policy_returns_expected_view(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('en/mobile-privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_en_mob_current');
    }

    /**
     * @test
     */
    public function opt_out_returns_view(): void
    {
        $this->refreshApplicationWithLocale('en');

        $response = $this->get('en/opt-out');

        $response->assertOk();
        $response->assertViewIs('layouts.opt_out');
    }
}
