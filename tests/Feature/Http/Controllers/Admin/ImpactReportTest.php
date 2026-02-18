<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Http\Controllers\ReportController;
use App\Models\Resource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ImpactReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_impact_report_returns_view_with_expected_counts(): void
    {
        $this->withoutMiddleware();

        $from = Carbon::parse('2024-01-01 00:00:00');
        $to   = Carbon::parse('2024-01-31 23:59:59');

        // Resources: 2 in range, 1 out of range
        Resource::factory()->create(['created_at' => Carbon::parse('2024-01-10 12:00:00')]);
        Resource::factory()->create(['created_at' => Carbon::parse('2024-01-20 12:00:00')]);
        Resource::factory()->create(['created_at' => Carbon::parse('2024-02-01 00:00:00')]);

        // Users + profiles:
        // In range: Male, Female, None, NULL
        // Out of range: Male
        $maleIn = User::factory()->create(['created_at' => Carbon::parse('2024-01-05 10:00:00')]);
        $femaleIn = User::factory()->create(['created_at' => Carbon::parse('2024-01-06 10:00:00')]);
        $noneIn = User::factory()->create(['created_at' => Carbon::parse('2024-01-07 10:00:00')]);
        $nullIn = User::factory()->create(['created_at' => Carbon::parse('2024-01-08 10:00:00')]);

        $maleOut = User::factory()->create(['created_at' => Carbon::parse('2024-02-05 10:00:00')]);

        DB::table('user_profiles')->insert([
            ['user_id' => $maleIn->id,   'gender' => 'Male'],
            ['user_id' => $femaleIn->id, 'gender' => 'Female'],
            ['user_id' => $noneIn->id,   'gender' => 'None'],
            ['user_id' => $nullIn->id,   'gender' => null],
            ['user_id' => $maleOut->id,  'gender' => 'Male'],
        ]);

        // Downloads: 3 in range, 1 out of range
        DB::table('download_counts')->insert([
            ['resource_id' => 1234, 'file_id' => 1234, 'created_at' => Carbon::parse('2024-01-03 09:00:00'), 'updated_at' => Carbon::parse('2024-01-03 09:00:00')],
            ['resource_id' => 1234, 'file_id' => 1234, 'created_at' => Carbon::parse('2024-01-15 09:00:00'), 'updated_at' => Carbon::parse('2024-01-15 09:00:00')],
            ['resource_id' => 1234, 'file_id' => 1234, 'created_at' => Carbon::parse('2024-01-31 09:00:00'), 'updated_at' => Carbon::parse('2024-01-31 09:00:00')],
            ['resource_id' => 1234, 'file_id' => 1234, 'created_at' => Carbon::parse('2024-02-01 09:00:00'), 'updated_at' => Carbon::parse('2024-02-01 09:00:00')],
        ]);

        $response = $this->get(action([ReportController::class, 'impactReport'], [
            'from' => $from->toDateTimeString(),
            'to'   => $to->toDateTimeString(),
        ]));

        $response->assertOk();
        $response->assertViewIs('admin.reports.impact_report');

        $response->assertViewHas('resources_count', 2);
        $response->assertViewHas('resources_download_count', 3);

        $response->assertViewHas('registered_users_count');
        $registered = $response->viewData('registered_users_count');

        $this->assertSame(4, (int) $registered->total_users_count);
        $this->assertSame(1, (int) $registered->male_count);
        $this->assertSame(1, (int) $registered->female_count);
        $this->assertSame(1, (int) $registered->undisclosed_count);
        $this->assertSame(1, (int) $registered->unknown_count);
    }

    public function test_impact_report_rejects_invalid_date_range(): void
    {
        $this->withoutMiddleware();

        $response = $this->get(action([ReportController::class, 'impactReport'], [
            'from' => '2024-02-01 00:00:00',
            'to'   => '2024-01-01 00:00:00',
        ]));

        // Laravel validation on a GET typically redirects back (302)
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['from', 'to']);
    }
}
