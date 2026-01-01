<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check if user is Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can manage custom fields.');
        }
        
        $customFields = CustomField::orderBy('model_type')->orderBy('order')->paginate(20);
        
        return view('custom-fields.index', compact('customFields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user is Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can manage custom fields.');
        }
        
        return view('custom-fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user is Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can manage custom fields.');
        }
        
        $validated = $request->validate([
            'model_type' => 'required|in:Department,User,Employee',
            'field_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('custom_fields')->where(function ($query) use ($request) {
                    return $query->where('model_type', $request->model_type);
                })
            ],
            'label' => 'required|string|max:255',
            'field_type' => 'required|in:text,textarea,number,date,select,checkbox',
            'options' => 'nullable|string',
            'help_text' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Convert options string to array for select fields
        if ($validated['field_type'] === 'select' && !empty($validated['options'])) {
            $validated['options'] = array_map('trim', explode(',', $validated['options']));
        } else {
            $validated['options'] = null;
        }

        $validated['is_required'] = $request->has('is_required');
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        CustomField::create($validated);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CustomField $customField)
    {
        // Check if user is Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can manage custom fields.');
        }
        
        return view('custom-fields.show', compact('customField'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomField $customField)
    {
        // Check if user is Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can manage custom fields.');
        }
        
        return view('custom-fields.edit', compact('customField'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomField $customField)
    {
        // Check if user is Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can manage custom fields.');
        }
        
        $validated = $request->validate([
            'model_type' => 'required|in:Department,User,Employee',
            'field_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('custom_fields')->where(function ($query) use ($request) {
                    return $query->where('model_type', $request->model_type);
                })->ignore($customField->id)
            ],
            'label' => 'required|string|max:255',
            'field_type' => 'required|in:text,textarea,number,date,select,checkbox',
            'options' => 'nullable|string',
            'help_text' => 'nullable|string',
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0',
        ]);

        // Convert options string to array for select fields
        if ($validated['field_type'] === 'select' && !empty($validated['options'])) {
            $validated['options'] = array_map('trim', explode(',', $validated['options']));
        } else {
            $validated['options'] = null;
        }

        $validated['is_required'] = $request->has('is_required');
        $validated['is_active'] = $request->has('is_active');
        $validated['order'] = $validated['order'] ?? 0;

        $customField->update($validated);

        return redirect()->route('custom-fields.index')
            ->with('success', 'Custom field updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CustomField $customField)
    {
        // Check if user is Super Admin
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403, 'Only Super Admins can manage custom fields.');
        }
        
        try {
            $customField->delete();
            
            return redirect()->route('custom-fields.index')
                ->with('success', 'Custom field deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete custom field: ' . $e->getMessage());
        }
    }
}
