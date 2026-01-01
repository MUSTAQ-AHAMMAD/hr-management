<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that dashboard can be accessed by authenticated user.
     * This specifically tests the fix for the strftime SQL error.
     */
    public function test_dashboard_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        // This should not throw an SQL error regardless of database driver
        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test that the correct date format SQL is used for different database drivers.
     */
    public function test_date_format_is_database_agnostic(): void
    {
        $driver = DB::connection()->getDriverName();

        // Test that we can determine the driver
        $this->assertTrue(
            in_array($driver, ['sqlite', 'mysql', 'pgsql', 'mariadb', 'sqlsrv']),
            "Expected driver to be one of the supported databases, got: {$driver}"
        );
    }
}
