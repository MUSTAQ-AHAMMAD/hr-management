<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OnboardingRequestController;
use App\Http\Controllers\ExitClearanceRequestController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskAssignmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomFieldController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Departments
    Route::resource('departments', DepartmentController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Users
    Route::resource('users', UserController::class);

    // Custom Fields (Super Admin only)
    Route::middleware('role:Super Admin')->group(function () {
        Route::resource('custom-fields', CustomFieldController::class);
    });

    // Onboarding Requests
    Route::resource('onboarding-requests', OnboardingRequestController::class);
    Route::post('onboarding-requests/{onboardingRequest}/assign-tasks', [OnboardingRequestController::class, 'assignTasks'])->name('onboarding-requests.assign-tasks');

    // Exit Clearance Requests
    Route::resource('exit-clearance-requests', ExitClearanceRequestController::class);
    Route::post('exit-clearance-requests/{exitClearanceRequest}/assign-tasks', [ExitClearanceRequestController::class, 'assignTasks'])->name('exit-clearance-requests.assign-tasks');
    Route::post('exit-clearance-requests/{exitClearanceRequest}/generate-pdf', [ExitClearanceRequestController::class, 'generatePdf'])->name('exit-clearance-requests.generate-pdf');

    // Tasks
    Route::resource('tasks', TaskController::class);

    // Task Assignments
    Route::post('task-assignments/{taskAssignment}/update-status', [TaskAssignmentController::class, 'updateStatus'])->name('task-assignments.update-status');
    Route::get('my-tasks', [TaskAssignmentController::class, 'myTasks'])->name('my-tasks');

    // Notifications
    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
});

require __DIR__.'/auth.php';
