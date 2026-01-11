<?php

namespace App\Http\Controllers;

use App\Mail\EmployeeEmailUpdated;
use App\Mail\NewEmployeeNeedsEmail;
use App\Models\CustomField;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['department', 'roles'])->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->get();
        $roles = Role::all();
        $customFields = CustomField::where('model_type', 'User')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('users.create', compact('departments', 'roles', 'customFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'password' => Hash::make($validated['password']),
                'department_id' => $validated['department_id'],
                'phone' => $validated['phone'],
                'status' => $validated['status'],
            ]);

            $user->assignRole($validated['role']);

            // Handle custom fields if they exist
            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $fieldId => $value) {
                    $user->customFieldValues()->create([
                        'custom_field_id' => $fieldId,
                        'value' => $value,
                    ]);
                }
            }

            // Send email to IT team if email is not provided
            if (empty($validated['email'])) {
                $this->notifyITTeamForNewEmployee($user);
            }

            DB::commit();

            return redirect()->route('users.index')
                ->with('success', 'User created successfully.' . (empty($validated['email']) ? ' IT team has been notified to create email ID.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to create user: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['department', 'roles', 'customFieldValues.customField']);
        $customFieldValues = $user->customFieldValues;

        return view('users.show', compact('user', 'customFieldValues'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $departments = Department::where('is_active', true)->get();
        $roles = Role::all();
        $customFields = CustomField::where('model_type', 'User')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $user->load('customFieldValues');

        return view('users.edit', compact('user', 'departments', 'roles', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|exists:roles,name',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            DB::beginTransaction();

            // Check if email is being updated from null to a value
            $emailWasNull = empty($user->email);
            $emailIsBeingSet = !empty($validated['email']);
            $shouldNotifyHR = $emailWasNull && $emailIsBeingSet;

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'] ?? null,
                'department_id' => $validated['department_id'],
                'phone' => $validated['phone'],
                'status' => $validated['status'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Update role
            $user->syncRoles([$validated['role']]);

            // Handle custom fields if they exist
            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $fieldId => $value) {
                    $user->customFieldValues()->updateOrCreate(
                        ['custom_field_id' => $fieldId],
                        ['value' => $value]
                    );
                }
            }

            // Notify HR team if email was added
            if ($shouldNotifyHR) {
                $this->notifyHRTeamForEmailUpdate($user);
            }

            DB::commit();

            $message = 'User updated successfully.';
            if ($shouldNotifyHR) {
                $message .= ' HR team has been notified about the email update.';
            }

            return redirect()->route('users.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Failed to update user: '.$e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deleting the current logged-in user
            if ($user->id === auth()->id()) {
                return back()->with('error', 'Cannot delete your own account.');
            }

            $user->delete();

            return redirect()->route('users.index')
                ->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user: '.$e->getMessage());
        }
    }

    /**
     * Notify IT team that a new employee needs an email ID.
     */
    private function notifyITTeamForNewEmployee(User $user): void
    {
        try {
            // Get all users with IT department role
            $itDepartment = Department::where('name', 'LIKE', '%IT%')->first();
            
            if ($itDepartment) {
                $itUsers = User::where('department_id', $itDepartment->id)
                    ->whereNotNull('email')
                    ->get();
                
                foreach ($itUsers as $itUser) {
                    Mail::to($itUser->email)->send(new NewEmployeeNeedsEmail($user));
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the user creation
            \Log::error('Failed to send email to IT team: ' . $e->getMessage());
        }
    }

    /**
     * Notify HR team that an employee's email has been updated.
     */
    private function notifyHRTeamForEmailUpdate(User $user): void
    {
        try {
            // Get all users with HR department
            $hrDepartment = Department::where('name', 'LIKE', '%HR%')->first();
            
            if ($hrDepartment) {
                $hrUsers = User::where('department_id', $hrDepartment->id)
                    ->whereNotNull('email')
                    ->get();
                
                foreach ($hrUsers as $hrUser) {
                    Mail::to($hrUser->email)->send(new EmployeeEmailUpdated($user));
                }
            }
        } catch (\Exception $e) {
            // Log the error but don't fail the user update
            \Log::error('Failed to send email to HR team: ' . $e->getMessage());
        }
    }
}
