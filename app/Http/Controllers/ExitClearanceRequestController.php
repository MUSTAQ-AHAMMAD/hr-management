<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\ExitClearanceRequest;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExitClearanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = ExitClearanceRequest::with(['employee', 'initiatedBy', 'taskAssignments.task'])
            ->latest()
            ->paginate(15);

        return view('exit-clearance-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::whereIn('status', ['active', 'inactive'])->get();

        return view('exit-clearance-requests.create', compact('employees'));
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
        ]);

        $validated['initiated_by'] = Auth::id();
        $validated['status'] = 'pending';

        $exitRequest = ExitClearanceRequest::create($validated);

        return redirect()->route('exit-clearance-requests.show', $exitRequest)
            ->with('success', 'Exit clearance request created successfully. Now assign tasks to complete the clearance.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ExitClearanceRequest $exitClearanceRequest)
    {
        $exitClearanceRequest->load(['employee.department', 'initiatedBy', 'taskAssignments.task.department', 'taskAssignments.assignedTo']);

        return view('exit-clearance-requests.show', compact('exitClearanceRequest'));
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
            $assignee = User::where('department_id', $task->department_id)
                ->whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Department User', 'Admin', 'Super Admin']);
                })
                ->first();

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
        // This would implement PDF generation - simplified for now
        return back()->with('success', 'PDF generation feature will be implemented.');
    }
}
