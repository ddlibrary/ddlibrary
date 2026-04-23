<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PrivacyPolicyControllerUzTest extends TestCase
{
    use RefreshDatabase;

    protected string $defaultLocale = 'uz';

    #[Test]
    public function privacy_policy_returns_farsi_with_other_locales_view(): void
    {
        $response = $this->get('uz/privacy-policy');

        $response->assertOk();
        $response->assertViewIs('policies.privacy_fa_web_current');
    }
}
