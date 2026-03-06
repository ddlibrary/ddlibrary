<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyControllerUzTest extends TestCase
{
    use RefreshDatabase;
    protected string $defaultLocale = 'uz';

    /**
     * @test
     */
    public function privacy_policy_returns_farsi_with_other_locales_view(): void
    {
        $response = $this->get('uz/privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_fa_web_current');
    }

}
