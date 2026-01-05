<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskPermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleAndPermissionSeeder::class);
    }

    public function test_admin_can_access_tasks_index()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertStatus(200);
    }

    public function test_department_user_can_view_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Department User');

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertStatus(200);
    }

    public function test_employee_cannot_view_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Employee');

        $response = $this->actingAs($user)->get(route('tasks.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');
        
        $department = Department::first();

        $response = $this->actingAs($user)->get(route('tasks.create'));

        $response->assertStatus(200);
    }

    public function test_department_user_cannot_create_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Department User');

        $response = $this->actingAs($user)->get(route('tasks.create'));

        $response->assertStatus(403);
    }

    public function test_admin_can_edit_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');
        
        $task = Task::first();

        $response = $this->actingAs($user)->get(route('tasks.edit', $task));

        $response->assertStatus(200);
    }

    public function test_department_user_cannot_edit_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Department User');
        
        $task = Task::first();

        $response = $this->actingAs($user)->get(route('tasks.edit', $task));

        $response->assertStatus(403);
    }

    public function test_super_admin_can_delete_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Super Admin');
        
        $department = Department::first();
        
        // Create a new task without dependencies
        $task = Task::create([
            'name' => 'Test Task',
            'description' => 'Test description',
            'type' => 'onboarding',
            'department_id' => $department->id,
            'priority' => 99,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_admin_cannot_delete_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');
        
        $department = Department::first();
        
        // Create a new task without dependencies
        $task = Task::create([
            'name' => 'Test Task 2',
            'description' => 'Test description',
            'type' => 'onboarding',
            'department_id' => $department->id,
            'priority' => 99,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_department_user_cannot_delete_tasks()
    {
        $user = User::factory()->create();
        $user->assignRole('Department User');
        
        $task = Task::first();

        $response = $this->actingAs($user)->delete(route('tasks.destroy', $task));

        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    public function test_unauthenticated_user_redirected_to_login()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertRedirect(route('login'));
    }
}
