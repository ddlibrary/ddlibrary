<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PrivacyPolicyControllerFaTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'fa';

    #[Test]
    public function privacy_policy_defaults_to_farsi_view_for_other_locales(): void
    {
        $response = $this->get('fa/privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_fa_web_current');
    }
}
