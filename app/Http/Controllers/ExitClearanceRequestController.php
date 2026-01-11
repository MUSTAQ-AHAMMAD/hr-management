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
            'line_manager_name' => 'required|string|max:255',
            'line_manager_email' => 'required|email',
            'exit_date' => 'required|date',
            'reason' => 'nullable|string',
            'department_ids' => 'nullable|array',
            'department_ids.*' => 'exists:departments,id',
        ]);

        $validated['initiated_by'] = Auth::id();
        $validated['status'] = 'pending';
        $validated['line_manager_approval_status'] = 'pending';

        \DB::beginTransaction();

        try {
            $exitRequest = ExitClearanceRequest::create($validated);

            // Send email to line manager for approval
            $approvalToken = hash('sha256', \Str::random(64) . $exitRequest->id . now()->timestamp);
            \Cache::put('exit_approval_' . $exitRequest->id, $approvalToken, now()->addDays(7));
            
            try {
                \Mail::to($validated['line_manager_email'])->queue(
                    new \App\Mail\LineManagerApprovalRequest($exitRequest, $approvalToken)
                );
            } catch (\Exception $e) {
                \Log::error('Failed to send line manager approval email for exit request #' . $exitRequest->id . ': ' . $e->getMessage());
            }

            \DB::commit();

            return redirect()->route('exit-clearance-requests.show', $exitRequest)
                ->with('success', 'Exit clearance request created successfully. Line manager approval email has been sent.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->with('error', 'Failed to create exit clearance request for employee #' . $validated['employee_id'] . ': ' . $e->getMessage())
                ->withInput();
        }
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
            'line_manager_name' => 'required|string|max:255',
            'line_manager_email' => 'required|email',
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
     * Show line manager approval page.
     */
    public function lineManagerApprove(Request $request, ExitClearanceRequest $exitClearanceRequest)
    {
        $token = $request->query('token');
        $cachedToken = \Cache::get('exit_approval_' . $exitClearanceRequest->id);

        if (!$token || $token !== $cachedToken) {
            return redirect()->route('exit-clearance-requests.show', $exitClearanceRequest)
                ->with('error', 'Invalid or expired approval link.');
        }

        // If this is a GET request, show the approval page
        if ($request->isMethod('get')) {
            $exitClearanceRequest->load(['employee.department', 'employee.assets', 'initiatedBy']);
            return view('exit-clearance-requests.line-manager-approval', compact('exitClearanceRequest', 'token'));
        }

        // POST request - process the approval
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $exitClearanceRequest->update([
            'line_manager_approval_status' => 'approved',
            'line_manager_approved_at' => now(),
            'line_manager_approval_notes' => $validated['notes'] ?? 'Approved via email link',
        ]);

        \Cache::forget('exit_approval_' . $exitClearanceRequest->id);

        return redirect()->route('exit-clearance-requests.show', $exitClearanceRequest)
            ->with('success', 'Exit clearance request approved. HR can now assign departments for clearance.');
    }

    /**
     * Show line manager reject page.
     */
    public function lineManagerReject(Request $request, ExitClearanceRequest $exitClearanceRequest)
    {
        $token = $request->query('token');
        $cachedToken = \Cache::get('exit_approval_' . $exitClearanceRequest->id);

        if (!$token || $token !== $cachedToken) {
            return redirect()->route('exit-clearance-requests.show', $exitClearanceRequest)
                ->with('error', 'Invalid or expired approval link.');
        }

        // If this is a GET request, show the rejection page
        if ($request->isMethod('get')) {
            $exitClearanceRequest->load(['employee.department', 'employee.assets', 'initiatedBy']);
            return view('exit-clearance-requests.line-manager-rejection', compact('exitClearanceRequest', 'token'));
        }

        // POST request - process the rejection
        $validated = $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $exitClearanceRequest->update([
            'line_manager_approval_status' => 'rejected',
            'line_manager_approved_at' => now(),
            'line_manager_approval_notes' => $validated['notes'],
            'status' => 'rejected',
        ]);

        \Cache::forget('exit_approval_' . $exitClearanceRequest->id);

        return redirect()->route('exit-clearance-requests.show', $exitClearanceRequest)
            ->with('success', 'Exit clearance request has been rejected.');
    }

    /**
     * Assign tasks to the exit clearance request.
     */
    public function assignTasks(Request $request, ExitClearanceRequest $exitClearanceRequest)
    {
        // Check if line manager has approved
        if ($exitClearanceRequest->line_manager_approval_status !== 'approved') {
            return redirect()->route('exit-clearance-requests.show', $exitClearanceRequest)
                ->with('error', 'Cannot assign tasks until line manager approves the exit clearance request.');
        }

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
