<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\ExitClearanceRequest;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ExitClearanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ExitClearanceRequest::with(['employee', 'initiatedBy', 'taskAssignments.task']);

        // Filter by employee code or name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function ($q) use ($search) {
                $q->where('employee_code', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->latest()->paginate(15);

        return view('exit-clearance-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::whereIn('status', ['active', 'inactive'])->get();
        $departments = Department::where('is_active', true)->get();

        return view('exit-clearance-requests.create', compact('employees', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'exit_date' => 'required|date',
            'reason' => 'nullable|string',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $validated['initiated_by'] = Auth::id();
        $validated['status'] = 'pending';

        $exitRequest = ExitClearanceRequest::create($validated);

        // Auto-assign tasks if departments are selected
        if (isset($validated['department_ids']) && count($validated['department_ids']) > 0) {
            $this->autoAssignTasksForDepartments($exitRequest, $validated['department_ids']);
        }

        return redirect()->route('exit-clearance-requests.show', $exitRequest)
            ->with('success', 'Exit clearance request created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExitClearanceRequest $exitClearanceRequest)
    {
        $exitClearanceRequest->load([
            'employee.department',
            'employee.assets',
            'initiatedBy',
            'taskAssignments.task.department',
            'taskAssignments.assignedTo'
        ]);

        $availableTasks = Task::where('type', 'exit')
            ->where('is_active', true)
            ->with('department')
            ->get();

        $departments = Department::where('is_active', true)->get();

        return view('exit-clearance-requests.show', compact('exitClearanceRequest', 'availableTasks', 'departments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExitClearanceRequest $exitClearanceRequest)
    {
        $employees = Employee::all();

        return view('exit-clearance-requests.edit', compact('exitClearanceRequest', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExitClearanceRequest $exitClearanceRequest)
    {
        $validated = $request->validate([
            'exit_date' => 'required|date',
            'reason' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,cleared,rejected',
        ]);

        if ($validated['status'] === 'cleared') {
            $validated['clearance_date'] = now();
        }

        $exitClearanceRequest->update($validated);

        return redirect()->route('exit-clearance-requests.show', $exitClearanceRequest)
            ->with('success', 'Exit clearance request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExitClearanceRequest $exitClearanceRequest)
    {
        $exitClearanceRequest->taskAssignments()->delete();
        $exitClearanceRequest->delete();

        return redirect()->route('exit-clearance-requests.index')
            ->with('success', 'Exit clearance request deleted successfully.');
    }

    /**
     * Assign tasks to the exit clearance request.
     */
    public function assignTasks(Request $request, ExitClearanceRequest $exitClearanceRequest)
    {
        $validated = $request->validate([
            'task_ids' => 'required|array',
            'task_ids.*' => 'exists:tasks,id',
        ]);

        foreach ($validated['task_ids'] as $taskId) {
            $task = Task::find($taskId);

            // Find a user from the task's department to assign to
            // Try Department User first, then Admin, then Super Admin
            $assignee = User::where('department_id', $task->department_id)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Department User', 'Admin', 'Super Admin']);
                })
                ->first();

            // If no user found in the specific department, assign to any Admin or Super Admin
            if (!$assignee) {
                $assignee = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Admin', 'Super Admin']);
                })
                ->first();
            }

            if ($assignee) {
                TaskAssignment::create([
                    'task_id' => $taskId,
                    'assigned_to' => $assignee->id,
                    'assignable_type' => ExitClearanceRequest::class,
                    'assignable_id' => $exitClearanceRequest->id,
                    'status' => 'pending',
                    'due_date' => $exitClearanceRequest->exit_date,
                ]);
            }
        }

        $exitClearanceRequest->update(['status' => 'in_progress']);

        return redirect()->route('exit-clearance-requests.show', $exitClearanceRequest)
            ->with('success', 'Tasks assigned successfully.');
    }

    /**
     * Generate PDF clearance document.
     */
    public function generatePdf(ExitClearanceRequest $exitClearanceRequest)
    {
        $exitClearanceRequest->load([
            'employee.department',
            'employee.assets',
            'initiatedBy',
            'taskAssignments.task.department',
            'taskAssignments.assignedTo'
        ]);

        // Check if all tasks are completed
        $pendingTasks = $exitClearanceRequest->taskAssignments()
            ->whereIn('status', ['pending', 'in_progress'])
            ->count();

        if ($pendingTasks > 0) {
            return back()->with('error', 'Cannot generate PDF. Please complete all clearance tasks first.');
        }

        $pdf = PDF::loadView('exit-clearance-requests.pdf', [
            'exitRequest' => $exitClearanceRequest
        ]);

        $filename = 'exit_clearance_' . $exitClearanceRequest->employee->employee_code . '_' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Auto-assign tasks for selected departments.
     */
    private function autoAssignTasksForDepartments(ExitClearanceRequest $exitRequest, array $departmentIds)
    {
        foreach ($departmentIds as $departmentId) {
            $tasks = Task::where('department_id', $departmentId)
                ->where('type', 'exit')
                ->where('is_active', true)
                ->get();

            foreach ($tasks as $task) {
                // Find a user from the task's department to assign to
                // Try Department User first, then Admin, then Super Admin
                $assignee = User::where('department_id', $task->department_id)
                    ->whereHas('roles', function ($query) {
                        $query->whereIn('name', ['Department User', 'Admin', 'Super Admin']);
                    })
                    ->first();

                // If no user found in the specific department, assign to any Admin or Super Admin
                if (!$assignee) {
                    $assignee = User::whereHas('roles', function ($query) {
                        $query->whereIn('name', ['Admin', 'Super Admin']);
                    })
                    ->first();
                }

                if ($assignee) {
                    TaskAssignment::create([
                        'task_id' => $task->id,
                        'assigned_to' => $assignee->id,
                        'assignable_type' => ExitClearanceRequest::class,
                        'assignable_id' => $exitRequest->id,
                        'status' => 'pending',
                        'due_date' => $exitRequest->exit_date,
                    ]);
                }
            }
        }

        if (count($departmentIds) > 0) {
            $exitRequest->update(['status' => 'in_progress']);
        }
    }
}
