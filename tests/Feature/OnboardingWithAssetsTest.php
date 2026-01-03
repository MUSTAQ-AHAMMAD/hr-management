<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Department;
use App\Models\Employee;
use App\Models\OnboardingRequest;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingWithAssetsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
    }

    public function test_employee_user_account_created_during_onboarding()
    {
        // Create admin user
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        // Create department
        $department = Department::factory()->create();

        // Create employee without user account
        $employee = Employee::factory()->create([
            'department_id' => $department->id,
            'email' => 'newemployee@test.com',
            'status' => 'onboarding',
            'user_id' => null,
        ]);

        // Assert employee has no user account
        $this->assertNull($employee->user_id);

        // Create onboarding request
        $this->actingAs($admin);
        $response = $this->post(route('onboarding-requests.store'), [
            'employee_id' => $employee->id,
            'expected_completion_date' => now()->addDays(7)->format('Y-m-d'),
            'notes' => 'New hire onboarding',
        ]);

        // Refresh employee
        $employee->refresh();

        // Assert employee now has a user account
        $this->assertNotNull($employee->user_id);
        $this->assertDatabaseHas('users', [
            'id' => $employee->user_id,
            'email' => 'newemployee@test.com',
            'department_id' => $department->id,
        ]);

        // Assert user has Employee role
        $user = User::find($employee->user_id);
        $this->assertTrue($user->hasRole('Employee'));
    }

    public function test_employee_can_view_pending_assets_on_dashboard()
    {
        // Use existing department from seeder
        $department = Department::where('type', 'IT')->first();
        $employee = Employee::factory()->create(['department_id' => $department->id]);
        $user = User::factory()->create([
            'email' => $employee->email,
            'department_id' => $department->id,
        ]);
        $user->assignRole('Employee');
        $employee->update(['user_id' => $user->id]);

        // Create pending assets for the employee
        $asset1 = Asset::create([
            'employee_id' => $employee->id,
            'asset_type' => 'Laptop',
            'asset_name' => 'Dell Latitude 5500',
            'serial_number' => 'LAP001',
            'assigned_by' => User::where('email', 'admin@hrmanagement.com')->first()->id,
            'assigned_date' => now(),
            'status' => 'assigned',
            'acceptance_status' => 'pending_acceptance',
            'department_id' => $department->id,
        ]);

        $asset2 = Asset::create([
            'employee_id' => $employee->id,
            'asset_type' => 'SIM Card',
            'asset_name' => 'Company SIM',
            'serial_number' => 'SIM001',
            'assigned_by' => User::where('email', 'admin@hrmanagement.com')->first()->id,
            'assigned_date' => now(),
            'status' => 'assigned',
            'acceptance_status' => 'pending_acceptance',
            'department_id' => $department->id,
        ]);

        // Access employee dashboard
        $this->actingAs($user);
        $response = $this->get(route('employee-dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dell Latitude 5500');
        $response->assertSee('Company SIM');
        $response->assertSee('Assets Pending Your Acceptance');
    }

    public function test_employee_can_accept_asset()
    {
        // Create employee with user account
        $department = Department::factory()->create();
        $employee = Employee::factory()->create(['department_id' => $department->id]);
        $user = User::factory()->create([
            'email' => $employee->email,
            'department_id' => $department->id,
        ]);
        $user->assignRole('Employee');
        $employee->update(['user_id' => $user->id]);

        // Create pending asset
        $asset = Asset::create([
            'employee_id' => $employee->id,
            'asset_type' => 'Laptop',
            'asset_name' => 'Dell Latitude 5500',
            'assigned_by' => User::where('email', 'admin@hrmanagement.com')->first()->id,
            'assigned_date' => now(),
            'status' => 'assigned',
            'acceptance_status' => 'pending_acceptance',
            'department_id' => $department->id,
        ]);

        // Accept the asset
        $this->actingAs($user);
        $response = $this->post(route('employee-dashboard.accept-asset', $asset));

        // Assert asset is accepted
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'acceptance_status' => 'accepted',
        ]);

        $asset->refresh();
        $this->assertNotNull($asset->acceptance_date);
    }

    public function test_employee_can_reject_asset()
    {
        // Use existing department from seeder
        $department = Department::where('type', 'Admin')->first();
        $employee = Employee::factory()->create(['department_id' => $department->id]);
        $user = User::factory()->create([
            'email' => $employee->email,
            'department_id' => $department->id,
        ]);
        $user->assignRole('Employee');
        $employee->update(['user_id' => $user->id]);

        // Create pending asset
        $asset = Asset::create([
            'employee_id' => $employee->id,
            'asset_type' => 'Laptop',
            'asset_name' => 'Dell Latitude 5500',
            'assigned_by' => User::where('email', 'admin@hrmanagement.com')->first()->id,
            'assigned_date' => now(),
            'status' => 'assigned',
            'acceptance_status' => 'pending_acceptance',
            'department_id' => $department->id,
        ]);

        // Reject the asset
        $this->actingAs($user);
        $response = $this->post(route('employee-dashboard.reject-asset', $asset), [
            'rejection_reason' => 'Laptop screen is damaged',
        ]);

        // Assert asset is rejected
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'acceptance_status' => 'rejected',
            'damage_notes' => 'Laptop screen is damaged',
        ]);
    }

    public function test_department_can_mark_asset_as_damaged()
    {
        // Use existing department from seeder
        $department = Department::where('type', 'IT')->first();
        $deptUser = User::factory()->create(['department_id' => $department->id]);
        $deptUser->assignRole('Department User');

        // Create employee and asset
        $employee = Employee::factory()->create(['department_id' => $department->id]);
        $asset = Asset::create([
            'employee_id' => $employee->id,
            'asset_type' => 'Laptop',
            'asset_name' => 'Dell Latitude 5500',
            'assigned_by' => $deptUser->id,
            'assigned_date' => now(),
            'status' => 'assigned',
            'acceptance_status' => 'accepted',
            'department_id' => $department->id,
        ]);

        // Mark asset as damaged
        $this->actingAs($deptUser);
        $response = $this->post(route('assets.mark-damaged', $asset), [
            'damage_notes' => 'Screen cracked, keyboard damaged',
            'depreciation_value' => 500.00,
        ]);

        // Assert asset is marked as damaged
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'status' => 'damaged',
            'damage_notes' => 'Screen cracked, keyboard damaged',
            'depreciation_value' => 500.00,
        ]);

        $asset->refresh();
        $this->assertNotNull($asset->return_date);
    }

    public function test_department_can_mark_asset_as_lost()
    {
        // Use existing department from seeder
        $department = Department::where('type', 'IT')->first();
        $deptUser = User::factory()->create(['department_id' => $department->id]);
        $deptUser->assignRole('Department User');

        // Create employee and asset
        $employee = Employee::factory()->create(['department_id' => $department->id]);
        $asset = Asset::create([
            'employee_id' => $employee->id,
            'asset_type' => 'Laptop',
            'asset_name' => 'Dell Latitude 5500',
            'assigned_by' => $deptUser->id,
            'assigned_date' => now(),
            'status' => 'assigned',
            'acceptance_status' => 'accepted',
            'department_id' => $department->id,
        ]);

        // Mark asset as lost
        $this->actingAs($deptUser);
        $response = $this->post(route('assets.mark-lost', $asset), [
            'damage_notes' => 'Employee reported laptop stolen',
            'depreciation_value' => 1200.00,
        ]);

        // Assert asset is marked as lost
        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'status' => 'lost',
            'damage_notes' => 'Employee reported laptop stolen',
            'depreciation_value' => 1200.00,
        ]);
    }

    public function test_task_assignment_can_be_partially_closed()
    {
        // Use existing department from seeder
        $department = Department::where('type', 'Operations')->first();
        $deptUser = User::factory()->create(['department_id' => $department->id]);
        $deptUser->assignRole('Department User');

        // Create employee and onboarding request
        $employee = Employee::factory()->create(['department_id' => $department->id]);
        $onboardingRequest = OnboardingRequest::create([
            'employee_id' => $employee->id,
            'initiated_by' => $deptUser->id,
            'status' => 'in_progress',
            'expected_completion_date' => now()->addDays(7),
        ]);

        // Create task and assignment
        $task = Task::create([
            'name' => 'Provide Laptop',
            'description' => 'Assign laptop to employee',
            'type' => 'onboarding',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        $taskAssignment = TaskAssignment::create([
            'task_id' => $task->id,
            'assigned_to' => $deptUser->id,
            'assignable_type' => OnboardingRequest::class,
            'assignable_id' => $onboardingRequest->id,
            'status' => 'in_progress',
            'due_date' => $onboardingRequest->expected_completion_date,
        ]);

        // Partially close the task
        $this->actingAs($deptUser);
        $response = $this->post(route('task-assignments.partially-close', $taskAssignment), [
            'partial_closure_reason' => 'Laptops out of stock, will assign when available',
            'notify_on_availability' => true,
        ]);

        // Assert task is partially closed
        $this->assertDatabaseHas('task_assignments', [
            'id' => $taskAssignment->id,
            'is_partially_closed' => true,
            'partial_closure_reason' => 'Laptops out of stock, will assign when available',
            'notify_on_availability' => true,
        ]);

        $taskAssignment->refresh();
        $this->assertNotNull($taskAssignment->partial_closure_date);
    }

    public function test_partially_closed_task_can_be_reopened()
    {
        // Use existing department from seeder
        $department = Department::where('type', 'Finance')->first();
        $deptUser = User::factory()->create(['department_id' => $department->id]);
        $deptUser->assignRole('Department User');

        // Create employee and onboarding request
        $employee = Employee::factory()->create(['department_id' => $department->id]);
        $onboardingRequest = OnboardingRequest::create([
            'employee_id' => $employee->id,
            'initiated_by' => $deptUser->id,
            'status' => 'in_progress',
            'expected_completion_date' => now()->addDays(7),
        ]);

        // Create task and assignment
        $task = Task::create([
            'name' => 'Provide Laptop',
            'description' => 'Assign laptop to employee',
            'type' => 'onboarding',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        $taskAssignment = TaskAssignment::create([
            'task_id' => $task->id,
            'assigned_to' => $deptUser->id,
            'assignable_type' => OnboardingRequest::class,
            'assignable_id' => $onboardingRequest->id,
            'status' => 'in_progress',
            'due_date' => $onboardingRequest->expected_completion_date,
            'is_partially_closed' => true,
            'partial_closure_reason' => 'Out of stock',
            'partial_closure_date' => now(),
        ]);

        // Reopen the task
        $this->actingAs($deptUser);
        $response = $this->post(route('task-assignments.reopen', $taskAssignment));

        // Assert task is reopened
        $this->assertDatabaseHas('task_assignments', [
            'id' => $taskAssignment->id,
            'is_partially_closed' => false,
            'partial_closure_reason' => null,
            'partial_closure_date' => null,
        ]);
    }

    public function test_employee_access_revoked_on_exit_clearance()
    {
        // Create admin user
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        // Use existing department from seeder
        $department = Department::where('type', 'HR')->first();

        // Create employee with user account
        $employee = Employee::factory()->create(['department_id' => $department->id]);
        $user = User::factory()->create([
            'email' => $employee->email,
            'department_id' => $department->id,
            'status' => 'active',
        ]);
        $user->assignRole('Employee');
        $employee->update(['user_id' => $user->id]);

        // Create exit clearance request
        $exitRequest = \App\Models\ExitClearanceRequest::create([
            'employee_id' => $employee->id,
            'initiated_by' => $admin->id,
            'status' => 'in_progress',
            'exit_date' => now()->addDays(7),
        ]);

        // Create task and assignment
        $task = Task::create([
            'name' => 'Collect Assets',
            'description' => 'Collect all company assets',
            'type' => 'exit',
            'department_id' => $department->id,
            'is_active' => true,
        ]);

        $taskAssignment = TaskAssignment::create([
            'task_id' => $task->id,
            'assigned_to' => $admin->id,
            'assignable_type' => \App\Models\ExitClearanceRequest::class,
            'assignable_id' => $exitRequest->id,
            'status' => 'in_progress',
            'due_date' => $exitRequest->exit_date,
        ]);

        // Complete the task
        $this->actingAs($admin);
        $response = $this->post(route('task-assignments.update-status', $taskAssignment), [
            'status' => 'completed',
            'notes' => 'All assets collected',
        ]);

        // Assert user account is deactivated
        $user->refresh();
        $this->assertEquals('inactive', $user->status);

        // Assert exit request is cleared
        $exitRequest->refresh();
        $this->assertEquals('cleared', $exitRequest->status);
        $this->assertNotNull($exitRequest->clearance_date);
    }
}
