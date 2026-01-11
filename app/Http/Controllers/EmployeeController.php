<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Notification;
use App\Models\User;
use App\Mail\NewEmployeeNeedsEmail;
use App\Mail\EmployeeEmailUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::with('department')->paginate(15);

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->get();

        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_code' => 'required|string|unique:employees,employee_code',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employees,email',
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'status' => 'required|in:onboarding,active,exit_initiated,exited',
        ]);

        $employee = Employee::create($validated);

        // If email is not provided, notify IT team
        if (empty($validated['email'])) {
            $this->notifyITTeamForEmailCreation($employee);
        }

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully. ' . 
                (empty($validated['email']) ? 'IT team has been notified to create an email ID.' : 'You can now create an onboarding request to set up their account and assign tasks.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        $employee->load('department', 'onboardingRequests', 'exitClearanceRequests', 'assets.assignedBy');

        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)->get();

        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'employee_code' => 'required|string|unique:employees,employee_code,'.$employee->id,
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employees,email,'.$employee->id,
            'phone' => 'required|string|max:20',
            'department_id' => 'required|exists:departments,id',
            'designation' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'exit_date' => 'nullable|date|after_or_equal:joining_date',
            'status' => 'required|in:onboarding,active,exit_initiated,exited',
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    /**
     * Show the form for IT to update only the email field
     */
    public function editEmail(Employee $employee)
    {
        // Only allow if employee has no email yet
        if (!empty($employee->email)) {
            return redirect()->route('employees.index')
                ->with('error', 'This employee already has an email address.');
        }

        return view('employees.edit-email', compact('employee'));
    }

    /**
     * Update only the email field (for IT team)
     */
    public function updateEmail(Request $request, Employee $employee)
    {
        // Only allow if employee has no email yet
        if (!empty($employee->email)) {
            return redirect()->route('employees.index')
                ->with('error', 'This employee already has an email address.');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:employees,email,'.$employee->id,
        ]);

        $employee->update([
            'email' => $validated['email'],
            'email_created_by_it' => true,
            'email_created_at' => now(),
        ]);

        // Notify HR team that email has been created
        $this->notifyHRTeamEmailUpdated($employee);

        return redirect()->route('employees.index')
            ->with('success', 'Email ID created successfully. HR team has been notified.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }

    /**
     * Notify IT team that a new employee needs an email
     * 
     * Note: This assumes a department with type='IT' exists in the database.
     * The seeder creates departments with types: 'IT', 'HR', 'Admin', 'Finance', 'Operations'
     */
    private function notifyITTeamForEmailCreation(Employee $employee)
    {
        // Get IT department ID (type='IT' is set in the seeder)
        $itDepartment = Department::where('type', 'IT')->first();
        
        if (!$itDepartment) {
            Log::warning('IT department not found when trying to notify about new employee needing email', [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
            ]);
            return;
        }
        
        // Get all users in IT department
        $itUsers = User::where('department_id', $itDepartment->id)->get();
        
        if ($itUsers->isEmpty()) {
            Log::warning('No IT users found to notify about new employee needing email', [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
            ]);
            return;
        }
        
        foreach ($itUsers as $itUser) {
            // Create in-app notification
            Notification::create([
                'user_id' => $itUser->id,
                'title' => 'New Employee Needs Email ID',
                'message' => "A new employee {$employee->full_name} (Code: {$employee->employee_code}) has been added and needs an email ID to be created.",
                'type' => 'email_creation_required',
                'notifiable_type' => Employee::class,
                'notifiable_id' => $employee->id,
                'is_read' => false,
            ]);
            
            // Send email notification
            try {
                Mail::to($itUser->email)->queue(new NewEmployeeNeedsEmail($employee));
            } catch (\Exception $e) {
                // Log the error but don't fail the request
                Log::error('Failed to send email notification to IT', [
                    'error' => $e->getMessage(),
                    'it_user_id' => $itUser->id,
                    'employee_id' => $employee->id,
                ]);
            }
        }
    }

    /**
     * Notify HR team that email has been created/updated by IT
     * 
     * Note: This assumes a department with type='HR' exists and roles named 
     * 'Admin' and 'Super Admin' are defined. These are created by the seeder.
     */
    private function notifyHRTeamEmailUpdated(Employee $employee)
    {
        // Get HR department ID (type='HR' is set in the seeder)
        $hrDepartment = Department::where('type', 'HR')->first();
        
        // Get HR department users
        if ($hrDepartment) {
            $hrUsers = User::where('department_id', $hrDepartment->id)->get();
        } else {
            Log::warning('HR department not found when trying to notify about email creation', [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
            ]);
            $hrUsers = collect(); // Start with empty collection
        }
        
        // Also get all Admin and Super Admin users regardless of department
        // Role names 'Admin' and 'Super Admin' are defined in the seeder
        $adminUsers = User::whereHas('roles', function($roleQuery) {
            $roleQuery->whereIn('name', ['Admin', 'Super Admin']);
        })->get();
        
        // Merge HR users and admin users, removing duplicates
        $allRecipients = $hrUsers->merge($adminUsers)->unique('id');
        
        if ($allRecipients->isEmpty()) {
            Log::warning('No HR users or admins found to notify about email creation', [
                'employee_id' => $employee->id,
                'employee_code' => $employee->employee_code,
            ]);
            return;
        }
        
        foreach ($allRecipients as $recipient) {
            // Create in-app notification
            Notification::create([
                'user_id' => $recipient->id,
                'title' => 'Employee Email ID Created',
                'message' => "Email ID ({$employee->email}) has been created for employee {$employee->full_name} (Code: {$employee->employee_code}). The employee is now ready for onboarding.",
                'type' => 'email_created',
                'notifiable_type' => Employee::class,
                'notifiable_id' => $employee->id,
                'is_read' => false,
            ]);
            
            // Send email notification
            try {
                Mail::to($recipient->email)->queue(new EmployeeEmailUpdated($employee));
            } catch (\Exception $e) {
                // Log the error but don't fail the request
                Log::error('Failed to send email notification to HR', [
                    'error' => $e->getMessage(),
                    'recipient_user_id' => $recipient->id,
                    'employee_id' => $employee->id,
                ]);
            }
        }
    }
}
