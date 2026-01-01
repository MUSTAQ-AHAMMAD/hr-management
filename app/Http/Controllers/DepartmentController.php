<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::withCount('users')->paginate(15);
        
        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customFields = CustomField::where('model_type', 'Department')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('departments.create', compact('customFields'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
            'type' => 'required|in:IT,Admin,Finance,HR,Operations,Other',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            DB::beginTransaction();
            
            $department = Department::create($validated);
            
            // Handle custom fields if they exist
            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $fieldId => $value) {
                    $department->customFieldValues()->create([
                        'custom_field_id' => $fieldId,
                        'value' => $value,
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('departments.index')
                ->with('success', 'Department created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create department: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $department->load(['users', 'customFieldValues.customField']);
        $customFieldValues = $department->customFieldValues;
        
        return view('departments.show', compact('department', 'customFieldValues'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $customFields = CustomField::where('model_type', 'Department')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
        
        $department->load('customFieldValues');
        
        return view('departments.edit', compact('department', 'customFields'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'description' => 'nullable|string',
            'type' => 'required|in:IT,Admin,Finance,HR,Operations,Other',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        try {
            DB::beginTransaction();
            
            $department->update($validated);
            
            // Handle custom fields if they exist
            if ($request->has('custom_fields')) {
                foreach ($request->custom_fields as $fieldId => $value) {
                    $department->customFieldValues()->updateOrCreate(
                        ['custom_field_id' => $fieldId],
                        ['value' => $value]
                    );
                }
            }
            
            DB::commit();
            
            return redirect()->route('departments.index')
                ->with('success', 'Department updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update department: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try {
            // Check if department has users
            if ($department->users()->count() > 0) {
                return back()->with('error', 'Cannot delete department with assigned users.');
            }
            
            $department->delete();
            
            return redirect()->route('departments.index')
                ->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}
