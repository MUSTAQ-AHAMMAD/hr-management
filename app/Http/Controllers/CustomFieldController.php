<?php

namespace App\Http\Controllers;

use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customFields = CustomField::orderBy('model_type')->orderBy('order')->paginate(20);
        
        return view('custom-fields.index', compact('customFields'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('custom-fields.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'model_type' => 'required|in:Department,User,Employee',
            'field_name' => 'required|string|max:255|unique:custom_fields,field_name',
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
        return view('custom-fields.show', compact('customField'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomField $customField)
    {
        return view('custom-fields.edit', compact('customField'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CustomField $customField)
    {
        $validated = $request->validate([
            'model_type' => 'required|in:Department,User,Employee',
            'field_name' => 'required|string|max:255|unique:custom_fields,field_name,' . $customField->id,
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
        try {
            $customField->delete();
            
            return redirect()->route('custom-fields.index')
                ->with('success', 'Custom field deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete custom field: ' . $e->getMessage());
        }
    }
}
