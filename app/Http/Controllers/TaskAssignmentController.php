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
        $taskAssignments = TaskAssignment::with(['task', 'assignable'])
            ->where('assigned_to', $user->id)
            ->latest()
            ->paginate(15);
        
        return view('my-tasks', compact('taskAssignments'));
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
                    $assignable->update([
                        'status' => 'completed',
                        'actual_completion_date' => now(),
                    ]);
                }
            }
        }

        return back()->with('success', 'Task status updated successfully.');
    }
}
