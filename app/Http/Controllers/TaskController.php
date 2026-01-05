<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TaskController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view tasks', only: ['index', 'show']),
            new Middleware('permission:create tasks', only: ['create', 'store']),
            new Middleware('permission:edit tasks', only: ['edit', 'update']),
            new Middleware('permission:delete tasks', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with('department')
            ->orderBy('department_id')
            ->orderBy('priority')
            ->paginate(15);

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('tasks.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'priority' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            Task::create($validated);

            return redirect()->route('tasks.index')
                ->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create task: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load('department', 'taskAssignments');

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('tasks.edit', compact('task', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'priority' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            $task->update($validated);

            return redirect()->route('tasks.index')
                ->with('success', 'Task updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update task: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            // Check if task has active assignments
            if ($task->taskAssignments()->whereIn('status', ['pending', 'in_progress'])->count() > 0) {
                return back()->with('error', 'Cannot delete task with active assignments.');
            }

            $task->delete();

            return redirect()->route('tasks.index')
                ->with('success', 'Task deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete task: '.$e->getMessage());
        }
    }

    /**
     * Display onboarding tasks only.
     */
    public function onboardingTasks()
    {
        $tasks = Task::with('department')
            ->where('type', 'onboarding')
            ->orderBy('priority')
            ->paginate(15);

        $activeCount = Task::where('type', 'onboarding')
            ->where('is_active', true)
            ->count();

        return view('tasks.onboarding', compact('tasks', 'activeCount'));
    }

    /**
     * Display exit tasks only.
     */
    public function exitTasks()
    {
        $tasks = Task::with('department')
            ->where('type', 'exit')
            ->orderBy('priority')
            ->paginate(15);

        $activeCount = Task::where('type', 'exit')
            ->where('is_active', true)
            ->count();

        return view('tasks.exit', compact('tasks', 'activeCount'));
    }
}
