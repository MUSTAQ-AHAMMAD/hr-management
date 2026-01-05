<?php

namespace App\Http\Controllers;

use App\Models\TaskAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskAssignmentController extends Controller
{
    /**
     * Show the my tasks page.
     */
    public function myTasks()
    {
        $user = Auth::user();
        
        // Get onboarding task assignments
        $onboardingTasks = TaskAssignment::with(['task.department', 'assignable'])
            ->where('assigned_to', $user->id)
            ->whereHas('task', function ($query) {
                $query->where('type', 'onboarding');
            })
            ->latest()
            ->get();
        
        // Get exit clearance task assignments
        $exitTasks = TaskAssignment::with(['task.department', 'assignable'])
            ->where('assigned_to', $user->id)
            ->whereHas('task', function ($query) {
                $query->where('type', 'exit');
            })
            ->latest()
            ->get();

        return view('my-tasks', compact('onboardingTasks', 'exitTasks'));
    }

    /**
     * Update the status of a task assignment.
     */
    public function updateStatus(Request $request, TaskAssignment $taskAssignment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,rejected',
            'notes' => 'nullable|string',
            'rejection_reason' => 'required_if:status,rejected|nullable|string',
        ]);

        if ($validated['status'] === 'completed') {
            $validated['completed_date'] = now();
        }

        $taskAssignment->update($validated);

        // Update parent request status if all tasks are completed
        if ($validated['status'] === 'completed') {
            $assignable = $taskAssignment->assignable;
            if ($assignable) {
                $allCompleted = $assignable->taskAssignments()->where('status', '!=', 'completed')->count() === 0;
                if ($allCompleted) {
                    // For ExitClearanceRequest, use 'cleared' status, otherwise 'completed'
                    $statusToSet = $assignable instanceof \App\Models\ExitClearanceRequest ? 'cleared' : 'completed';
                    
                    $updateData = ['status' => $statusToSet];
                    
                    // Add clearance_date for ExitClearanceRequest, actual_completion_date for others
                    if ($assignable instanceof \App\Models\ExitClearanceRequest) {
                        $updateData['clearance_date'] = now();
                        
                        // Revoke employee access
                        $employee = $assignable->employee;
                        if ($employee && $employee->user) {
                            $employee->user->update(['status' => 'inactive']);
                        }
                    } else {
                        $updateData['actual_completion_date'] = now();
                    }
                    
                    $assignable->update($updateData);
                }
            }
        }

        return back()->with('success', 'Task status updated successfully.');
    }

    /**
     * Partially close a task assignment.
     */
    public function partiallyClose(Request $request, TaskAssignment $taskAssignment)
    {
        $validated = $request->validate([
            'partial_closure_reason' => 'required|string',
            'notify_on_availability' => 'boolean',
        ]);

        $taskAssignment->update([
            'is_partially_closed' => true,
            'partial_closure_date' => now(),
            'partial_closure_reason' => $validated['partial_closure_reason'],
            'notify_on_availability' => $validated['notify_on_availability'] ?? false,
        ]);

        return back()->with('success', 'Task partially closed. It can be reopened when assets are available.');
    }

    /**
     * Reopen a partially closed task.
     */
    public function reopenTask(Request $request, TaskAssignment $taskAssignment)
    {
        if (!$taskAssignment->is_partially_closed) {
            return back()->with('error', 'This task is not partially closed.');
        }

        $taskAssignment->update([
            'is_partially_closed' => false,
            'partial_closure_date' => null,
            'partial_closure_reason' => null,
        ]);

        return back()->with('success', 'Task reopened successfully.');
    }

    /**
     * Show employee task assignments page with search functionality.
     */
    public function employeeAssignments(Request $request)
    {
        $employeeCode = $request->input('employee_code');
        $employees = collect();

        if ($employeeCode) {
            // Search for employees by employee code
            $employees = \App\Models\Employee::with(['department'])
                ->where('employee_code', 'like', "%{$employeeCode}%")
                ->orWhere('first_name', 'like', "%{$employeeCode}%")
                ->orWhere('last_name', 'like', "%{$employeeCode}%")
                ->get();
        }

        return view('task-assignments.employee-assignments', compact('employees', 'employeeCode'));
    }

    /**
     * Show task assignments for a specific employee.
     */
    public function employeeDetail($employeeId)
    {
        $employee = \App\Models\Employee::with(['department'])->findOrFail($employeeId);
        
        // Get all task assignments for this employee (through onboarding and exit requests)
        $onboardingAssignments = TaskAssignment::with(['task.department', 'assignable', 'assignedTo'])
            ->whereHasMorph('assignable', [\App\Models\OnboardingRequest::class], function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        $exitAssignments = TaskAssignment::with(['task.department', 'assignable', 'assignedTo'])
            ->whereHasMorph('assignable', [\App\Models\ExitClearanceRequest::class], function ($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('task-assignments.employee-detail', compact('employee', 'onboardingAssignments', 'exitAssignments'));
    }
}
