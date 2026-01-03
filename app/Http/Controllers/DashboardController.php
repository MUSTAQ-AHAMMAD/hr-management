<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\ExitClearanceRequest;
use App\Models\OnboardingRequest;
use App\Models\TaskAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get database-specific date format SQL for grouping by month.
     */
    private function getDateFormatSql(): string
    {
        $driver = DB::connection()->getDriverName();

        return match ($driver) {
            'mysql', 'mariadb' => "DATE_FORMAT(created_at, '%Y-%m')",
            'pgsql' => "TO_CHAR(created_at, 'YYYY-MM')",
            'sqlsrv' => "FORMAT(created_at, 'yyyy-MM')",
            default => "strftime('%Y-%m', created_at)", // SQLite and others
        };
    }

    public function index()
    {
        $user = Auth::user();

        // Redirect employees to their specific dashboard
        if ($user->hasRole('Employee')) {
            return redirect()->route('employee-dashboard');
        }

        // Get statistics based on user role
        $stats = [
            'total_employees' => Employee::count(),
            'active_employees' => Employee::where('status', 'active')->count(),
            'pending_onboarding' => OnboardingRequest::where('status', 'pending')->count(),
            'pending_exit_clearance' => ExitClearanceRequest::where('status', 'pending')->count(),
        ];

        // Get tasks assigned to current user
        $myTasks = TaskAssignment::with(['task', 'assignable'])
            ->where('assigned_to', $user->id)
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->limit(10)
            ->get();

        // Get recent onboarding requests
        $recentOnboarding = OnboardingRequest::with(['employee', 'initiatedBy'])
            ->latest()
            ->limit(5)
            ->get();

        // Get recent exit clearance requests
        $recentExitClearance = ExitClearanceRequest::with(['employee', 'initiatedBy'])
            ->latest()
            ->limit(5)
            ->get();

        // Get department-wise statistics
        $departmentStats = Department::withCount([
            'employees',
            'tasks',
        ])->get();

        // Monthly trends for charts
        // Use database-agnostic date formatting
        $dateFormat = $this->getDateFormatSql();

        $onboardingTrend = OnboardingRequest::selectRaw("{$dateFormat} as month, COUNT(*) as count")
            ->groupBy(DB::raw($dateFormat))
            ->orderBy(DB::raw($dateFormat), 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        $exitTrend = ExitClearanceRequest::selectRaw("{$dateFormat} as month, COUNT(*) as count")
            ->groupBy(DB::raw($dateFormat))
            ->orderBy(DB::raw($dateFormat), 'desc')
            ->limit(6)
            ->get()
            ->reverse()
            ->values();

        return view('dashboard', compact(
            'stats',
            'myTasks',
            'recentOnboarding',
            'recentExitClearance',
            'departmentStats',
            'onboardingTrend',
            'exitTrend'
        ));
    }
}
