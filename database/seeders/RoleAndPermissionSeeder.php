<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Department;
use App\Models\Task;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create departments
        $departments = [
            ['name' => 'IT Department', 'description' => 'Information Technology Department', 'type' => 'IT', 'is_active' => true],
            ['name' => 'Admin Department', 'description' => 'Administration Department', 'type' => 'Admin', 'is_active' => true],
            ['name' => 'Finance Department', 'description' => 'Finance and Accounts Department', 'type' => 'Finance', 'is_active' => true],
            ['name' => 'HR Department', 'description' => 'Human Resources Department', 'type' => 'HR', 'is_active' => true],
            ['name' => 'Operations', 'description' => 'Operations Department', 'type' => 'Operations', 'is_active' => true],
        ];

        foreach ($departments as $deptData) {
            Department::create($deptData);
        }

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Employee management
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            
            // Department management
            'view departments',
            'create departments',
            'edit departments',
            'delete departments',
            
            // Onboarding management
            'view onboarding',
            'create onboarding',
            'edit onboarding',
            'process onboarding',
            'complete onboarding',
            
            // Exit clearance management
            'view exit-clearance',
            'create exit-clearance',
            'edit exit-clearance',
            'process exit-clearance',
            'approve exit-clearance',
            'generate clearance-documents',
            
            // Task management
            'view tasks',
            'create tasks',
            'edit tasks',
            'delete tasks',
            'assign tasks',
            'complete tasks',
            
            // Reports and analytics
            'view reports',
            'view analytics',
            'export reports',
            
            // Notifications
            'send notifications',
            'view all notifications',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        
        // Super Admin - All permissions
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - Most permissions except user deletion
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'view users', 'create users', 'edit users',
            'view employees', 'create employees', 'edit employees',
            'view departments', 'create departments', 'edit departments',
            'view onboarding', 'create onboarding', 'edit onboarding', 'process onboarding', 'complete onboarding',
            'view exit-clearance', 'create exit-clearance', 'edit exit-clearance', 'process exit-clearance', 'approve exit-clearance', 'generate clearance-documents',
            'view tasks', 'create tasks', 'edit tasks', 'assign tasks', 'complete tasks',
            'view reports', 'view analytics', 'export reports',
            'send notifications', 'view all notifications',
        ]);

        // Department User - Limited permissions
        $deptUser = Role::create(['name' => 'Department User']);
        $deptUser->givePermissionTo([
            'view employees',
            'view onboarding', 'process onboarding',
            'view exit-clearance', 'process exit-clearance',
            'view tasks', 'complete tasks',
            'view reports',
        ]);

        // Create default super admin user
        $superAdminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@hrmanagement.com',
            'password' => Hash::make('password'),
            'department_id' => 4, // HR Department
            'phone' => '+1234567890',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $superAdminUser->assignRole('Super Admin');

        // Create sample admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'hr@hrmanagement.com',
            'password' => Hash::make('password'),
            'department_id' => 4, // HR Department
            'phone' => '+1234567891',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('Admin');

        // Create sample department users
        $itUser = User::create([
            'name' => 'IT Manager',
            'email' => 'it@hrmanagement.com',
            'password' => Hash::make('password'),
            'department_id' => 1, // IT Department
            'phone' => '+1234567892',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $itUser->assignRole('Department User');

        $financeUser = User::create([
            'name' => 'Finance Manager',
            'email' => 'finance@hrmanagement.com',
            'password' => Hash::make('password'),
            'department_id' => 3, // Finance Department
            'phone' => '+1234567893',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);
        $financeUser->assignRole('Department User');

        // Create default tasks for onboarding
        $onboardingTasks = [
            ['name' => 'Provide Laptop', 'description' => 'Assign and configure laptop for new employee', 'type' => 'onboarding', 'department_id' => 1, 'priority' => 1, 'is_active' => true],
            ['name' => 'Provide SIM Card', 'description' => 'Provide company SIM card', 'type' => 'onboarding', 'department_id' => 1, 'priority' => 2, 'is_active' => true],
            ['name' => 'Create Email Account', 'description' => 'Set up company email account', 'type' => 'onboarding', 'department_id' => 1, 'priority' => 3, 'is_active' => true],
            ['name' => 'Create Employee ID', 'description' => 'Generate and assign employee ID card', 'type' => 'onboarding', 'department_id' => 2, 'priority' => 1, 'is_active' => true],
            ['name' => 'Assign Workspace', 'description' => 'Allocate desk and workspace', 'type' => 'onboarding', 'department_id' => 2, 'priority' => 2, 'is_active' => true],
            ['name' => 'Setup Payroll', 'description' => 'Add employee to payroll system', 'type' => 'onboarding', 'department_id' => 3, 'priority' => 1, 'is_active' => true],
        ];

        foreach ($onboardingTasks as $taskData) {
            Task::create($taskData);
        }

        // Create default tasks for exit clearance
        $exitTasks = [
            ['name' => 'Collect Laptop', 'description' => 'Collect company laptop and verify condition', 'type' => 'exit', 'department_id' => 1, 'priority' => 1, 'is_active' => true],
            ['name' => 'Collect SIM Card', 'description' => 'Collect company SIM card', 'type' => 'exit', 'department_id' => 1, 'priority' => 2, 'is_active' => true],
            ['name' => 'Disable Email Account', 'description' => 'Disable company email account', 'type' => 'exit', 'department_id' => 1, 'priority' => 3, 'is_active' => true],
            ['name' => 'Collect ID Card', 'description' => 'Collect employee ID card and access cards', 'type' => 'exit', 'department_id' => 2, 'priority' => 1, 'is_active' => true],
            ['name' => 'Clear Workspace', 'description' => 'Ensure workspace is cleared', 'type' => 'exit', 'department_id' => 2, 'priority' => 2, 'is_active' => true],
            ['name' => 'Final Settlement', 'description' => 'Process final salary and dues', 'type' => 'exit', 'department_id' => 3, 'priority' => 1, 'is_active' => true],
            ['name' => 'Clear Advances', 'description' => 'Clear any pending advances or loans', 'type' => 'exit', 'department_id' => 3, 'priority' => 2, 'is_active' => true],
        ];

        foreach ($exitTasks as $taskData) {
            Task::create($taskData);
        }

        // Create sample employees
        $employees = [
            [
                'employee_code' => 'EMP001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@company.com',
                'phone' => '+1234567894',
                'department_id' => 1, // IT Department
                'designation' => 'Senior Software Engineer',
                'joining_date' => now()->subYears(2),
                'status' => 'active',
            ],
            [
                'employee_code' => 'EMP002',
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@company.com',
                'phone' => '+1234567895',
                'department_id' => 4, // HR Department
                'designation' => 'HR Manager',
                'joining_date' => now()->subYears(3),
                'status' => 'active',
            ],
            [
                'employee_code' => 'EMP003',
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@company.com',
                'phone' => '+1234567896',
                'department_id' => 3, // Finance Department
                'designation' => 'Finance Officer',
                'joining_date' => now()->subYear(),
                'status' => 'active',
            ],
            [
                'employee_code' => 'EMP004',
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah.williams@company.com',
                'phone' => '+1234567897',
                'department_id' => 2, // Admin Department
                'designation' => 'Administrative Assistant',
                'joining_date' => now()->subMonths(6),
                'status' => 'active',
            ],
            [
                'employee_code' => 'EMP005',
                'first_name' => 'Robert',
                'last_name' => 'Brown',
                'email' => 'robert.brown@company.com',
                'phone' => '+1234567898',
                'department_id' => 5, // Operations
                'designation' => 'Operations Manager',
                'joining_date' => now()->subYears(4),
                'status' => 'active',
            ],
        ];

        foreach ($employees as $employeeData) {
            \App\Models\Employee::create($employeeData);
        }
    }
}
