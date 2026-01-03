<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\ExitClearanceRequest;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExitClearanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
    }

    public function test_exit_clearance_status_changes_to_cleared_when_all_tasks_completed()
    {
        // Create a user with necessary permissions
        $user = User::factory()->create();
        $user->assignRole('Admin');

        // Create a department
        $department = Department::factory()->create();
        $user->update(['department_id' => $department->id]);

        // Create an employee
        $employee = Employee::factory()->create(['department_id' => $department->id]);

        // Create an exit clearance request
        $exitRequest = ExitClearanceRequest::create([
            'employee_id' => $employee->id,
            'initiated_by' => $user->id,
            'status' => 'in_progress',
            'exit_date' => now()->addDays(7),
        ]);

        // Create a task for the department
        $task = Task::create([
            'name' => 'Test Task',
            'description' => 'Test description',
            'type' => 'exit',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        // Create a task assignment
        $taskAssignment = TaskAssignment::create([
            'task_id' => $task->id,
            'assigned_to' => $user->id,
            'assignable_type' => ExitClearanceRequest::class,
            'assignable_id' => $exitRequest->id,
            'status' => 'pending',
            'due_date' => $exitRequest->exit_date,
        ]);

        // Act as the user and complete the task
        $this->actingAs($user);

        $response = $this->post(route('task-assignments.update-status', $taskAssignment), [
            'status' => 'completed',
            'notes' => 'Task completed successfully',
        ]);

        // Assert the task assignment is completed
        $this->assertDatabaseHas('task_assignments', [
            'id' => $taskAssignment->id,
            'status' => 'completed',
        ]);

        // Assert the exit clearance request status is now 'cleared'
        $exitRequest->refresh();
        $this->assertEquals('cleared', $exitRequest->status);
        $this->assertNotNull($exitRequest->clearance_date);
    }

    public function test_exit_clearance_status_remains_in_progress_when_not_all_tasks_completed()
    {
        // Create a user with necessary permissions
        $user = User::factory()->create();
        $user->assignRole('Admin');

        // Use existing department from seeder
        $department = Department::where('type', 'Admin')->first();
        $user->update(['department_id' => $department->id]);

        // Create an employee
        $employee = Employee::factory()->create(['department_id' => $department->id]);

        // Create an exit clearance request
        $exitRequest = ExitClearanceRequest::create([
            'employee_id' => $employee->id,
            'initiated_by' => $user->id,
            'status' => 'in_progress',
            'exit_date' => now()->addDays(7),
        ]);

        // Create two tasks
        $task1 = Task::create([
            'name' => 'Task 1',
            'description' => 'Test description 1',
            'type' => 'exit',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        $task2 = Task::create([
            'name' => 'Task 2',
            'description' => 'Test description 2',
            'type' => 'exit',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        // Create task assignments
        $taskAssignment1 = TaskAssignment::create([
            'task_id' => $task1->id,
            'assigned_to' => $user->id,
            'assignable_type' => ExitClearanceRequest::class,
            'assignable_id' => $exitRequest->id,
            'status' => 'pending',
            'due_date' => $exitRequest->exit_date,
        ]);

        $taskAssignment2 = TaskAssignment::create([
            'task_id' => $task2->id,
            'assigned_to' => $user->id,
            'assignable_type' => ExitClearanceRequest::class,
            'assignable_id' => $exitRequest->id,
            'status' => 'pending',
            'due_date' => $exitRequest->exit_date,
        ]);

        // Complete only the first task
        $this->actingAs($user);

        $this->post(route('task-assignments.update-status', $taskAssignment1), [
            'status' => 'completed',
            'notes' => 'Task 1 completed',
        ]);

        // Assert the exit clearance request status remains 'in_progress'
        $exitRequest->refresh();
        $this->assertEquals('in_progress', $exitRequest->status);
        $this->assertNull($exitRequest->clearance_date);
    }

    public function test_pdf_generation_prevented_when_tasks_pending()
    {
        // Create a user with necessary permissions
        $user = User::factory()->create();
        $user->assignRole('Admin');

        // Create a department
        $department = Department::factory()->create();
        $user->update(['department_id' => $department->id]);

        // Create an employee
        $employee = Employee::factory()->create(['department_id' => $department->id]);

        // Create an exit clearance request
        $exitRequest = ExitClearanceRequest::create([
            'employee_id' => $employee->id,
            'initiated_by' => $user->id,
            'status' => 'in_progress',
            'exit_date' => now()->addDays(7),
        ]);

        // Create a task
        $task = Task::create([
            'name' => 'Pending Task',
            'description' => 'Test description',
            'type' => 'exit',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        // Create a pending task assignment
        TaskAssignment::create([
            'task_id' => $task->id,
            'assigned_to' => $user->id,
            'assignable_type' => ExitClearanceRequest::class,
            'assignable_id' => $exitRequest->id,
            'status' => 'pending',
            'due_date' => $exitRequest->exit_date,
        ]);

        // Try to generate PDF
        $this->actingAs($user);

        $response = $this->post(route('exit-clearance-requests.generate-pdf', $exitRequest));

        // Assert that PDF generation was prevented
        $response->assertSessionHas('error', 'Cannot generate PDF. Please complete all clearance tasks first.');
    }

    public function test_pdf_generation_allowed_when_all_tasks_completed()
    {
        // Create a user with necessary permissions
        $user = User::factory()->create();
        $user->assignRole('Admin');

        // Create a department
        $department = Department::factory()->create();
        $user->update(['department_id' => $department->id]);

        // Create an employee
        $employee = Employee::factory()->create(['department_id' => $department->id]);

        // Create an exit clearance request
        $exitRequest = ExitClearanceRequest::create([
            'employee_id' => $employee->id,
            'initiated_by' => $user->id,
            'status' => 'cleared',
            'exit_date' => now()->addDays(7),
            'clearance_date' => now(),
        ]);

        // Create a task
        $task = Task::create([
            'name' => 'Completed Task',
            'description' => 'Test description',
            'type' => 'exit',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        // Create a completed task assignment
        TaskAssignment::create([
            'task_id' => $task->id,
            'assigned_to' => $user->id,
            'assignable_type' => ExitClearanceRequest::class,
            'assignable_id' => $exitRequest->id,
            'status' => 'completed',
            'due_date' => $exitRequest->exit_date,
            'completed_date' => now(),
        ]);

        // Try to generate PDF
        $this->actingAs($user);

        $response = $this->post(route('exit-clearance-requests.generate-pdf', $exitRequest));

        // Assert that PDF generation was successful (downloads a file)
        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
