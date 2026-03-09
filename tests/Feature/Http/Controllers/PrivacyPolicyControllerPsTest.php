<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivacyPolicyControllerPsTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'ps';

    /**
     * @test
     */
    public function privacy_policy_returns_pashto_view(): void
    {
        $response = $this->get('ps/privacy-policy');
        $response->assertOk();
        $response->assertViewIs('policies.privacy_ps_web_current');
    }
}
