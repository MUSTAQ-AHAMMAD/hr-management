<?php

use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ExitClearanceRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OnboardingRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employee Dashboard (for employees with limited access)
    Route::get('/employee-dashboard', [\App\Http\Controllers\EmployeeDashboardController::class, 'index'])->name('employee-dashboard');
    Route::post('/employee-dashboard/assets/{asset}/accept', [\App\Http\Controllers\EmployeeDashboardController::class, 'acceptAsset'])->name('employee-dashboard.accept-asset');
    Route::post('/employee-dashboard/assets/{asset}/reject', [\App\Http\Controllers\EmployeeDashboardController::class, 'rejectAsset'])->name('employee-dashboard.reject-asset');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Departments
    Route::resource('departments', DepartmentController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/edit-email', [EmployeeController::class, 'editEmail'])->name('employees.edit-email');
    Route::patch('employees/{employee}/update-email', [EmployeeController::class, 'updateEmail'])->name('employees.update-email');

    // Users
    Route::resource('users', UserController::class);

    // Custom Fields (Super Admin only - authorization checked in controller)
    Route::resource('custom-fields', CustomFieldController::class);

    // Onboarding Requests
    Route::resource('onboarding-requests', OnboardingRequestController::class);
    Route::post('onboarding-requests/{onboardingRequest}/assign-tasks', [OnboardingRequestController::class, 'assignTasks'])->name('onboarding-requests.assign-tasks');

    // Exit Clearance Requests
    Route::resource('exit-clearance-requests', ExitClearanceRequestController::class);
    Route::post('exit-clearance-requests/{exitClearanceRequest}/assign-tasks', [ExitClearanceRequestController::class, 'assignTasks'])->name('exit-clearance-requests.assign-tasks');
    Route::post('exit-clearance-requests/{exitClearanceRequest}/generate-pdf', [ExitClearanceRequestController::class, 'generatePdf'])->name('exit-clearance-requests.generate-pdf');

    // Assets
    Route::get('assets-reports', [AssetController::class, 'reports'])->name('assets.reports');
    Route::resource('assets', AssetController::class);
    Route::post('assets/{asset}/mark-returned', [AssetController::class, 'markAsReturned'])->name('assets.mark-returned');
    Route::post('assets/{asset}/mark-damaged', [AssetController::class, 'markAsDamaged'])->name('assets.mark-damaged');
    Route::post('assets/{asset}/mark-lost', [AssetController::class, 'markAsLost'])->name('assets.mark-lost');

    // Tasks
    Route::resource('tasks', TaskController::class);
    Route::get('tasks-onboarding', [TaskController::class, 'onboardingTasks'])->name('tasks.onboarding');
    Route::get('tasks-exit', [TaskController::class, 'exitTasks'])->name('tasks.exit');

    // Task Assignments
    Route::post('task-assignments/{taskAssignment}/update-status', [TaskAssignmentController::class, 'updateStatus'])->name('task-assignments.update-status');
    Route::post('task-assignments/{taskAssignment}/partially-close', [TaskAssignmentController::class, 'partiallyClose'])->name('task-assignments.partially-close');
    Route::post('task-assignments/{taskAssignment}/reopen', [TaskAssignmentController::class, 'reopenTask'])->name('task-assignments.reopen');
    Route::get('my-tasks', [TaskAssignmentController::class, 'myTasks'])->name('my-tasks');
    Route::get('my-tasks/onboarding', [TaskAssignmentController::class, 'myOnboardingTasks'])->name('my-tasks.onboarding');
    Route::get('my-tasks/exit', [TaskAssignmentController::class, 'myExitTasks'])->name('my-tasks.exit');
    Route::get('task-assignments/by-employee', [TaskAssignmentController::class, 'employeeAssignments'])->name('task-assignments.by-employee');
    Route::get('task-assignments/employee/{employee}', [TaskAssignmentController::class, 'employeeDetail'])->name('task-assignments.employee-detail');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

require __DIR__.'/auth.php';
