<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OnboardingRequest;
use App\Models\ExitClearanceRequest;
use App\Models\Asset;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ExportController extends Controller
{
    /**
     * Export employees to CSV
     */
    public function exportEmployees(Request $request)
    {
        $query = Employee::with(['department', 'user']);

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $employees = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="employees_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($employees) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'Employee Code',
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Department',
                'Job Title',
                'Date of Joining',
                'Status',
                'Has User Account'
            ]);

            // Add data rows
            foreach ($employees as $employee) {
                fputcsv($file, [
                    $employee->employee_code,
                    $employee->first_name,
                    $employee->last_name,
                    $employee->email ?? 'N/A',
                    $employee->phone ?? 'N/A',
                    $employee->department->name ?? 'N/A',
                    $employee->job_title ?? 'N/A',
                    $employee->date_of_joining ? $employee->date_of_joining->format('Y-m-d') : 'N/A',
                    ucfirst($employee->status),
                    $employee->user_id ? 'Yes' : 'No'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export onboarding requests to CSV
     */
    public function exportOnboardingRequests(Request $request)
    {
        $query = OnboardingRequest::with(['employee.department', 'initiatedBy']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="onboarding_requests_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($requests) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Request ID',
                'Employee Code',
                'Employee Name',
                'Department',
                'Initiated By',
                'Status',
                'Expected Completion',
                'Actual Completion',
                'Line Manager',
                'Created At'
            ]);

            foreach ($requests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->employee->employee_code,
                    $request->employee->full_name,
                    $request->employee->department->name ?? 'N/A',
                    $request->initiatedBy->name ?? 'N/A',
                    ucfirst($request->status),
                    $request->expected_completion_date?->format('Y-m-d') ?? 'N/A',
                    $request->actual_completion_date?->format('Y-m-d') ?? 'N/A',
                    $request->line_manager_name ?? 'N/A',
                    $request->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export exit clearance requests to CSV
     */
    public function exportExitClearanceRequests(Request $request)
    {
        $query = ExitClearanceRequest::with(['employee.department', 'initiatedBy']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="exit_clearance_requests_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($requests) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Request ID',
                'Employee Code',
                'Employee Name',
                'Department',
                'Initiated By',
                'Status',
                'Exit Date',
                'Clearance Date',
                'Line Manager Approval',
                'Created At'
            ]);

            foreach ($requests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->employee->employee_code,
                    $request->employee->full_name,
                    $request->employee->department->name ?? 'N/A',
                    $request->initiatedBy->name ?? 'N/A',
                    ucfirst($request->status),
                    $request->exit_date?->format('Y-m-d') ?? 'N/A',
                    $request->clearance_date?->format('Y-m-d') ?? 'N/A',
                    $request->line_manager_approved_at ? 'Approved' : 'Pending',
                    $request->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export assets to CSV
     */
    public function exportAssets(Request $request)
    {
        $query = Asset::with(['employee', 'department', 'assignedBy']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $assets = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="assets_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($assets) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Asset ID',
                'Asset Type',
                'Asset Name',
                'Serial Number',
                'Employee',
                'Department',
                'Status',
                'Acceptance Status',
                'Assigned By',
                'Assigned Date',
                'Value',
                'Condition',
                'Purchase Date'
            ]);

            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->id,
                    $asset->asset_type,
                    $asset->asset_name,
                    $asset->serial_number ?? 'N/A',
                    $asset->employee->full_name ?? 'N/A',
                    $asset->department->name ?? 'N/A',
                    ucfirst($asset->status),
                    ucfirst(str_replace('_', ' ', $asset->acceptance_status ?? 'N/A')),
                    $asset->assignedBy->name ?? 'N/A',
                    $asset->assigned_date?->format('Y-m-d') ?? 'N/A',
                    $asset->asset_value ? '$' . number_format($asset->asset_value, 2) : 'N/A',
                    ucfirst($asset->asset_condition ?? 'N/A'),
                    $asset->purchase_date?->format('Y-m-d') ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export tasks to CSV
     */
    public function exportTasks(Request $request)
    {
        $query = TaskAssignment::with(['task.department', 'assignedTo', 'assignable']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->whereHas('task', function($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }

        $tasks = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tasks_' . date('Y-m-d_H-i-s') . '.csv"',
        ];

        $callback = function() use ($tasks) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Task ID',
                'Task Name',
                'Department',
                'Assigned To',
                'Status',
                'Request Type',
                'Due Date',
                'Completion Date',
                'Is Partially Closed'
            ]);

            foreach ($tasks as $taskAssignment) {
                fputcsv($file, [
                    $taskAssignment->id,
                    $taskAssignment->task->name,
                    $taskAssignment->task->department->name ?? 'N/A',
                    $taskAssignment->assignedTo->name ?? 'N/A',
                    ucfirst($taskAssignment->status),
                    Str::of($taskAssignment->assignable_type)->classBasename(),
                    $taskAssignment->due_date?->format('Y-m-d') ?? 'N/A',
                    $taskAssignment->completed_at?->format('Y-m-d') ?? 'N/A',
                    $taskAssignment->is_partially_closed ? 'Yes' : 'No'
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
