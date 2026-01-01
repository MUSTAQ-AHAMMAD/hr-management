<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\OnboardingRequest;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = OnboardingRequest::with(['employee', 'initiatedBy', 'taskAssignments.task'])
            ->latest()
            ->paginate(15);

        return view('onboarding-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('status', 'active')
            ->whereDoesntHave('onboardingRequests', function ($query) {
                $query->where('status', '!=', 'completed');
            })
            ->get();

        return view('onboarding-requests.create', compact('employees'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'expected_completion_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $validated['initiated_by'] = Auth::id();
        $validated['status'] = 'pending';

        $onboardingRequest = OnboardingRequest::create($validated);

        return redirect()->route('onboarding-requests.show', $onboardingRequest)
            ->with('success', 'Onboarding request created successfully. Now assign tasks to complete the onboarding.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OnboardingRequest $onboardingRequest)
    {
        $onboardingRequest->load(['employee.department', 'initiatedBy', 'taskAssignments.task.department', 'taskAssignments.assignedTo']);

        return view('onboarding-requests.show', compact('onboardingRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OnboardingRequest $onboardingRequest)
    {
        $employees = Employee::where('status', 'active')->get();

        return view('onboarding-requests.edit', compact('onboardingRequest', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OnboardingRequest $onboardingRequest)
    {
        $validated = $request->validate([
            'expected_completion_date' => 'required|date',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        if ($validated['status'] === 'completed') {
            $validated['actual_completion_date'] = now();
        }

        $onboardingRequest->update($validated);

        return redirect()->route('onboarding-requests.show', $onboardingRequest)
            ->with('success', 'Onboarding request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OnboardingRequest $onboardingRequest)
    {
        $onboardingRequest->taskAssignments()->delete();
        $onboardingRequest->delete();

        return redirect()->route('onboarding-requests.index')
            ->with('success', 'Onboarding request deleted successfully.');
    }

    /**
     * Assign tasks to the onboarding request.
     */
    public function assignTasks(Request $request, OnboardingRequest $onboardingRequest)
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
                    'assignable_type' => OnboardingRequest::class,
                    'assignable_id' => $onboardingRequest->id,
                    'status' => 'pending',
                    'due_date' => $onboardingRequest->expected_completion_date,
                ]);
            }
        }

        $onboardingRequest->update(['status' => 'in_progress']);

        return redirect()->route('onboarding-requests.show', $onboardingRequest)
            ->with('success', 'Tasks assigned successfully.');
    }
}
