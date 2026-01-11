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
        $employee->load('department', 'onboardingRequests', 'exitClearanceRequests');

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
     */
    private function notifyITTeamForEmailCreation(Employee $employee)
    {
        // Get IT department ID
        $itDepartment = Department::where('type', 'IT')->first();
        
        if ($itDepartment) {
            // Get all users in IT department
            $itUsers = User::where('department_id', $itDepartment->id)->get();
            
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
                    \Log::error('Failed to send email notification to IT: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Notify HR team that email has been created/updated by IT
     */
    private function notifyHRTeamEmailUpdated(Employee $employee)
    {
        // Get HR department ID
        $hrDepartment = Department::where('type', 'HR')->first();
        
        if ($hrDepartment) {
            // Get all users in HR department with appropriate permissions
            $hrUsers = User::where('department_id', $hrDepartment->id)
                ->orWhereHas('roles', function($query) {
                    $query->whereIn('name', ['Admin', 'Super Admin']);
                })
                ->get();
            
            foreach ($hrUsers as $hrUser) {
                // Create in-app notification
                Notification::create([
                    'user_id' => $hrUser->id,
                    'title' => 'Employee Email ID Created',
                    'message' => "Email ID ({$employee->email}) has been created for employee {$employee->full_name} (Code: {$employee->employee_code}). The employee is now ready for onboarding.",
                    'type' => 'email_created',
                    'notifiable_type' => Employee::class,
                    'notifiable_id' => $employee->id,
                    'is_read' => false,
                ]);
                
                // Send email notification
                try {
                    Mail::to($hrUser->email)->queue(new EmployeeEmailUpdated($employee));
                } catch (\Exception $e) {
                    // Log the error but don't fail the request
                    \Log::error('Failed to send email notification to HR: ' . $e->getMessage());
                }
            }
        }
    }
}
